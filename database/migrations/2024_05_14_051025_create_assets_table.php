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
            $table->string("uniqueid")->unique();
            $table->string("asset_name");
            $table->enum("type",['Laptop','PC','Monitor','IP Camera','Surveilance','Attendance','Others']);
            $table->string("sn")->unique();
            $table->string("os")->nullable();
            $table->string("hostname")->nullable();
            $table->string("mac_address")->nullable();
            $table->string("office_name");
            $table->date("purchase_date")->nullable();
            $table->date("asset_in")->nullable();
            $table
                ->foreignId("office_id")
                ->constrained(table: "offices", indexName: "assets_office_id")
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("assets");
    }
};
