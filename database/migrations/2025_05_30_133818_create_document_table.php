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
        Schema::create('document', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id')->index(); // indexed
            $table->string('tag')->index();                    // indexed
            $table->string('document_no')->unique();           // unique index
            $table->longText('summary')->nullable()->index();
            $table->unsignedBigInteger('user_id')->index();    // indexed
            $table->timestamps();

            $table->foreign('patient_id')->references('id')->on('patient')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document');
    }
};
