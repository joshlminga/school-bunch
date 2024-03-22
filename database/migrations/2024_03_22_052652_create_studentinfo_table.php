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
        Schema::create('studentinfo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doc')->constrained('schooldoc')->onDelete('cascade');

            $table->string('county')->nullable()->default(null);
            $table->string('subcounty')->nullable()->default(null);
            $table->string('assessor', 2000)->nullable()->default(null);
            $table->string('school', 2000)->nullable()->default(null);
            $table->string('electricity')->nullable()->default(null);
            $table->string('internet')->nullable()->default(null);
            $table->string('ict')->nullable()->default(null);
            $table->string('learner')->nullable()->default(null);
            $table->string('assessment')->nullable()->default(null);
            $table->string('birth')->nullable()->default(null);
            $table->string('gender')->nullable()->default(null);
            $table->string('parent')->nullable()->default(null);
            $table->string('phonenumber')->nullable()->default(null);
            $table->mediumText('visual')->nullable()->default(null);
            $table->mediumText('reading')->nullable()->default(null);
            $table->mediumText('physical')->nullable()->default(null);
            $table->longText('meta')->nullable()->default(null);

            $table->integer('printed')->default(0);
            $table->timestamp('last_printed')->nullable()->default(null)->useCurrent();

            $table->integer('flag')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studentinfo');
    }
};
