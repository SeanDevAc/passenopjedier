<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}" media="screen">
    <title>Instellingen - PassenOpJeDier</title>
</head>
<body>
    <div class="generalWrapper">
        <div class="topNav">
            <h1>Instellingen</h1>
            <a href="{{ route('home') }}">Terug naar Home</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="settingsCard">
            <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="formGroup">
                    <label for="name">Naam</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="formGroup">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="formGroup">
                    <label for="bio">Over Mij</label>
                    <textarea id="bio" name="bio" rows="4">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="formGroup">
                    <label for="birthdate">Geboortedatum</label>
                    <input type="date" id="birthdate" name="birthdate"
                           value="{{ old('birthdate', $user->birthdate) }}"
                           max="{{ date('Y-m-d', strtotime('-18 years')) }}"
                           min="{{ date('Y-m-d', strtotime('-100 years')) }}"
                           required>
                    @error('birthdate')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="formGroup">
                    <label for="haspet">Heb je een huisdier?</label>
                    <select id="haspet" name="haspet" required>
                        <option value="1" {{ old('haspet', $user->haspet) === true ? 'selected' : '' }}>Ja</option>
                        <option value="0" {{ old('haspet', $user->haspet) === false ? 'selected' : '' }}>Nee</option>
                    </select>
                    @error('haspet')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="formGroup">
                    <label for="profileimage">Profielfoto</label>
                    @if($user->profileimage)
                        <div class="currentImage">
                            <p>Huidige foto:</p>
                            <img src="data:image/jpeg;base64,{{ base64_encode(stream_get_contents($user->profileimage)) }}" alt="Huidige foto">
                        </div>
                    @endif
                    <input type="file" id="profileimage" name="profileimage" accept="image/*">
                    @error('profileimage')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="passwordSection">
                    <h3>Wachtwoord Wijzigen</h3>
                    <div class="formGroup">
                        <label for="current_password">Huidig Wachtwoord</label>
                        <input type="password" id="current_password" name="current_password">
                        @error('current_password')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="formGroup">
                        <label for="new_password">Nieuw Wachtwoord</label>
                        <input type="password" id="new_password" name="new_password">
                        @error('new_password')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="formGroup">
                        <label for="new_password_confirmation">Bevestig Nieuw Wachtwoord</label>
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation">
                    </div>
                </div>

                <button type="submit" class="submitButton">Opslaan</button>
            </form>
        </div>
    </div>
</body>
</html>
