<div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
    <h4 class="font-semibold text-gray-800 dark:text-gray-200 mb-2">Required ZIP Structure for Videos:</h4>
    <pre class="text-sm text-gray-600 dark:text-gray-400 font-mono">
upload.zip
├── metadata_template.xlsx
├── media/
│   ├── video1.mp4
│   ├── video2.mov
│   └── ...
└── thumbnail/
    ├── thumb1.jpg
    ├── thumb2.png
    └── ...
    </pre>

    <div class="mt-3 text-sm text-gray-600 dark:text-gray-400">
        <p><strong>Excel Columns (metadata_template.xlsx):</strong></p>
        <ul class="list-disc list-inside ml-2 space-y-1">
            <li><strong>A:</strong> Type (video)</li>
            <li><strong>B:</strong> File Name (optional if YouTube link provided)</li>
            <li><strong>C:</strong> Title</li>
            <li><strong>D:</strong> Source</li>
            <li><strong>E:</strong> Alt Text</li>
            <li><strong>F:</strong> Description</li>
            <li><strong>G:</strong> Note</li>
            <li><strong>H:</strong> Tags</li>
            <li><strong>I:</strong> Categories (comma-separated)</li>
            <li><strong>J:</strong> YouTube Link (optional)</li>
            <li><strong>K:</strong> Thumbnail File Name</li>
        </ul>

        <div class="mt-3 p-2 bg-blue-50 dark:bg-blue-900 rounded">
            <p class="text-xs"><strong>Note:</strong> For videos, either provide a video file in media/ folder OR a
                YouTube link. Thumbnail files should be placed in thumbnail/ folder.</p>
        </div>
    </div>
</div>
