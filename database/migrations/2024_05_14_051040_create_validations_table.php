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
        Schema::create("validations", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId("asset_id")
                ->unique()
                ->constrained(
                    table: "assets",
                    indexName: "validations_asset_id"
                );
            $table
                ->foreignId("validator_id")
                ->constrained(table: "users", indexName: "validations_user_id");
            $table
                ->foreignId("office_id")
                ->constrained(
                    table: "offices",
                    indexName: "validations_office_id"
                );
            $table->enum("condition", ["Good", "Obsolete", "Broken"]);
            $table->string("comment");
            $table->string("month_period");
            $table->boolean("is_validate")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("validations");
    }
};
