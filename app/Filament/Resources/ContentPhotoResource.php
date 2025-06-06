<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContentPhotoResource\Pages;
use App\Filament\Resources\ContentPhotoResource\RelationManagers;
use App\Models\ContentPhoto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Filters\CategoryFilter;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\Action;
use App\Models\UserComment;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use App\Filament\Resources\ContentPhotoResource\Widgets\ContentPhotoOverview;
use App\Notifications\PhotoStatus;
use Illuminate\Http\UploadedFile;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Intervention\Image\ImageManagerStatic as Image;
use Filament\Forms\Components\FileUpload;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use App\Models\Category;
use App\Models\MetadataPhoto;






class ContentPhotoResource extends Resource
{
    protected static ?string $model = ContentPhoto::class;

    protected static ?string $navigationIcon = 'heroicon-o-camera';
    protected static ?string $navigationGroup = 'Photo Contents';


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
                Forms\Components\FileUpload::make('image_url')
                    ->image()
                    ->directory('foto_content')
                    ->hint('Max file size: 10MB. Allowed formats: JPG, JPEG, PNG, HEIC.')
                    ->hintIcon('heroicon-o-information-circle')
                    ->hintColor('warning')
                    ->disk('public')
                    ->maxSize(10000)
                    ->storeFileNamesIn('original_filename')
                    ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png', 'image/heic'])
                    ->getUploadedFileNameForStorageUsing(
                        function (TemporaryUploadedFile $file, callable $get): string {
                            $slug = $get('slug') ?? Str::slug($get('title'));
                            $extension = $file->getClientOriginalExtension();
                            return $slug . '_' . time() . '.' . $extension;
                        }
                    )
                    ->afterStateUpdated(function (callable $set, $state) {
                        if ($state) {
                            // Only extract EXIF for new uploads
                            if ($state instanceof TemporaryUploadedFile) {
                                $exifData = static::extractExifData($state);
                                $set('exif_data', $exifData);
                            }
                        }
                    }),

