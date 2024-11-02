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
        Schema::create('warehouse_assets', function (Blueprint $table) {
            $table->id();
            $table->string("uniqueid",7)->unique();
            $table->string("asset_name",100);
            $table->string("sn",12)->unique();
            $table->foreignId("type_id")
                ->constrained(table: "asset_types", indexName: "asset_types_id")
                ->noActionOnDelete();
            $table->date("guarantee_date")->nullable();
            $table->date("purchase_date")->nullable();
            $table->date("asset_in")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_assets');
    }
};
