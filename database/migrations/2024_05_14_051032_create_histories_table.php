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
            $table->string("uniqueid");
            $table->string("asset_name");
            $table->string("sn");
            $table->enum("transaction_type", ["Handover", "Return"]);
            $table->string("name");
            $table->string("office_name");
            $table->date("transaction_date");
            $table->string("comment");
            $table
                ->foreignId("asset_id")
                ->constrained(
                    table: "assets", 
                    indexName: "histories_asset_id")
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table
                ->foreignId("person_id")
                ->constrained(
                    table: "persons",
                    indexName: "histories_person_id"
                )
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table
                ->foreignId("office_id")
                ->constrained(
                    table: "offices",
                    indexName: "histories_office_id"
                )
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
        Schema::dropIfExists("histories");
    }
};