                Forms\Components\Hidden::make('exif_data'),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('source')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('alt_text')
                    ->maxLength(255),
                Forms\Components\TextInput::make('tag')
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state === 'approved') {
                            $set('approved_at', now()->toDateTimeString());
                        } else {
                            $set('approved_at', null);
                        }
                    }),
                Forms\Components\DateTimePicker::make('approved_at')
                    ->hidden()
                    ->dehydrated(true),
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
            Tables\Columns\TextColumn::make('categoryContents.category.category_name')
                ->searchable(),
            Tables\Columns\TextColumn::make('tag')
                ->searchable(),
            Tables\Columns\TextColumn::make('total_views')
                ->limit(length: 20)
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('popularity')
                ->label('Popularity')
                ->sortable(query: function (Builder $query, string $direction) {
                    return $query->withCount(['contentReactions', 'userComments'])
                        ->orderByRaw('(content_reactions_count * 1) + (user_comments_count * 2) + (total_views * 0.5) ' . $direction);
                })
                ->getStateUsing(function (ContentPhoto $record) {
                    return $record->calculatePopularity();
                })
                ->searchable()
                ->limit(20),
            BadgeColumn::make('status')
                ->state(function (ContentPhoto $record): string {
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
                ->description(fn(ContentPhoto $record): string => $record->note ?? '')
                ->sortable(),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('approved_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    protected static function getTableHeaderActions(): array
    {
        return [
            Action::make('bulkUpload')
                ->label('Bulk Upload')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->form([
                    FileUpload::make('zip_file')
                        ->label('ZIP File')
                        ->acceptedFileTypes(['application/zip', 'application/x-zip-compressed'])
                        ->maxSize(102400)
                        ->required()
                        ->helperText('Upload a ZIP file containing metadata_template.xlsx and media/ folder with photos.')
                        ->disk('local')
                        ->directory('bulk-uploads')
                        ->preserveFilenames()
                        ->columnSpanFull(),

                    Forms\Components\Placeholder::make('structure')
                        ->label('Required ZIP Structure')
                        ->content(view('filament.components.zip-structure'))
                        ->columnSpanFull(),
                ])
                ->action(function (array $data) {
                    return static::processBulkUpload($data);
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
                ->visible(fn(ContentPhoto $record) => $record->status === 'pending')
                ->action(function (ContentPhoto $record) {
                    $record->update([
                        'status' => 'approved',
                        'approved_at' => now(),
                    ]);

                    $record->user->notify(new PhotoStatus('approved', $record->title, ''));

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
                ->visible(fn(ContentPhoto $record) => $record->status === 'pending')
                ->form([
                    Textarea::make('note')
                        ->label('Rejection Reason')
                        ->required()
                        ->placeholder('Enter the reason for rejection...')
                        ->columnSpanFull(),
                ])
                ->action(function (ContentPhoto $record, array $data) {
                    $record->update([
                        'status' => 'rejected',
                        'note' => $data['note'],
                    ]);

                    $record->user->notify(new PhotoStatus('rejected', $record->title, $data['note']));

                    Notification::make()
                        ->title('Content Rejected')
                        ->body('The content has been rejected.')
                        ->danger()
                        ->sendToDatabase(auth()->user())
                        ->send();
                }),
        ];
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['contentReactions', 'userComments', 'userFavorite'])
            ->when(request()->has('sort'), function (Builder $query) {
                if (request('sort') === 'popularity') {
                    $query->orderByRaw('
                        (SELECT COUNT(*) FROM content_reactions WHERE content_reactions.content_photo_id = content_photo.id) * 1 +
                        (SELECT COUNT(*) FROM user_comments WHERE user_comments.content_photo_id = content_photo.id) * 1 +
                        (content_photo.total_views) * 0.5 + (SELECT COUNT(*) FROM user_favorites WHERE user_favorites.content_photo_id = content_photo.id) * 1
                    ' . (request('direction') === 'desc' ? 'DESC' : 'ASC'));
                }
            })
            ->when(request()->has('search'), function (Builder $query) {
                $search = request('search');
                $query->whereRaw('
                    (SELECT COUNT(*) FROM content_reactions WHERE content_reactions.content_photo_id = content_photo.id) * 1 +
                    (SELECT COUNT(*) FROM user_comments WHERE user_comments.content_photo_id = content_photo.id) * 1 +
                    (content_photo.total_views) * 0.5 + (SELECT COUNT(*) FROM user_favorites WHERE user_favorites.content_photo_id = content_photo.id) * 1
                    LIKE ?',
                    ["%{$search}%"]
                );
            });
    }

    public static function query(Builder $query): Builder
    {
        return $query->withCount('userComments', 'contentReactions');
    }

    public static function getWidgets(): array
    {
        return [
            ContentPhotoOverview::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContentPhotos::route('/'),
            'create' => Pages\CreateContentPhoto::route('/create'),
            'edit' => Pages\EditContentPhoto::route('/{record}/edit'),
        ];
    }

    public static function getHourlyUploadedContent()
    {
        $newContent = ContentPhoto::whereBetween('created_at', [now()->subHour(), now()])->count();
        if ($newContent > 0) {
            Notification::make()
                ->title('New Content Uploaded')
                ->body("There are {$newContent} new content photo uploaded in the last hour.")
                ->sendToDatabase(auth()->user())
                ->send();
        }
    }

    public static function handleCategoryRelationships(ContentPhoto $record, array $data): void
    {
        if (isset($data['categories'])) {
            // Delete existing category relationships
            $record->categoryContents()->delete();

            // Create new category relationships
            foreach ($data['categories'] as $categoryId) {
                $record->categoryContents()->create([
                    'category_id' => $categoryId,
                    'content_photo_id' => $record->id
                ]);
            }
        }
    }

    public static function processBulkUpload(array $data)
    {
        $tempDir = null;

        try {
            $zipFile = $data['zip_file'];
            $userId = Auth::id();

            Log::info('Starting bulk upload process', [
                'user_id' => $userId,
                'zip_file' => $zipFile
            ]);

            // Create temporary directory
            $tempDir = storage_path('app/temp/' . uniqid());
            if (!mkdir($tempDir, 0755, true)) {
                throw new \Exception('Failed to create temporary directory');
            }

            // [Previous ZIP handling code remains the same...]

            // Handle Livewire temporary file
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

            // Process ZIP file
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

            // Check required files
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
            if (!is_dir($mediaDir)) {
                $dirs = glob($tempDir . '/*', GLOB_ONLYDIR);
                throw new \Exception('media directory not found. Available directories: ' . implode(', ', array_map('basename', $dirs)));
            }

            // Parse Excel file with better error handling
            try {
                $rawData = Excel::toArray([], $metadataPath);
                $rows = $rawData[0]; // Get first sheet

                Log::info('Excel file parsed successfully', [
                    'sheet_count' => count($rawData),
                    'row_count' => count($rows),
                    'raw_first_rows' => array_slice($rows, 0, 5) // First 5 rows for debugging
                ]);

                // Clean up the data - remove completely empty rows
                $cleanRows = [];
                foreach ($rows as $index => $row) {
                    // Check if row has any non-empty values
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

                Log::info('Cleaned Excel data', [
                    'clean_row_count' => count($rows),
                    'clean_rows' => $rows
                ]);

            } catch (\Exception $e) {
                throw new \Exception('Failed to parse Excel file: ' . $e->getMessage());
            }

            if (empty($rows)) {
                throw new \Exception('Excel file appears to be empty or contains no valid data');
            }

            $processedCount = 0;
            $errors = [];
            $skippedRows = [];
            $photoRows = [];

            // Find header row and data rows
            $headerRowIndex = null;
            $dataStartIndex = null;

            // Look for header row (should contain "type" in first column)
            foreach ($rows as $index => $row) {
                $firstCell = strtolower(trim($row[0] ?? ''));
                if ($firstCell === 'type') {
                    $headerRowIndex = $index;
                    $dataStartIndex = $index + 1;
                    Log::info('Found header row', ['header_index' => $headerRowIndex]);
                    break;
                }
            }

            if ($headerRowIndex === null) {
                // If no header found, assume first row is header and data starts from second row
                $headerRowIndex = array_key_first($rows);
                $dataStartIndex = $headerRowIndex + 1;
                Log::info('No header row found, assuming first row is header', ['assumed_header_index' => $headerRowIndex]);
            }

            foreach ($rows as $index => $row) {
                // Skip header row
                if ($index <= $headerRowIndex) {
                    Log::info("Skipping header row $index");
                    continue;
                }

                Log::info("Processing row $index", [
                    'row_data' => $row,
                    'original_index' => $index
                ]);

                // Skip completely empty rows
                $hasData = false;
                foreach ($row as $cell) {
                    if (!empty(trim($cell ?? ''))) {
                        $hasData = true;
                        break;
                    }
                }

                if (!$hasData) {
                    Log::info("Skipping empty row $index");
                    $skippedRows[] = "Row " . ($index + 1) . ": Empty row";
                    continue;
                }

                try {
                    // Map Excel columns to variables with better error handling
                    $type = strtolower(trim($row[0] ?? ''));
                    $fileName = trim($row[1] ?? '');
                    $title = trim($row[2] ?? '');
                    $source = trim($row[3] ?? '');
                    $alt_text = trim($row[4] ?? '');
                    $description = trim($row[5] ?? '');
                    $note = trim($row[6] ?? '');
                    $tag = trim($row[7] ?? '');
                    $categoriesStr = trim($row[8] ?? '');

                    Log::info("Row $index data extraction", [
                        'type' => $type,
                        'fileName' => $fileName,
                        'title' => $title,
                        'source' => $source,
                        'categories' => $categoriesStr
                    ]);

                    // Validate type
                    if ($type !== 'photo') {
                        $skippedRows[] = "Row " . ($index + 1) . ": Not a photo (type: '$type')";
                        continue;
                    }

                    $photoRows[] = $index + 1;

                    // Validate required fields
                    $missingFields = [];
                    if (empty($fileName))
                        $missingFields[] = 'fileName';
                    if (empty($title))
                        $missingFields[] = 'title';
                    if (empty($source))
                        $missingFields[] = 'source';

                    if (!empty($missingFields)) {
                        $errors[] = "Row " . ($index + 1) . ": Missing required fields: " . implode(', ', $missingFields);
                        continue;
                    }

                    // Check if file exists
                    $mediaPath = $mediaDir . '/' . $fileName;
                    if (!file_exists($mediaPath)) {
                        // List available files for debugging
                        $availableFiles = glob($mediaDir . '/*');
                        $errors[] = "Row " . ($index + 1) . ": File not found - '$fileName'. Available files: " . implode(', ', array_map('basename', $availableFiles));
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
                                // Just warn, don't fail the whole row
                                $errors[] = "Row " . ($index + 1) . ": Category not found - '$categoryName' (continuing without this category)";
                            }
                        }
                    }

                    // Process image
                    $slug = Str::slug($title);
                    $extension = pathinfo($fileName, PATHINFO_EXTENSION);
                    $newFileName = time() . '_' . $slug . '_' . $index . '.' . $extension;

                    $storedPath = Storage::disk('public')->putFileAs(
                        'foto_content',
                        new File($mediaPath),
                        $newFileName
                    );

                    $exifData = static::extractExifData($mediaPath);

                    // Create ContentPhoto
                    $contentPhoto = ContentPhoto::create([
                        'user_id' => $userId,
                        'title' => $title,
                        'slug' => $slug,
                        'description' => $description,
                        'source' => $source,
                        'alt_text' => $alt_text,
                        'tag' => $tag,
                        'image_url' => $storedPath,
                        'status' => 'pending',
                        'approved_at' => null,
                    ]);

                    // Create MetadataPhoto
                    MetadataPhoto::create([
                        'content_photo_id' => $contentPhoto->id,
                        'collection_date' => $exifData['collection_date'],
                        'file_size' => $exifData['file_size'],
                        'aperture' => $exifData['aperture'],
                        'location' => $exifData['location'],
                        'model' => $exifData['model'],
                        'ISO' => $exifData['ISO'],
                        'dimensions' => $exifData['dimensions'],
                    ]);

                    // Attach categories
                    if (!empty($categoryIds)) {
                        foreach ($categoryIds as $categoryId) {
                            $contentPhoto->categoryContents()->create([
                                'category_id' => $categoryId
                            ]);
                        }
                    }

                    $processedCount++;
                    Log::info("Successfully processed row $index", [
                        'content_photo_id' => $contentPhoto->id,
                        'processed_count' => $processedCount
                    ]);

                } catch (\Exception $e) {
                    $errors[] = "Row " . ($index + 1) . ": " . $e->getMessage();
                    Log::error('Error processing row', [
                        'row' => $index + 1,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Clean up temp directory
            static::deleteDirectory($tempDir);
            $tempDir = null;

            // Log final summary
            Log::info('Final processing summary', [
                'total_rows_processed' => count($rows),
                'photo_rows_found' => $photoRows,
                'successfully_processed' => $processedCount,
                'errors' => $errors,
                'skipped_rows' => $skippedRows
            ]);

            // Show notifications
            if ($processedCount > 0) {
                Notification::make()
                    ->title("Bulk Upload Completed!")
                    ->body("Successfully processed $processedCount photos" .
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
                $message = "No photos were processed. ";
                if (!empty($photoRows)) {
                    $message .= "Found " . count($photoRows) . " photo rows but encountered errors.";
                } else {
                    $message .= "No photo rows found in Excel file.";
                }

                Notification::make()
                    ->title('No Photos Processed')
                    ->body($message)
                    ->warning()
                    ->persistent()
                    ->send();
            }

        } catch (\Exception $e) {
            Log::error('Bulk upload error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($tempDir && is_dir($tempDir)) {
                static::deleteDirectory($tempDir);
            }

            Notification::make()
                ->title('Bulk Upload Failed')
                ->body($e->getMessage())
                ->danger()
                ->persistent()
                ->send();
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

    protected static function extractExifData($imagePath): array
    {
        try {
            // Handle different input types
            if ($imagePath instanceof TemporaryUploadedFile) {
                $realPath = $imagePath->getRealPath();
            } elseif (is_string($imagePath)) {
                // For bulk upload, it's already a file path
                $realPath = $imagePath;
            } else {
                // Fallback
                $realPath = (string) $imagePath;
            }

            if (!file_exists($realPath) || !is_readable($realPath)) {
                Log::warning('File not found or not readable for EXIF extraction', [
                    'path' => $realPath,
                    'original_input' => $imagePath
                ]);
                return static::getDefaultExifData($realPath);
            }

            $image = Image::make($realPath);
            $dimensions = $image->width() . 'x' . $image->height();

            $exif = null;
            if (in_array(strtolower(pathinfo($realPath, PATHINFO_EXTENSION)), ['jpg', 'jpeg'])) {
                $exif = @exif_read_data($realPath);
            }

            return [
                'collection_date' => !empty($exif['DateTimeOriginal']) ?
                    date('Y-m-d H:i:s', strtotime($exif['DateTimeOriginal'])) : null,
                'file_size' => filesize($realPath),
                'aperture' => isset($exif['COMPUTED']['ApertureFNumber']) ?
                    $exif['COMPUTED']['ApertureFNumber'] : null,
                'location' => isset($exif['GPSLatitude']) ?
                    static::getGPSCoordinates($exif) : null,
                'model' => isset($exif['Model']) ? $exif['Model'] : null,
                'ISO' => isset($exif['ISOSpeedRatings']) ?
                    $exif['ISOSpeedRatings'] : null,
                'dimensions' => $dimensions,
            ];
        } catch (\Exception $e) {
            Log::warning('EXIF extraction failed', [
                'input' => $imagePath,
                'error' => $e->getMessage()
            ]);
            return static::getDefaultExifData($imagePath);
        }
    }

    protected static function getDefaultExifData($imagePath): array
    {
        $fileSize = 0;
        try {
            if ($imagePath instanceof TemporaryUploadedFile) {
                $fileSize = $imagePath->getSize();
            } elseif (is_string($imagePath) && file_exists($imagePath)) {
                $fileSize = filesize($imagePath);
            }
        } catch (\Exception $e) {
            Log::warning('Could not get file size', ['error' => $e->getMessage()]);
        }

        return [
            'collection_date' => null,
            'file_size' => $fileSize,
            'aperture' => null,
            'location' => null,
            'model' => null,
            'ISO' => null,
            'dimensions' => 'unknown',
        ];
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



    protected static function getGPSCoordinates($exif): ?string
    {
        if (!isset($exif['GPSLatitude']) || !isset($exif['GPSLongitude'])) {
            return null;
        }

        $lat = static::getGps($exif['GPSLatitude'], $exif['GPSLatitudeRef']);
        $lon = static::getGps($exif['GPSLongitude'], $exif['GPSLongitudeRef']);

        return $lat . ',' . $lon;
    }

    protected static function getGps($exifCoord, $hemi): float
    {
        $degrees = count($exifCoord) > 0 ? static::gps2Num($exifCoord[0]) : 0;
        $minutes = count($exifCoord) > 1 ? static::gps2Num($exifCoord[1]) : 0;
        $seconds = count($exifCoord) > 2 ? static::gps2Num($exifCoord[2]) : 0;

        $flip = ($hemi == 'W' or $hemi == 'S') ? -1 : 1;

        return $flip * ($degrees + $minutes / 60 + $seconds / 3600);
    }

    protected static function gps2Num($coordPart): float
    {
        $parts = explode('/', $coordPart);
        if (count($parts) <= 0)
            return 0.0;
        if (count($parts) == 1)
            return floatval($parts[0]);
        return floatval($parts[0]) / floatval($parts[1]);
    }
}
