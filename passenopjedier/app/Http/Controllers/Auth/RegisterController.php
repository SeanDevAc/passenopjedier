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

class RegisterController extends Controller
{
    protected $imageUploader;

    public function __construct(ImageUploader $imageUploader)
    {
        $this->imageUploader = $imageUploader;
    }

    protected function validator(array $data)
    {
        return validator($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'birthdate' => ['required', 'date'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'profile_image' => ['nullable', 'image', 'max:2048'],
            'house_image' => ['nullable', 'image', 'max:2048'],
            // 'pet_name' => ['nullable', 'string', 'max:255'],
            // 'pet_type' => ['nullable', 'string', 'max:255'],
            // 'pet_description' => ['nullable', 'string', 'max:1000'],
            // 'pet_image' => ['nullable', 'image', 'max:2048'],
        ]);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $data = $request->all();

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image');
        }
        if ($request->hasFile('house_image')) {
            $data['house_image'] = $request->file('house_image');
        }
        if ($request->hasFile('pet_image')) {
            $data['pet_image'] = $request->file('pet_image');
        }

        $user = $this->create($data);

        // Gebruik de correcte credentials om in te loggen
        Auth::attempt([
            'email' => $data['email'],
            'password' => $data['password']
        ]);

        return redirect()->route('home')->with('success', 'Welkom bij PassenOpJeDier!');
    }

    protected function create(array $data)
    {
        try {
            // Create the user first without images
            $userId = DB::table('users')->insertGetId([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'birthdate' => $data['birthdate'],
                'bio' => $data['bio'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
                'profile_image' => null,
                'house_image' => null
            ]);

            // Handle profile image upload
            if (isset($data['profile_image'])) {
                $profileImage = $this->imageUploader->uploadImage($data['profile_image'], 'profile');
                DB::table('users')
                    ->where('id', $userId)
                    ->update(['profile_image' => $profileImage]);
            }

            // Handle house image upload
            if (isset($data['house_image'])) {
                $houseImage = $this->imageUploader->uploadImage($data['house_image'], 'house');
                DB::table('users')
                    ->where('id', $userId)
                    ->update(['house_image' => $houseImage]);
            }

            // Handle pet registration if user has a pet
            if (isset($data['has_pet']) && $data['has_pet'] === 'on') {
                $petData = [
                    'ownerid' => $userId,
                    'name' => $data['pet_name'],
                    'type' => $data['pet_type'],
                    'description' => $data['pet_description'] ?? null,
                    'image' => null,
                    'created_at' => now(),
                    'updated_at' => now()
                ];

                // Handle pet image upload
                if (isset($data['pet_image'])) {
                    $petImage = $this->imageUploader->uploadImage($data['pet_image'], 'pet');
                    $petData['image'] = $petImage;
                }

                DB::table('pet')->insert($petData);
            }

            return DB::table('users')->where('id', $userId)->first();
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }
}
