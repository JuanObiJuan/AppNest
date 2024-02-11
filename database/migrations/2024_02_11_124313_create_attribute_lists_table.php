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
        Schema::create('attribute_lists', function (Blueprint $table) {
            $table->id();
            $table->string('language_key');
            $table->json('json_schema');
            $table->json('json_ui_schema');
            $table->json('json_data');
            $table->foreignIdFor(\App\Models\AttributeCollection::class)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attribute_lists');
    }
};
