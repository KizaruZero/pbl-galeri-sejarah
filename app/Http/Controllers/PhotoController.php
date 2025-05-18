<?php

namespace App\Http\Controllers;

use App\Models\CategoryContent;
use App\Models\ContentPhoto;
use App\Models\UserFavorite;
use App\Models\MetadataPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Auth;

class PhotoController extends Controller
{
    public function index()
    {
        $contentPhoto = ContentPhoto::with(['metadataPhoto', 'userComments', 'userComments.userReactions', 'user', 'categoryContents'])
            ->where('status', 'approved')
            ->get();
        if (!$contentPhoto) {
            return response()->json(['message' => 'Photo not found'], 404);
        }
        return response()->json($contentPhoto);
    }

    public function show($slug)
    {
        $contentPhoto = ContentPhoto::with(['metadataPhoto', 'userComments', 'user', 'categoryContents'])
            ->where('status', 'approved')
            ->where('slug', $slug)
            ->first();
        if (!$contentPhoto) {
            return response()->json(['message' => 'Photo not found'], 404);
        }
        $contentPhoto->updateTotalViews();
        return response()->json($contentPhoto);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'source' => 'nullable|string|max:255',
            'alt_text' => 'nullable|string|max:255',
            'note' => 'nullable|string',
            'tag' => 'nullable|string|max:255',
        ]);

        $userId = Auth::user()->id;
        if (!$userId) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        // Handle file upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();

            $path = $file->storeAs('foto_content', $filename, 'public');

            // Save only the relative path (without 'public/') to DB
            $imageUrl = 'foto_content/' . $filename;

            // Create slug from title
            $slug = Str::slug($request->title);

            // Create new photo record
            $photo = ContentPhoto::create([
                'title' => $request->title,
                'slug' => $slug,
                'user_id' => $userId,
                'description' => $request->description,
                'source' => $request->source,
                'alt_text' => $request->alt_text,
                'note' => $request->note,
                'tag' => $request->tag,
                'image_url' => $imageUrl,
                'status' => 'pending',
            ]);

            // Extract EXIF metadata
            try {
                $exif = exif_read_data($file->getPathname());

                // Get image dimensions
                $image = Image::make($file->getPathname());
                $dimensions = $image->width() . 'x' . $image->height();

                // Create metadata record
                MetadataPhoto::create([
                    'content_photo_id' => $photo->id,
                    'collection_date' => isset($exif['DateTimeOriginal']) ? date('Y-m-d H:i:s', strtotime($exif['DateTimeOriginal'])) : null,
                    'file_size' => $file->getSize(),
                    'aperture' => isset($exif['COMPUTED']['ApertureFNumber']) ? $exif['COMPUTED']['ApertureFNumber'] : null,
                    'location' => isset($exif['GPSLatitude']) ? $this->getGPSCoordinates($exif) : null,
                    'model' => isset($exif['Model']) ? $exif['Model'] : null,
                    'ISO' => isset($exif['ISOSpeedRatings']) ? $exif['ISOSpeedRatings'] : null,
                    'dimensions' => $dimensions,
                ]);
            } catch (\Exception $e) {
                // Log error but continue with photo upload
                \Log::error('Error extracting EXIF data: ' . $e->getMessage());
            }

            return response()->json([
                'message' => 'Photo uploaded successfully',
                'data' => $photo->load('metadataPhoto')
            ], 201);
        }

        return response()->json([
            'message' => 'No image file provided'
        ], 400);
    }

    private function getGPSCoordinates($exif)
    {
        if (!isset($exif['GPSLatitude']) || !isset($exif['GPSLongitude'])) {
            return null;
        }

        $lat = $this->getGpsCoordinate($exif['GPSLatitude'], $exif['GPSLatitudeRef']);
        $lng = $this->getGpsCoordinate($exif['GPSLongitude'], $exif['GPSLongitudeRef']);

        return $lat . ',' . $lng;
    }

    private function getGpsCoordinate($coordinate, $hemisphere)
    {
        if (is_string($coordinate)) {
            $coordinate = explode(',', $coordinate);
        }

        for ($i = 0; $i < 3; $i++) {
            $part = explode('/', $coordinate[$i]);
            if (count($part) == 1) {
                $coordinate[$i] = $part[0];
            } else if (count($part) == 2) {
                $coordinate[$i] = floatval($part[0]) / floatval($part[1]);
            } else {
                $coordinate[$i] = 0;
            }
        }

        list($degrees, $minutes, $seconds) = $coordinate;
        $flip = ($hemisphere == 'W' || $hemisphere == 'S') ? -1 : 1;
        return $flip * ($degrees + $minutes / 60 + $seconds / 3600);
    }

    public function getPhotoByUser($userId)
    {
        $contentPhotos = ContentPhoto::with(['metadataPhoto', 'userComments', 'user', 'categoryContents'])
            ->where('status', 'approved')
            ->where('user_id', $userId)
            ->get();

        if ($contentPhotos->isEmpty()) {
            return response()->json(['message' => 'Photo not found'], 404);
        }

        return response()->json([
            'photos' => $contentPhotos,
            'total' => $contentPhotos->count()
        ]);
    }

    public function getPhotoByCategory($slug)
    {
        $contentPhotos = CategoryContent::with([
            'contentPhoto.metadataPhoto',
            'contentPhoto.userComments',
            'contentPhoto.user',
            'category'
        ])
            ->whereHas('category', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })
            ->whereHas('contentPhoto', function ($query) {
                $query->where('status', 'approved'); // Ensure the PHOTO is approved
            })
            ->get();

        if ($contentPhotos->isEmpty()) {
            return response()->json(['message' => 'No approved photos found for this category'], 404);
        }

        return response()->json([
            'photos' => $contentPhotos,
        ]);
    }
}
