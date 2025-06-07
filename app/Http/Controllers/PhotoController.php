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
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Excel;
use Illuminate\Support\Facades\File;
use App\Models\Category;
use App\Models\ContentVideo;
use Illuminate\Support\Facades\Log;
use App\Imports\PhotosImport;

class PhotoController extends Controller
{
    public function index()
    {
        $contentPhoto = ContentPhoto::with(['metadataPhoto', 'user', 'categoryContents', 'contentReactions'])
            ->where('status', 'approved')
            ->get()
            ->map(function ($photo) {
                $photo->likes_count = $photo->contentReactions->count();
                return $photo;
            });

        if ($contentPhoto->isEmpty()) {
            return response()->json(['message' => 'Photo not found'], 404);
        }

        return response()->json($contentPhoto);
    }

    public function getPopularPhotos()
    {
        $popularPhotos = ContentPhoto::with([
            'metadataPhoto',
            'user',
            'categoryContents',
            'contentReactions'
        ])
            ->where('status', 'approved')
            ->withCount([
                'contentReactions',
                'userComments',
                'userFavorite'
            ])
            ->orderByRaw('(content_reactions_count * 1) + (user_comments_count * 1) + (total_views * 0.5) + (user_favorite_count * 1) DESC')
            ->take(3)
            ->get()
            ->map(function ($photo) {
                $photo->likes_count = $photo->contentReactions->count();
                return $photo;
            });

        if ($popularPhotos->isEmpty()) {
            return response()->json(['message' => 'No popular photos found'], 404);
        }

        return response()->json($popularPhotos);
    }

