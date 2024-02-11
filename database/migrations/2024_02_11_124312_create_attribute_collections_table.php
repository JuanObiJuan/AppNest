<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attribute_collections', function (Blueprint $table) {
            $table->id();
            $table->json('languages');
            $table->json('json_schema');
            $table->json('json_ui_schema');
            $table->foreignIdFor(\App\Models\Application::class)->nullable();
            $table->foreignIdFor(\App\Models\Scene::class)->nullable();
            $table->foreignIdFor(\App\Models\Voice::class)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attribute_collections');
    }
};
