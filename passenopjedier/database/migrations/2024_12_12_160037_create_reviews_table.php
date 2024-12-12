<?php

use App\Models\PetSittingRequest;
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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('pet_sitting_request_id');
            $table->foreign('pet_sitting_request_id')->references('id')->on('pet_sitting_requests')->onDelete('cascade');

            $table->integer('rating');
            $table->string('comment', 500);
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
