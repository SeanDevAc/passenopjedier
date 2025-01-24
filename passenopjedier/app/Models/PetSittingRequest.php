<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;


class PetSittingRequest extends Model
{
    // Beschermde velden tegen mass-assignment aanvallen
    protected $fillable = ['date', 'hourly_rate', 'pet_id', 'user_id', 'responder_id'];

    // Datatypes automatisch casten, zoals 'date' naar een DateTime object
    protected $casts = [
        'date' => 'datetime',
    ];

    // Relaties

    // Een pet_sitting_request hoort bij een pet
    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    // Een pet_sitting_request hoort bij een user (de eigenaar)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Een pet_sitting_request kan door een responder (user) worden beantwoord
    public function responder()
    {
        return $this->belongsTo(User::class, 'responder_id');
    }

    // Een pet_sitting_request heeft veel reviews
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Validatie van gegevens
    public static function validate($data)
    {
        $rules = [
            'date' => 'required|date',
            'hourly_rate' => 'required|integer',
            'pet_id' => 'required|exists:pets,id',
            'user_id' => 'required|exists:users,id',
            'responder_id' => 'nullable|exists:users,id',
        ];

        return Validator::make($data, $rules);
    }
}

