<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('error_messages', function (Blueprint $table) {
            $table->id();
            $table->text('text');
            $table->json('metadata')->default(DB::raw('(JSON_OBJECT())'));
            $table->unsignedBigInteger('errorable_id');
            $table->string('errorable_type');
            $table->timestamps();

            $table->index(['errorable_type', 'errorable_id'], 'errorable_index');
            $table->index('errorable_id');
            $table->index('errorable_type');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('error_messages');
    }
};