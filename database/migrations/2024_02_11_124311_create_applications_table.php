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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->json('languages')->nullable();
            $table->string('default_language')->nullable();
            $table->json('json_data')->nullable();
            $table->json('json_schema')->nullable();
            $table->json('json_admin_ui_schema')->nullable();
            $table->json('json_manager_ui_schema')->nullable();
            $table->foreignIdFor(\App\Models\Organization::class)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
