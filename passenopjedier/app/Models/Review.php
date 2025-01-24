<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    // Beschermde velden tegen mass-assignment aanvallen
    protected $fillable = ['pet_sitting_request_id', 'rating', 'comment', 'date'];

    // Relaties

    // Een review hoort bij een pet_sitting_request
    public function petSittingRequest()
    {
        return $this->belongsTo(PetSittingRequest::class);
    }
}
