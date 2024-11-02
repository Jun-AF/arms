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
        Schema::create("histories", function (Blueprint $table) {
            $table->id();
            $table->string("asset_name",100);
            $table->string("sn",25);
            $table->enum("transaction_type", ["handover", "return"]);
            $table->date("transaction_date");
            $table->string("comment",100);
            $table->foreignId("asset_id")
                ->unsigned()
                ->constrained(table: "it_assets", indexName: "histories_asset_id")
                ->cascadeOnDelete();
            $table->foreignId("person_id")
                ->unsigned()
                ->constrained(table: "persons", indexName: "histories_person_id")
                ->noActionOnDelete();
            $table->foreignId("office_id")
                ->unsigned()
                ->constrained(table: "offices", indexName: "histories_office_id")
                ->noActionOnDelete();
            $table->timestamps();
        });

        Schema::rename("histories", "it_assets_histories");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("histories");
    }
};
