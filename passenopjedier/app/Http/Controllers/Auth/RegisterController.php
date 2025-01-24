<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\ImageUploader;
use Carbon\Carbon;

class RegisterController extends Controller
{
    protected $imageUploader;

    public function __construct(ImageUploader $imageUploader)
    {
        $this->imageUploader = $imageUploader;
    }

    protected function validator(array $data)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'birthdate' => ['required', 'date'],
            'bio' => ['nullable', 'string'],
            'profileimage' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'has_pet' => ['nullable', 'boolean'],
        ];

        return validator($data, $rules);
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        // Gebruik dezelfde validatieregels als in de validator methode
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Begin een database transactie
        DB::beginTransaction();

        try {
            // Maak de gebruiker aan
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'birthdate' => $request->birthdate,
                'bio' => $request->bio,
            ];

            // Verwerk de profielfoto als die is geüpload
            if ($request->hasFile('profile_image')) {
                $profileImage = $request->file('profile_image');
                $profileImageData = base64_encode(file_get_contents($profileImage->getRealPath()));

                // Voeg de gebruiker toe met profielfoto
                $userId = DB::table('users')->insertGetId($userData);

                DB::statement("
                    UPDATE users
                    SET profile_image = decode(?, 'base64')
                    WHERE id = ?
                ", [$profileImageData, $userId]);
            } else {
                $userId = DB::table('users')->insertGetId($userData);
            }

            DB::commit();

            // Log de gebruiker in
            Auth::loginUsingId($userId);

            return redirect()->route('home')->with('success', 'Registratie succesvol!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->withErrors(['error' => 'Er is iets misgegaan bij de registratie. Probeer het opnieuw.']);
        }
    }
}
