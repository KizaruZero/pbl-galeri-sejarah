<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddFulltextIndexes extends Migration
{
    public function up()
    {
        // Tambahkan FULLTEXT indexes untuk kedua tabel
        DB::statement('ALTER TABLE content_photo ADD FULLTEXT search_index (title, description, note, alt_text)');
        DB::statement('ALTER TABLE content_video ADD FULLTEXT search_index (title, description, note)');
        DB::statement('ALTER TABLE metadata_photo ADD FULLTEXT metadata_search_index (location, tag)');
        DB::statement('ALTER TABLE metadata_video ADD FULLTEXT metadata_search_index (location, tag)');
        DB::statement('ALTER TABLE user_comments ADD FULLTEXT comment_search_index (content)');

    }

    public function down()
    {
        // Hapus FULLTEXT indexes untuk kedua tabel
        DB::statement('ALTER TABLE content_photo DROP INDEX search_index');
        DB::statement('ALTER TABLE content_video DROP INDEX search_index');
        DB::statement('ALTER TABLE metadata_photo DROP INDEX metadata_search_index');
        DB::statement('ALTER TABLE metadata_video DROP INDEX metadata_search_index');
        DB::statement('ALTER TABLE user_comments DROP INDEX comment_search_index');
    }
}

?>