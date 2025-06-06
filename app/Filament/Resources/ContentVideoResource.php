<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContentVideoResource\Widgets\ContentVideoOverview;
use App\Filament\Resources\ContentVideoResource\Pages;
use App\Models\ContentVideo;
use App\Models\MetadataVideo;
use App\Models\Category;
use App\Notifications\VideoStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\Action;
use App\Filament\Filters\CategoryFilter;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\File;
use Plutuss\Facades\MediaAnalyzer;

class ContentVideoResource extends Resource
{
    protected static ?string $model = ContentVideo::class;
    protected static ?string $navigationIcon = 'heroicon-o-video-camera';
    protected static ?string $navigationGroup = 'Video Contents';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug'),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('tag')
                    ->maxLength(255),
                Forms\Components\TextInput::make('source')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\Select::make('categories')
                    ->relationship('categoryContents.category', 'category_name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('category_name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('category_description')
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('category_image')
                            ->image()
                            ->directory('category_images')
                            ->disk('public'),
                    ])
                    ->createOptionUsing(function (array $data) {
                        $category = Category::create([
                            'category_name' => $data['category_name'],
                            'slug' => $data['slug'],
                            'category_description' => $data['category_description'] ?? null,
                            'category_image' => $data['category_image'] ?? null,
                        ]);
                        return $category->id;
                    })
                    ->required(),
                Forms\Components\FileUpload::make('video_url')
                    ->directory('video_content')
                    ->disk('public')
                    ->nullable()
                    ->hint('Max file size: 20MB. Allowed formats: MP4, MOV, AVI, MKV.')
                    ->hintIcon('heroicon-o-information-circle')
                    ->hintColor('warning')
                    ->acceptedFileTypes([
                        'video/mp4',
                        'video/x-msvideo',
                        'video/quicktime',
                        'video/x-ms-wmv',
                        'video/x-flv',
                        'video/mpeg',
                        'video/x-m4v',
                        'video/webm',
                        'video/x-matroska',
                        'video/mkv',
                        'application/x-matroska',
                    ])
                    ->maxSize(20000)
                    ->getUploadedFileNameForStorageUsing(
                        function (TemporaryUploadedFile $file, callable $get): string {
                            $slug = $get('slug') ?? Str::slug($get('title'));
                            $extension = $file->getClientOriginalExtension();
                            return $slug . '_' . time() . '.' . $extension;
                        }
                    ),
                Forms\Components\TextInput::make('link_youtube')
                    ->nullable(),
                Forms\Components\FileUpload::make('thumbnail')
                    ->image()
                    ->hint('Max file size: 10MB. Allowed formats: JPG, JPEG, PNG, HEIC.')
                    ->hintIcon('heroicon-o-information-circle')
                    ->hintColor('warning')
                    ->directory('thumbnail_video')
                    ->disk('public')
                    ->maxSize(10000)
                    ->getUploadedFileNameForStorageUsing(
                        function (TemporaryUploadedFile $file, callable $get): string {
                            $slug = $get('slug') ?? Str::slug($get('title'));
                            $extension = $file->getClientOriginalExtension();
                            return $slug . '_thumbnail_' . time() . '.webp';
                        }
                    ),
                Forms\Components\Hidden::make('_video_file_for_metadata'),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(static::getTableColumns())
            ->headerActions(static::getTableHeaderActions())
            ->filters([
                CategoryFilter::make('category'),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions(static::getTableActions())
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected static function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('title')
                ->limit(length: 20)
                ->searchable(),
            Tables\Columns\TextColumn::make('user.name')
                ->sortable(),
            Tables\Columns\TextColumn::make('tag')
                ->searchable(),
            Tables\Columns\TextColumn::make('link_youtube')
                ->limit(30),
            Tables\Columns\TextColumn::make('categoryContents.category.category_name')
                ->searchable(),
            Tables\Columns\TextColumn::make('total_views')
                ->limit(length: 20)
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('popularity')
                ->label('Popularity')
                ->sortable(query: function (Builder $query, string $direction) {
                    return $query->withCount(['contentReactions', 'userComments', 'userFavorite'])
                        ->orderByRaw('(content_reactions_count * 1) + (user_comments_count * 1) + (total_views * 0.5) + (user_favorite_count * 1) ' . $direction);
                })
                ->getStateUsing(function (ContentVideo $record) {
                    return $record->calculatePopularity();
                }),
            BadgeColumn::make('status')
                ->state(function (ContentVideo $record): string {
                    return match ($record->status) {
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        default => $record->status,
                    };
                })
                ->colors([
                    'primary' => 'Pending',
                    'success' => 'Approved',
                    'danger' => 'Rejected',
                ])
                ->icons([
                    'heroicon-o-clock' => 'Pending',
                    'heroicon-o-check-circle' => 'Approved',
                    'heroicon-o-x-circle' => 'Rejected',
                ])
                ->description(fn(ContentVideo $record): string => $record->note ?? '')
                ->sortable(),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    protected static function getTableHeaderActions(): array
    {
        return [
            Action::make('bulkUpload')
                ->label('Bulk Upload Videos')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->form([
                    FileUpload::make('zip_file')
                        ->label('ZIP File')
                        ->acceptedFileTypes(['application/zip', 'application/x-zip-compressed'])
                        ->maxSize(512000)
                        ->required()
                        ->helperText('Upload a ZIP file containing metadata_template.xlsx, media/ folder with videos, and thumbnail/ folder.')
                        ->disk('local')
                        ->directory('bulk-uploads')
                        ->preserveFilenames()
                        ->columnSpanFull(),
                    Forms\Components\Placeholder::make('structure')
                        ->label('Required ZIP Structure')
                        ->content(view('filament.components.video-zip-structure'))
                        ->columnSpanFull(),
                ])
                ->action(function (array $data) {
                    return static::processBulkUploadVideo($data);
                })
                ->modalWidth('2xl'),
        ];
    }

    protected static function getTableActions(): array
    {
        return [
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
            Action::make('approve')
                ->label('Approve')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn(ContentVideo $record) => $record->status === 'pending')
                ->action(function (ContentVideo $record) {
                    $record->update([
                        'status' => 'approved',
                        'approved_at' => now(),
                    ]);
                    $record->user->notify(new VideoStatus('approved', $record->title, ''));
                    Notification::make()
                        ->title('Content Approved')
                        ->body('The content has been approved!')
                        ->success()
                        ->sendToDatabase(auth()->user())
                        ->send();
                }),
            Action::make('reject')
                ->label('Reject')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn(ContentVideo $record) => $record->status === 'pending')
                ->form([
                    Textarea::make('note')
                        ->label('Rejection Reason')
                        ->required()
                        ->placeholder('Enter the reason for rejection...')
                        ->columnSpanFull(),
                ])
                ->action(function (ContentVideo $record, array $data) {
                    $record->update([
                        'status' => 'rejected',
                        'note' => $data['note'],
                    ]);
                    $record->user->notify(new VideoStatus('rejected', $record->title, $data['note']));
                    Notification::make()
                        ->title('Content Rejected')
                        ->body('The content has been rejected.')
                        ->danger()
                        ->sendToDatabase(auth()->user())
                        ->send();
                }),
        ];
    }

    public static function processBulkUploadVideo(array $data)
    {
        $tempDir = null;

        try {
            $zipFile = $data['zip_file'];
            $userId = Auth::id();

            Log::info('Starting video bulk upload process', [
                'user_id' => $userId,
                'zip_file' => $zipFile
            ]);

            // Create temporary directory
            $tempDir = storage_path('app/temp/' . uniqid());
            if (!mkdir($tempDir, 0755, true)) {
                throw new \Exception('Failed to create temporary directory');
            }

            // Handle ZIP file extraction (same as photo upload)
            $zipPath = null;

            if (is_string($zipFile)) {
                try {
                    $tempFile = TemporaryUploadedFile::createFromLivewire($zipFile);
                    $zipPath = $tempFile->getRealPath();
                } catch (\Exception $e) {
                    Log::warning('Failed to get Livewire temp file: ' . $e->getMessage());
                }
            }

            if (!$zipPath || !file_exists($zipPath)) {
                $possiblePaths = [
                    storage_path('app/livewire-tmp/' . $zipFile),
                    Storage::disk('local')->path($zipFile),
                    storage_path('app/' . $zipFile),
                    $zipFile,
                ];

                foreach ($possiblePaths as $path) {
                    if (file_exists($path) && is_readable($path)) {
                        $zipPath = $path;
                        break;
                    }
                }
            }

            if (!$zipPath || !file_exists($zipPath)) {
                $scanDirs = [
                    storage_path('app/livewire-tmp'),
                    storage_path('app'),
                    storage_path('app/temp'),
                ];

                foreach ($scanDirs as $dir) {
                    if (is_dir($dir)) {
                        $files = glob($dir . '/*.zip');
                        if (!empty($files)) {
                            usort($files, function ($a, $b) {
                                return filemtime($b) - filemtime($a);
                            });
                            $zipPath = $files[0];
                            break;
                        }
                    }
                }
            }

            if (!$zipPath || !file_exists($zipPath)) {
                throw new \Exception('ZIP file not found. Please try uploading again.');
            }

            // Extract ZIP file
            $zip = new \ZipArchive();
            $result = $zip->open($zipPath);

            if ($result !== true) {
                throw new \Exception('Failed to open ZIP file (Error code: ' . $result . ')');
            }

            if (!$zip->extractTo($tempDir)) {
                $zip->close();
                throw new \Exception('Failed to extract ZIP file contents');
            }

            $zip->close();

            // Check required files and directories
            $possibleMetadataPaths = [
                $tempDir . '/metadata_template.xlsx',
                $tempDir . '/metadata.xlsx',
            ];

            $metadataPath = null;
            foreach ($possibleMetadataPaths as $path) {
                if (file_exists($path)) {
                    $metadataPath = $path;
                    break;
                }
            }

            if (!$metadataPath) {
                $files = static::listDirectoryContents($tempDir);
                throw new \Exception('metadata_template.xlsx not found. Found files: ' . implode(', ', array_map('basename', $files)));
            }

            $mediaDir = $tempDir . '/media';
            $thumbnailDir = $tempDir . '/thumbnail';

            Log::info('Checking directories', [
                'media_dir_exists' => is_dir($mediaDir),
                'thumbnail_dir_exists' => is_dir($thumbnailDir)
            ]);

            // Parse Excel file
            try {
                $rawData = Excel::toArray([], $metadataPath);
                $rows = $rawData[0];

                Log::info('Excel file parsed successfully', [
                    'row_count' => count($rows),
                    'first_few_rows' => array_slice($rows, 0, 3)
                ]);

                // Clean empty rows
                $cleanRows = [];
                foreach ($rows as $index => $row) {
                    $hasData = false;
                    foreach ($row as $cell) {
                        if (!empty(trim($cell ?? ''))) {
                            $hasData = true;
                            break;
                        }
                    }
                    if ($hasData) {
                        $cleanRows[$index] = $row;
                    }
                }
                $rows = $cleanRows;

            } catch (\Exception $e) {
                throw new \Exception('Failed to parse Excel file: ' . $e->getMessage());
            }

            if (empty($rows)) {
                throw new \Exception('Excel file appears to be empty or contains no valid data');
            }

            $processedCount = 0;
            $errors = [];
            $skippedRows = [];
            $videoRows = [];

            // Find header row
            $headerRowIndex = null;
            foreach ($rows as $index => $row) {
                $firstCell = strtolower(trim($row[0] ?? ''));
                if ($firstCell === 'type') {
                    $headerRowIndex = $index;
                    break;
                }
            }

            if ($headerRowIndex === null) {
                $headerRowIndex = array_key_first($rows);
            }

            foreach ($rows as $index => $row) {
                // Skip header row
                if ($index <= $headerRowIndex) {
                    continue;
                }

                // Skip empty rows
                $hasData = false;
                foreach ($row as $cell) {
                    if (!empty(trim($cell ?? ''))) {
                        $hasData = true;
                        break;
                    }
                }

                if (!$hasData) {
                    $skippedRows[] = "Row " . ($index + 1) . ": Empty row";
                    continue;
                }

                try {
                    // Map Excel columns (same order as template)
                    $type = strtolower(trim($row[0] ?? ''));
                    $fileName = trim($row[1] ?? ''); // Can be empty for YouTube videos
                    $title = trim($row[2] ?? '');
                    $source = trim($row[3] ?? '');
                    $alt_text = trim($row[4] ?? '');
                    $description = trim($row[5] ?? '');
                    $note = trim($row[6] ?? '');
                    $tag = trim($row[7] ?? '');
                    $categoriesStr = trim($row[8] ?? '');
                    $link_youtube = trim($row[9] ?? '');
                    $thumbnail_url = trim($row[10] ?? '');

                    Log::info("Processing video row $index", [
                        'type' => $type,
                        'fileName' => $fileName,
                        'title' => $title,
                        'link_youtube' => $link_youtube,
                        'thumbnail_url' => $thumbnail_url
                    ]);

                    // Only process videos
                    if ($type !== 'video') {
                        $skippedRows[] = "Row " . ($index + 1) . ": Not a video (type: '$type')";
                        continue;
                    }

                    $videoRows[] = $index + 1;

                    // Validate required fields
                    $missingFields = [];
                    if (empty($title))
                        $missingFields[] = 'title';
                    if (empty($source))
                        $missingFields[] = 'source';

                    // For videos, either video file or YouTube link is required
                    if (empty($fileName) && empty($link_youtube)) {
                        $missingFields[] = 'fileName or link_youtube';
                    }

                    if (!empty($missingFields)) {
                        $errors[] = "Row " . ($index + 1) . ": Missing required fields: " . implode(', ', $missingFields);
                        continue;
                    }

                    // Process categories
                    $categoryIds = [];
                    if (!empty($categoriesStr)) {
                        $categoryNames = array_map('trim', explode(',', $categoriesStr));
                        foreach ($categoryNames as $categoryName) {
                            if (empty($categoryName))
                                continue;

                            $category = Category::where('category_name', $categoryName)->first();
                            if ($category) {
                                $categoryIds[] = $category->id;
                            } else {
                                $errors[] = "Row " . ($index + 1) . ": Category not found - '$categoryName' (continuing without this category)";
                            }
                        }
                    }

                    // Handle video file (if provided)
                    // Handle video file (if provided)
                    $storedVideoPath = null;
                    $originalVideoPath = null; // Add this to store original path for metadata

                    if (!empty($fileName)) {
                        $mediaPath = $mediaDir . '/' . $fileName;
                        if (!file_exists($mediaPath)) {
                            $availableFiles = is_dir($mediaDir) ? glob($mediaDir . '/*') : [];
                            $errors[] = "Row " . ($index + 1) . ": Video file not found - '$fileName'. Available files: " . implode(', ', array_map('basename', $availableFiles));
                            continue;
                        }

                        // Store original path before moving file
                        $originalVideoPath = $mediaPath;

                        // Store video file
                        $slug = Str::slug($title);
                        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
                        $newVideoFileName = time() . '_' . $slug . '_' . $index . '.' . $extension;

                        $storedVideoPath = Storage::disk('public')->putFileAs(
                            'video_content',
                            new File($mediaPath),
                            $newVideoFileName
                        );

                        Log::info("Video file stored", [
                            'original_path' => $originalVideoPath,
                            'stored_path' => $storedVideoPath,
                            'new_filename' => $newVideoFileName
                        ]);
                    }


                    // Handle thumbnail (if provided)
                    $storedThumbnailPath = null;
                    if (!empty($thumbnail_url)) {
                        if (!is_dir($thumbnailDir)) {
                            $errors[] = "Row " . ($index + 1) . ": Thumbnail directory not found in ZIP";
                        } else {
                            $thumbnailPath = $thumbnailDir . '/' . $thumbnail_url;
                            if (!file_exists($thumbnailPath)) {
                                $availableThumbnails = glob($thumbnailDir . '/*');
                                $errors[] = "Row " . ($index + 1) . ": Thumbnail file not found - '$thumbnail_url'. Available files: " . implode(', ', array_map('basename', $availableThumbnails));
                            } else {
                                // Store thumbnail
                                $slug = Str::slug($title);
                                $thumbnailExtension = pathinfo($thumbnail_url, PATHINFO_EXTENSION);
                                $newThumbnailName = time() . '_' . $slug . '_thumbnail_' . $index . '.webp';

                                $storedThumbnailPath = Storage::disk('public')->putFileAs(
                                    'thumbnail_video',
                                    new File($thumbnailPath),
                                    $newThumbnailName
                                );
                            }
                        }
                    }

                    // Create ContentVideo
                    // Create ContentVideo
                    $slug = Str::slug($title);
                    $contentVideo = ContentVideo::create([
                        'user_id' => $userId,
                        'title' => $title,
                        'slug' => $slug,
                        'description' => $description,
                        'tag' => $tag,
                        'source' => $source,
                        'video_url' => $storedVideoPath,
                        'link_youtube' => $link_youtube ?: null,
                        'thumbnail' => $storedThumbnailPath,
                        'status' => 'pending',
                        'approved_at' => null,
                    ]);

                    Log::info("ContentVideo created", [
                        'video_id' => $contentVideo->id,
                        'has_video_file' => !empty($storedVideoPath),
                        'has_youtube_link' => !empty($link_youtube)
                    ]);

                    // Create video metadata if video file was uploaded
                    if ($storedVideoPath && $originalVideoPath) {
                        Log::info("Starting metadata extraction", [
                            'video_id' => $contentVideo->id,
                            'original_path' => $originalVideoPath,
                            'stored_path' => $storedVideoPath
                        ]);

                        static::extractAndSaveVideoMetadata($contentVideo, $originalVideoPath);
                    } else {
                        Log::info("Skipping metadata extraction", [
                            'video_id' => $contentVideo->id,
                            'reason' => 'No video file uploaded (YouTube link only)'
                        ]);
                    }

                    // Attach categories
                    if (!empty($categoryIds)) {
                        foreach ($categoryIds as $categoryId) {
                            $contentVideo->categoryContents()->create([
                                'category_id' => $categoryId
                            ]);
                        }
                    }

                    $processedCount++;
                    Log::info("Successfully processed video row $index", [
                        'content_video_id' => $contentVideo->id,
                        'processed_count' => $processedCount
                    ]);

                } catch (\Exception $e) {
                    $errors[] = "Row " . ($index + 1) . ": " . $e->getMessage();
                    Log::error('Error processing video row', [
                        'row' => $index + 1,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Clean up temp directory
            static::deleteDirectory($tempDir);
            $tempDir = null;

            // Show notifications
            if ($processedCount > 0) {
                Notification::make()
                    ->title("Video Bulk Upload Completed!")
                    ->body("Successfully processed $processedCount videos" .
                        (!empty($errors) ? " with " . count($errors) . " warnings." : "."))
                    ->success()
                    ->send();
            }

            if (!empty($errors)) {
                $errorMessage = "Warnings/Errors encountered:\n" .
                    implode("\n", array_slice($errors, 0, 5));

                if (count($errors) > 5) {
                    $errorMessage .= "\n... and " . (count($errors) - 5) . " more.";
                }

                Notification::make()
                    ->title("Upload Warnings")
                    ->body($errorMessage)
                    ->warning()
                    ->persistent()
                    ->send();
            }

            if ($processedCount === 0) {
                $message = "No videos were processed. ";
                if (!empty($videoRows)) {
                    $message .= "Found " . count($videoRows) . " video rows but encountered errors.";
                } else {
                    $message .= "No video rows found in Excel file.";
                }

                Notification::make()
                    ->title('No Videos Processed')
                    ->body($message)
                    ->warning()
                    ->persistent()
                    ->send();
            }

        } catch (\Exception $e) {
            Log::error('Video bulk upload error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($tempDir && is_dir($tempDir)) {
                static::deleteDirectory($tempDir);
            }

            Notification::make()
                ->title('Video Bulk Upload Failed')
                ->body($e->getMessage())
                ->danger()
                ->persistent()
                ->send();
        }
    }

    protected static function extractAndSaveVideoMetadata($contentVideo, $videoPath)
    {
        try {
            Log::info("Extracting video metadata for video ID: " . $contentVideo->id, [
                'video_path' => $videoPath,
                'video_url_in_db' => $contentVideo->video_url
            ]);

            // Validate that video file exists
            if (!file_exists($videoPath)) {
                Log::error("Video file not found for metadata extraction", [
                    'video_id' => $contentVideo->id,
                    'expected_path' => $videoPath
                ]);
                static::createBasicMetadata($contentVideo, null);
                return;
            }

            // Get basic file info
            $fileSize = filesize($videoPath);
            $mimeType = mime_content_type($videoPath);
            $extension = pathinfo($videoPath, PATHINFO_EXTENSION);

            Log::info("Video file validated", [
                'video_id' => $contentVideo->id,
                'file_size' => $fileSize,
                'mime_type' => $mimeType,
                'extension' => $extension
            ]);

            // Try to extract metadata using MediaAnalyzer with the same approach as controller
            try {
                Log::info("Attempting MediaAnalyzer extraction with File approach", ['video_id' => $contentVideo->id]);

                // Create a proper UploadedFile instance similar to controller
                // Copy file to a temporary location with proper name
                $tempPath = sys_get_temp_dir() . '/' . uniqid() . '_' . basename($videoPath);
                copy($videoPath, $tempPath);

                // Create UploadedFile instance like in controller
                $uploadedFile = new \Illuminate\Http\UploadedFile(
                    $tempPath,
                    basename($videoPath),
                    $mimeType,
                    null,
                    true // test mode
                );

                Log::info("Created UploadedFile instance", [
                    'video_id' => $contentVideo->id,
                    'temp_path' => $tempPath,
                    'file_size' => $uploadedFile->getSize(),
                    'mime_type' => $uploadedFile->getMimeType()
                ]);

                // Use MediaAnalyzer exactly like in controller
                $media = MediaAnalyzer::uploadFile($uploadedFile);

                // Extract metadata using controller logic
                $metadata = static::extractVideoMetadataFromAnalyzer($media, $uploadedFile);

                // Clean up temp file
                if (file_exists($tempPath)) {
                    unlink($tempPath);
                }

                Log::info("MediaAnalyzer extraction successful", [
                    'video_id' => $contentVideo->id,
                    'metadata' => $metadata
                ]);

                // Create metadata record
                MetadataVideo::create(array_merge([
                    'content_video_id' => $contentVideo->id,
                    'collection_date' => now(),
                    'location' => null,
                ], $metadata));

                Log::info("Video metadata saved successfully via MediaAnalyzer", [
                    'video_id' => $contentVideo->id
                ]);

            } catch (\Exception $mediaAnalyzerError) {
                Log::warning("MediaAnalyzer failed, creating basic metadata", [
                    'video_id' => $contentVideo->id,
                    'error' => $mediaAnalyzerError->getMessage()
                ]);

                // Clean up temp file if it exists
                if (isset($tempPath) && file_exists($tempPath)) {
                    unlink($tempPath);
                }

                // Fallback to basic file information
                static::createBasicMetadata($contentVideo, $fileSize, $extension);
            }

        } catch (\Exception $e) {
            Log::error('Critical error in metadata extraction', [
                'video_id' => $contentVideo->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Last resort - create minimal metadata
            static::createBasicMetadata($contentVideo, null);
        }
    }

    protected static function extractVideoMetadataFromAnalyzer($media, $uploadedFile)
    {
        // Extract using same logic as controller
        $fileSize = $uploadedFile->getSize();

        // Extract codec information
        $codec = null;
        if ($media->getNestedValue('video.codec')) {
            $codec = 'Video: ' . $media->getNestedValue('video.codec');

            // Add audio codec if available
            if ($media->getNestedValue('audio.codec')) {
                $codec .= ', Audio: ' . $media->getNestedValue('audio.codec');
            }
        }

        // Extract resolution
        $resolution = null;
        if ($media->getNestedValue('video.resolution_x') && $media->getNestedValue('video.resolution_y')) {
            $resolution = $media->getNestedValue('video.resolution_x') . 'x' . $media->getNestedValue('video.resolution_y');
        } elseif ($media->getResolution()) {
            $resolution = $media->getResolution();
        }

        // Extract frame rate (ensure it's a numeric value before rounding)
        $frameRate = $media->getNestedValue('video.frame_rate');
        if ($frameRate && is_numeric($frameRate)) {
            $frameRate = round($frameRate, 2);
        }

        // Extract duration (ensure it's a numeric value before rounding)
        $duration = null;
        if (isset($media->playtime_seconds) && is_numeric($media->playtime_seconds)) {
            $duration = round($media->playtime_seconds, 2);
        }

        return [
            'file_size' => $fileSize,
            'format_file' => $media->getFileFormat(),
            'duration' => $duration,
            'codec_video_audio' => $codec,
            'resolution' => $resolution,
            'frame_rate' => $frameRate,
        ];
    }

    protected static function createBasicMetadata($contentVideo, $fileSize = null, $extension = null)
    {
        try {
            // If no file size provided, try to get it from stored file
            if ($fileSize === null && $contentVideo->video_url) {
                try {
                    $storedPath = Storage::disk('public')->path($contentVideo->video_url);
                    if (file_exists($storedPath)) {
                        $fileSize = filesize($storedPath);
                        $extension = $extension ?: pathinfo($storedPath, PATHINFO_EXTENSION);
                    }
                } catch (\Exception $e) {
                    Log::warning("Could not get file size from stored file", [
                        'video_id' => $contentVideo->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            MetadataVideo::create([
                'content_video_id' => $contentVideo->id,
                'file_size' => $fileSize,
                'format_file' => $extension ?: 'unknown',
                'duration' => null,
                'codec_video_audio' => null,
                'resolution' => null,
                'frame_rate' => null,
                'location' => null,
                'collection_date' => now(),
            ]);

            Log::info("Basic metadata created successfully", [
                'video_id' => $contentVideo->id,
                'file_size' => $fileSize
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to create even basic metadata', [
                'video_id' => $contentVideo->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    protected static function listDirectoryContents(string $directory): array
    {
        $files = [];
        if (is_dir($directory)) {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );

            foreach ($iterator as $file) {
                $files[] = $file->getPathname();
            }
        }
        return $files;
    }

    protected static function deleteDirectory($dir): bool
    {
        if (!file_exists($dir))
            return true;
        if (!is_dir($dir))
            return unlink($dir);

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..')
                continue;
            if (!static::deleteDirectory($dir . DIRECTORY_SEPARATOR . $item))
                return false;
        }

        return rmdir($dir);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getWidgets(): array
    {
        return [
            ContentVideoOverview::class,
        ];
    }

    public static function getHourlyUploadedContent()
    {
        $newContent = ContentVideo::whereBetween('created_at', [now()->subHour(), now()])->count();
        if ($newContent > 0) {
            Notification::make()
                ->title('New Content Uploaded')
                ->body("There are {$newContent} new content video uploaded in the last hour.")
                ->sendToDatabase(auth()->user())
                ->send();
        }
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContentVideos::route('/'),
            'create' => Pages\CreateContentVideo::route('/create'),
            'edit' => Pages\EditContentVideo::route('/{record}/edit'),
        ];
    }

    public static function handleCategoryRelationships(ContentVideo $record, array $data): void
    {
        if (isset($data['categories'])) {
            $record->categoryContents()->delete();
            foreach ($data['categories'] as $categoryId) {
                $record->categoryContents()->create([
                    'category_id' => $categoryId,
                    'content_video_id' => $record->id
                ]);
            }
        }
    }
}