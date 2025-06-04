<?php

namespace App\Http\Controllers;

use App\Models\CategoryContent;
use App\Models\ContentPhoto;
use App\Models\UserFavorite;
use App\Models\MetadataPhoto;
use App\Models\ContentReaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Auth;
use App\Imports\PhotosImport;
use Maatwebsite\Excel\Facades\Excel;

class PhotoController extends Controller
{
    public function index()
    {
        $contentPhoto = ContentPhoto::with(['metadataPhoto', 'userComments', 'userComments.userReactions', 'user', 'categoryContents', 'contentReactions', 'contentReactions.reactionType', 'userFavorite'])
            ->where('status', 'approved')
            ->get();
        if (!$contentPhoto) {
            return response()->json(['message' => 'Photo not found'], 404);
        }
        //  popularity

        return response()->json($contentPhoto);
    }

    public function getPopularPhotos()
    {
        $popularPhotos = ContentPhoto::with([
            'metadataPhoto',
            'userComments',
            'user',
            'categoryContents',
            'contentReactions',
            'contentReactions.reactionType',
            'userFavorite'
        ])
            ->where('status', 'approved')
            ->withCount([
                'contentReactions',
                'userComments',
                'userFavorite'
            ])
            ->orderByRaw('(content_reactions_count * 1) + (user_comments_count * 1) + (total_views * 0.5) + (user_favorite_count * 1) DESC')
            ->take(3)
            ->get();

        if ($popularPhotos->isEmpty()) {
            return response()->json(['message' => 'No popular photos found'], 404);
        }

        return response()->json($popularPhotos);
    }


    public function show($slug)
    {
        $contentPhoto = ContentPhoto::with(['metadataPhoto', 'userComments', 'user', 'categoryContents', 'contentReactions', 'contentReactions.reactionType', 'userFavorite'])
            ->where('status', 'approved')
            ->where('slug', $slug)
            ->first();
        if (!$contentPhoto) {
            return response()->json(['message' => 'Photo not found'], 404);
        }
        $contentReactions = ContentReaction::where('content_photo_id', $contentPhoto->id)
            ->with('reactionType')
            ->count();
        $contentPhoto->updateTotalViews();
        return response()->json([
            'photo' => $contentPhoto,
            'total_reactions' => $contentReactions
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:10485760',
            'source' => 'nullable|string|max:255',
            'alt_text' => 'nullable|string|max:255',
            'note' => 'nullable|string',
            'tag' => 'nullable|string|max:255',
            'category_ids' => 'required|array',
            'category_ids.*' => 'required|exists:categories,id',
        ]);

        $userId = Auth::user()->id;
        if (!$userId) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        // Handle file upload
        if ($request->hasFile('image')) {
            $slug = Str::slug($request->title);
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '_' . $slug . '.' . $extension;

            $path = $file->storeAs('foto_content', $filename, 'public');

            // Save only the relative path (without 'public/') to DB
            $imageUrl = 'foto_content/' . $filename;


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

            // Create category content associations for each selected category
            foreach ($request->category_ids as $categoryId) {
                CategoryContent::create([
                    'category_id' => $categoryId,
                    'content_photo_id' => $photo->id,
                    'content_video_id' => null
                ]);
            }

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
                'data' => $photo->load(['metadataPhoto', 'categoryContents'])
            ], 201);
        }

        return response()->json([
            'message' => 'No image file provided'
        ], 400);
    }

    // update photo
    public function updatePhotoByUser(Request $request, $id)
    {
        \Log::info('Updating photo with ID: ' . $id);
        \Log::info('Request data:', $request->all());

        $photo = ContentPhoto::find($id);
        if (!$photo) {
            \Log::error('Photo not found with ID: ' . $id);
            return response()->json(['message' => 'Photo not found'], 404);
        }

        $status = $photo->status;
        if ($status == 'rejected') {
            $photo->status = 'pending';
            $photo->save();
        }

        try {
            $validatedData = $request->validate([
                'title' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'source' => 'nullable|string|max:255',
                'alt_text' => 'nullable|string|max:255',
                'note' => 'nullable|string',
                'tag' => 'nullable|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'metadata.collection_date' => 'nullable|date',
                'metadata.model' => 'nullable|string',
                'metadata.ISO' => 'nullable|string',
                'metadata.aperture' => 'nullable|string',
                'metadata.location' => 'nullable|string',
                'metadata.dimensions' => 'nullable|string',
            ]);

            \Log::info('Validated data:', $validatedData);

            $slug = Str::slug($validatedData['title']);

            $updateData = [
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'source' => $validatedData['source'],
                'alt_text' => $validatedData['alt_text'],
                'note' => $validatedData['note'],
                'tag' => $validatedData['tag'],
                'slug' => $slug,
            ];

            // Only update image if new one is provided
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '_' . $slug . '.' . $extension;
                $path = $file->storeAs('foto_content', $filename, 'public');
                $updateData['image_url'] = 'foto_content/' . $filename;
            }

            $photo->update($updateData);

            // Update category if provided
            if ($request->has('category_id')) {
                $photo->categoryContents()->updateOrCreate(
                    ['content_photo_id' => $photo->id],
                    ['category_id' => $request->category_id]
                );
            }

                    // Update metadata
            if ($request->has('metadata')) {
                $metadata = $photo->metadataPhoto()->updateOrCreate(
                    ['content_photo_id' => $photo->id],
                    [
                        'collection_date' => $request->input('metadata.collection_date'),
                        'model' => $request->input('metadata.model'),
                        'ISO' => $request->input('metadata.ISO'),
                        'aperture' => $request->input('metadata.aperture'),
                        'location' => $request->input('metadata.location'),
                        'dimensions' => $request->input('metadata.dimensions'),
                    ]
                );
            }

            return response()->json([
                'message' => 'Photo and metadata updated successfully',
                'data' => $photo->load('metadataPhoto')
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed:', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            throw $e;
        }
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
        $contentPhotos = ContentPhoto::with(['metadataPhoto', 'userComments', 'user', 'categoryContents', 'contentReactions', 'contentReactions.reactionType', 'userFavorite'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();


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
            'category',
            'contentPhoto.contentReactions',
            'contentPhoto.contentReactions.reactionType',
            'contentPhoto.userFavorite'
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

    public function edit($id)
    {
        $photo = ContentPhoto::with(['metadataPhoto', 'categoryContents'])
            ->findOrFail($id);
        return response()->json($photo);
    }

    public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls|max:2048'
    ]);

    try {
        // Get the authenticated user's ID
        $userId = Auth::id();
        
        // Pass the user ID to the importer
        $import = new PhotosImport($userId);

        Excel::import($import, $request->file('file'));
        
        $importedCount = $import->getRowCount();
        $skippedCount = $import->getSkippedCount();
        
        return response()->json([
            'success' => true,
            'message' => "Berhasil mengimpor {$importedCount} foto",
            'skipped' => $skippedCount,
            'total' => $importedCount + $skippedCount
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengimpor: ' . $e->getMessage()
        ], 422);
    }
}
}
