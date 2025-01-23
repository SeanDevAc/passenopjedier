<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/register.css') }}" media="screen">
    <title>PassenOpJeDier - Registreren</title>
</head>
<body>
    <div class="generalWrapper">
        <div class="topNav">
            <p>Registreren</p>
            <a href="/">back</a>
        </div>

        <div class="registerCard">
            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                @csrf

                <h2>Persoonlijke Informatie</h2>
                <div class="formGroup">
                    <label for="name">Naam</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="formGroup">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="formGroup">
                    <label for="password">Wachtwoord</label>
                    <input type="password" id="password" name="password" required>
                    @error('password')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="formGroup">
                    <label for="password_confirmation">Bevestig Wachtwoord</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required>
                </div>

                <div class="formGroup">
                    <label for="birthdate">Geboortedatum</label>
                    <input type="date" id="birthdate" name="birthdate" value="{{ old('birthdate') }}" required>
                    @error('birthdate')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="formGroup">
                    <label for="bio">Over Mij</label>
                    <textarea id="bio" name="bio" rows="4">{{ old('bio') }}</textarea>
                    @error('bio')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="formGroup">
                    <label for="profile_image">Profielfoto</label>
                    <input type="file" id="profile_image" name="profile_image" accept="image/*">
                    @error('profile_image')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="formGroup">
                    <label>
                        <input type="checkbox" name="has_pet" id="has_pet" {{ old('has_pet') ? 'checked' : '' }}>
                        Ik heb een huisdier
                    </label>
                </div>

                <div id="petSection" class="{{ old('has_pet') ? '' : 'hidden' }}">
                    <h2>Huisdier Informatie</h2>
                    <div class="formGroup">
                        <label for="pet_name">Naam Huisdier</label>
                        <input type="text" id="pet_name" name="pet_name" value="{{ old('pet_name') }}">
                        @error('pet_name')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="formGroup">
                        <label for="pet_type">Type Huisdier</label>
                        <select id="pet_type" name="pet_type">
                            <option value="">Selecteer type</option>
                            <option value="hond" {{ old('pet_type') == 'hond' ? 'selected' : '' }}>Hond</option>
                            <option value="kat" {{ old('pet_type') == 'kat' ? 'selected' : '' }}>Kat</option>
                            <option value="vogel" {{ old('pet_type') == 'vogel' ? 'selected' : '' }}>Vogel</option>
                            <option value="konijn" {{ old('pet_type') == 'konijn' ? 'selected' : '' }}>Konijn</option>
                            <option value="vis" {{ old('pet_type') == 'vis' ? 'selected' : '' }}>Vis</option>
                            <option value="anders" {{ old('pet_type') == 'anders' ? 'selected' : '' }}>Anders</option>
                        </select>
                        @error('pet_type')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="formGroup">
                        <label for="pet_description">Over het Huisdier</label>
                        <textarea id="pet_description" name="pet_description" rows="4">{{ old('pet_description') }}</textarea>
                        @error('pet_description')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="formGroup">
                        <label for="pet_image">Foto van het Huisdier</label>
                        <input type="file" id="pet_image" name="pet_image" accept="image/*">
                        @error('pet_image')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="submitButton">Registreren</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('has_pet').addEventListener('change', function() {
            const petSection = document.getElementById('petSection');
            petSection.classList.toggle('hidden', !this.checked);

            // Toggle required attributes based on checkbox
            const petInputs = petSection.querySelectorAll('input, select, textarea');
            petInputs.forEach(input => {
                input.required = this.checked;
            });
        });
    </script>
</body>
</html>
