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
                    <label>
                        <input type="checkbox" name="haspet" id="haspet" {{ old('haspet') ? 'checked' : '' }}>
                        Ik heb een huisdier
                    </label>
                </div>

                <button type="submit" class="submitButton">Registreren</button>
            </form>
        </div>
    </div>
</body>
</html>
