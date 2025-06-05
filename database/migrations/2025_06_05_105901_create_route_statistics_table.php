<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('route_statistics', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedBigInteger('team_id')->nullable();
            $table->string('method')->nullable();
            $table->string('route')->nullable();
            $table->integer('status')->nullable();
            $table->ipAddress('ip')->nullable();
            $table->dateTime('date');
            $table->unsignedInteger('counter');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->index('date');
            $table->index(['user_id', 'date', 'route', 'method']);
            $table->index(['team_id', 'date', 'route', 'method']);
            $table->index(['route', 'method', 'date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('route_statistics');
    }
};
