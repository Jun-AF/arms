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
        Schema::create("borrowed_it_assets", function (Blueprint $table) {
            $table->id();
            $table->foreignId("assets_id")
                ->unsigned()
                ->constrained("it_assets", indexName: "it_asset_id")
                ->noActionOnDelete();
            $table->string("borrower", 25);
            $table->string("office_name", 25);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("borrowed_it_assets");
    }
};
