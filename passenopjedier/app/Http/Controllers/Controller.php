<?php

namespace App\Http\Controllers;

// use App\Models\PetSittingRequest;
// use App\Models\Pet;
// use App\Models\User;
// use Illuminate\Http\Request;

abstract class Controller
{
    // // Haal alle verzoeken op voor een bepaalde pet
    // $petSittingRequests = PetSittingRequest::where('pet_id', $petId)->get();

    // // Haal alle reviews op voor een pet_sitting_request
    // $reviews = $petSittingRequest->reviews;

    // // Maak een nieuw verzoek aan
    // PetSittingRequest::create([
    //     'date' => '2024-12-15',
    //     'hourly_rate' => 15,
    //     'pet_id' => 1,
    //     'user_id' => 2,
    // ]);

    // // Voeg een review toe aan een verzoek
    // $review = new Review();
    // $review->rating = 5;
    // $review->comment = "Excellent service!";
    // $review->pet_sitting_request_id = 1;
    // $review->save();

}
