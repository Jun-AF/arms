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
        Schema::create("activities", function (Blueprint $table) {
            $table->id();
            $table->foreignId("validator_id")
                ->unsigned()
                ->constrained(table: "users", indexName: "validations_user_id")
                ->noActionOnDelete();
            $table->string("message",100);
            $table->enum("condition", ["Good", "Obsolete", "Broken"]);
            $table->boolean("is_read")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("activities");
    }
};