    public function show($slug)
    {
        $contentPhoto = ContentPhoto::with(['metadataPhoto', 'user', 'categoryContents', 'contentReactions', 'userComments'])
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
            'exif_data' => 'nullable|array', // Accept pre-extracted EXIF data
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
            // Handle EXIF metadata
            $exifData = $request->input('exif_data');

            if ($exifData) {
                // Use pre-extracted EXIF data from bulk upload
                MetadataPhoto::create([
                    'content_photo_id' => $photo->id,
                    'collection_date' => !empty($exifData['collection_date']) ? $exifData['collection_date'] : null,
                    'file_size' => $exifData['file_size'],
                    'aperture' => $exifData['aperture'],
                    'location' => $exifData['location'],
                    'model' => $exifData['model'],
                    'ISO' => $exifData['ISO'],
                    'dimensions' => $exifData['dimensions'],
                ]);
            } else {
                // Extract EXIF from uploaded file (regular upload)
                try {
                    $exif = exif_read_data($file->getPathname());
                    $image = Image::make($file->getPathname());
                    $dimensions = $image->width() . 'x' . $image->height();

                    MetadataPhoto::create([
                        'content_photo_id' => $photo->id,
                        'collection_date' => !empty($exif['DateTimeOriginal']) ?
                            date('Y-m-d H:i:s', strtotime($exif['DateTimeOriginal'])) : null,
                        'file_size' => $file->getSize(),
                        'aperture' => isset($exif['COMPUTED']['ApertureFNumber']) ?
                            $exif['COMPUTED']['ApertureFNumber'] : null,
                        'location' => isset($exif['GPSLatitude']) ?
                            $this->getGPSCoordinates($exif) : null,
                        'model' => isset($exif['Model']) ? $exif['Model'] : null,
                        'ISO' => isset($exif['ISOSpeedRatings']) ?
                            $exif['ISOSpeedRatings'] : null,
                        'dimensions' => $dimensions,
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Error extracting EXIF data: ' . $e->getMessage());
                }
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
        $contentPhotos = ContentPhoto::with(['metadataPhoto', 'user', 'contentReactions'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($photo) {
                // Add likes count to each photo
                $photo->likes_count = $photo->contentReactions->count();
                return $photo;
            });

        $totalLikes = $contentPhotos->sum('likes_count');

        return response()->json([
            'photos' => $contentPhotos,
            'total' => $contentPhotos->count(),
            'total_likes' => $totalLikes
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
                $query->where('status', 'approved');
            })
            ->get()
            ->map(function ($categoryContent) {
                if ($categoryContent->contentPhoto) {
                    $categoryContent->contentPhoto->likes_count = $categoryContent->contentPhoto->contentReactions->count();
                }
                return $categoryContent;
            });

        if ($contentPhotos->isEmpty()) {
            return response()->json(['message' => 'No approved photos found for this category'], 404);
        }

        return response()->json([
            'photos' => $contentPhotos
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

    public function bulkUpload(Request $request)
    {
        $request->validate([
            'zip_file' => 'required|file|mimes:zip|max:102400', // Max 100MB
        ]);
        $userId = Auth::user()->id;

        try {
            // Create temporary directory with unique name
            $tempDir = storage_path('app/temp/' . uniqid());
            mkdir($tempDir, 0755, true);

            // Extract ZIP file
            $zipFile = $request->file('zip_file');
            $zipPath = $zipFile->getPathname();

            // Try ZipArchive first, fallback to alternative method if not available
            if (class_exists('ZipArchive')) {
                $zip = new \ZipArchive();
                if ($zip->open($zipPath) !== true) {
                    throw new \Exception('Failed to open ZIP file');
                }
                $zip->extractTo($tempDir);
                $zip->close();
            } else {
                // Alternative method using system commands
                $command = "powershell -command \"Expand-Archive -Path '{$zipPath}' -DestinationPath '{$tempDir}' -Force\"";
                exec($command, $output, $returnVar);
                if ($returnVar !== 0) {
                    throw new \Exception('Failed to extract ZIP file using alternative method');
                }
            }

            // Check if metadata.xlsx exists
            $metadataPath = $tempDir . '/metadata_template.xlsx';
            if (!file_exists($metadataPath)) {
                throw new \Exception('metadata_template.xlsx not found in ZIP file');
            }

            // Check if media directory exists
            $mediaDir = $tempDir . '/media';
            if (!is_dir($mediaDir)) {
                throw new \Exception('media directory not found in ZIP file');
            }

            // Parse Excel file
            $rows = \Maatwebsite\Excel\Facades\Excel::toArray([], $metadataPath)[0];

            $parsedItems = [];
            $tempFiles = [];

            // Process each row
            foreach ($rows as $index => $row) {
                if ($index == 0 || !isset($row[0]))
                    continue; // Skip header row

                $type = strtolower(trim($row[0])); // photo / video
                $fileName = trim($row[1]);
                $title = trim($row[2]);
                $source = trim($row[3]);
                $alt_text = trim($row[4]);
                $description = trim($row[5]);
                $note = trim($row[6]);
                $tag = trim($row[7]);
                $categories = explode(',', trim($row[8]));
                $link_youtube = trim($row[9]);
                $thumbnail_url = trim($row[10]);

                $slug = Str::slug($title);
                $extension = pathinfo($fileName, PATHINFO_EXTENSION);
                $newFileName = time() . '_' . $slug . '.' . $extension;

                $item = [
                    'type' => $type,
                    'title' => $title,
                    'slug' => $slug,
                    'description' => $description,
                    'note' => $note,
                    'tag' => $tag,
                    'source' => $source,
                    'alt_text' => $alt_text,
                    'categories' => $categories,
                    'link_youtube' => $link_youtube,
                    'media_url' => null,
                    'thumbnail_url' => null,
                ];

                // Handle media file
                if ($type === 'photo') {
                    // For photos, file is required
                    $mediaPath = $mediaDir . '/' . $fileName;
                    if (!file_exists($mediaPath)) {
                        throw new \Exception("File tidak ditemukan: $fileName - pastikan nama file yang ditulis sama dengan nama file yang di upload");
                    }

                    try {
                        $image = Image::make($mediaPath);
                        $dimensions = $image->width() . 'x' . $image->height();

                        // Only try to extract EXIF for JPEG/JPG files
                        $exif = null;
                        if (in_array(strtolower(pathinfo($mediaPath, PATHINFO_EXTENSION)), ['jpg', 'jpeg'])) {
                            $exif = @exif_read_data($mediaPath);
                        }

                        $item['exif_data'] = [
                            'collection_date' => !empty($exif['DateTimeOriginal']) ?
                                date('Y-m-d H:i:s', strtotime($exif['DateTimeOriginal'])) : null,
                            'file_size' => filesize($mediaPath),
                            'aperture' => isset($exif['COMPUTED']['ApertureFNumber']) ?
                                $exif['COMPUTED']['ApertureFNumber'] : null,
                            'location' => isset($exif['GPSLatitude']) ?
                                $this->getGPSCoordinates($exif) : null,
                            'model' => isset($exif['Model']) ? $exif['Model'] : null,
                            'ISO' => isset($exif['ISOSpeedRatings']) ?
                                $exif['ISOSpeedRatings'] : null,
                            'dimensions' => $dimensions,
                        ];
                    } catch (\Exception $e) {
                        \Log::warning('Error processing image ' . $fileName . ': ' . $e->getMessage());
                        // Set default values for unsupported formats
                        $item['exif_data'] = [
                            'collection_date' => null,
                            'file_size' => filesize($mediaPath),
                            'aperture' => null,
                            'location' => null,
                            'model' => null,
                            'ISO' => null,
                            'dimensions' => $dimensions ?? 'unknown',
                        ];
                    }

                    // Create temporary file in storage
                    $tempStoragePath = 'temp/' . uniqid() . '/' . $newFileName;
                    Storage::disk('public')->putFileAs(
                        dirname($tempStoragePath),
                        new \Illuminate\Http\File($mediaPath),
                        basename($tempStoragePath)
                    );
                    $item['media_url'] = $tempStoragePath;
                    $tempFiles[] = $tempStoragePath;
                } elseif ($type === 'video') {
                    // For videos, file is only required if no YouTube link is provided
                    if (empty($link_youtube)) {
                        $mediaPath = $mediaDir . '/' . $fileName;
                        if (!file_exists($mediaPath)) {
                            throw new \Exception("File tidak ditemukan: $fileName - pastikan nama file yang ditulis sama dengan nama file yang di upload");
                        }

                        // Create temporary file in storage
                        $tempStoragePath = 'temp/' . uniqid() . '/' . $newFileName;
                        Storage::disk('public')->putFileAs(
                            dirname($tempStoragePath),
                            new \Illuminate\Http\File($mediaPath),
                            basename($tempStoragePath)
                        );
                        $item['media_url'] = $tempStoragePath;
                        $tempFiles[] = $tempStoragePath;
                    }
                }

                // Handle thumbnail for videos
                if ($type === 'video' && !empty($thumbnail_url)) {
                    $thumbnailDir = $tempDir . '/thumbnail';
                    if (!is_dir($thumbnailDir)) {
                        throw new \Exception('thumbnail directory not found in ZIP file');
                    }
                    $thumbnailPath = $thumbnailDir . '/' . $thumbnail_url;
                    if (!file_exists($thumbnailPath)) {
                        \Log::warning("File not found: $thumbnail_url");
                        throw new \Exception("File tidak ditemukan:" . $thumbnail_url . "pastikan nama file yang ditulis sama dengan nama file yang di upload");
                    }

                    $thumbnailNewName = time() . '_' . $slug . '.jpg';
                    $tempThumbnailPath = 'temp/' . uniqid() . '/' . $thumbnailNewName;
                    Storage::disk('public')->putFileAs(
                        dirname($tempThumbnailPath),
                        new \Illuminate\Http\File($thumbnailPath),
                        basename($tempThumbnailPath)
                    );
                    $item['thumbnail_url'] = $tempThumbnailPath;
                }

                $parsedItems[] = $item;
                // Only add to tempFiles if media_url exists
                if ($item['media_url']) {
                    $tempFiles[] = $item['media_url'];
                }
                if ($item['thumbnail_url']) {
                    $tempFiles[] = $item['thumbnail_url'];
                }
            }

            // Clean up original temp directory
            $this->deleteDirectory($tempDir);

            return response()->json([
                'message' => 'Files parsed successfully',
                'items' => $parsedItems,
                'temp_files' => $tempFiles
            ]);

        } catch (\Exception $e) {
            // Clean up temporary directory if it exists
            if (isset($tempDir) && is_dir($tempDir)) {
                $this->deleteDirectory($tempDir);
            }

            return response()->json([
                'message' => 'Bulk upload failed: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function deleteDirectory($dir)
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }
        rmdir($dir);
    }
}
