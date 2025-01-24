<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    // Beschermde velden tegen mass-assignment aanvallen
    protected $fillable = ['name', 'species', 'age', 'owner_id', 'description'];

    // Relaties

    // Een pet hoort bij een eigenaar (user)
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // Een pet heeft veel pet_sitting_requests
    public function petSittingRequests()
    {
        return $this->hasMany(PetSittingRequest::class);
    }
}
