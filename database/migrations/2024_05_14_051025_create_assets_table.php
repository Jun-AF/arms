<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("assets", function (Blueprint $table) {
            $table->id();
            $table->string("uniqueid",7)->unique();
            $table->string("asset_name",100);
            $table->string("sn",12)->unique();
            $table->string("mac_address",17)->nullable();
            $table->string("ip_address",15)->nullable();
            $table->string("hostname",15)->nullable();
            $table->string("operating_system",15)->nullable();
            $table->mediumInteger("storage_capacity")->nullable();
            $table->foreignId("type_id")
                ->unsigned()
                ->constrained(table: "asset_types", indexName: "asset_types_id")
                ->noActionOnDelete();
            $table->date("guarantee_date")->nullable();
            $table->date("purchase_date")->nullable();
            $table->date("asset_in")->nullable();
            $table->timestamps();
        });

        Schema::rename("assets", "it_assets");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("assets");
    }
};
