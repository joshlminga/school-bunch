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
        Schema::create('shoolinfo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doc')->constrained('schooldoc')->onDelete('cascade');

            $table->string('county')->nullable()->default(null);
            $table->string('sub_county')->nullable()->default(null);
            $table->string('county')->nullable()->default(null);
            $table->string('county')->nullable()->default(null);
            $table->string('county')->nullable()->default(null);
            $table->string('county')->nullable()->default(null);


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shoolinfo');
    }
};
