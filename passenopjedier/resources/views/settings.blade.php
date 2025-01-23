<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}" media="screen">
    <title>PassenOpJeDier - Instellingen</title>
</head>
<body>
    <div class="generalWrapper">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <div class="topNav">
            <p>Instellingen</p>
            <div>
                <a href='/home'>back</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Uitloggen</button>
                </form>
            </div>
        </div>

        <div class="settingsCard">
            <h2>Profiel Instellingen</h2>
            <form method="POST" action="{{ route('settings.update') }}" class="settingsForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="formGroup">
                    <label for="name">Naam</label>
                    <input type="text" id="name" name="name" value="{{ $user->name }}" required>
                </div>

                <div class="formGroup">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ $user->email }}" required>
                </div>

                <div class="formGroup">
                    <label for="birthdate">Geboortedatum</label>
                    <input type="date" id="birthdate" name="birthdate" value="{{ $user->birthdate }}">
                </div>

                <div class="formGroup">
                    <label for="bio">Bio</label>
                    <textarea id="bio" name="bio" rows="4">{{ $user->bio }}</textarea>
                </div>

                <div class="formGroup">
                    <label for="profile_image">Profielfoto</label>
                    <input type="file" id="profile_image" name="profile_image" accept="image/*">
                </div>

                <button type="submit" class="settingsButton">Opslaan</button>
            </form>
        </div>

        <div class="settingsCard">
            <h2>Wachtwoord Wijzigen</h2>
            <form method="POST" action="{{ route('settings.password') }}" class="settingsForm">
                @csrf
                @method('PUT')

                <div class="formGroup">
                    <label for="current_password">Huidig Wachtwoord</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>

                <div class="formGroup">
                    <label for="new_password">Nieuw Wachtwoord</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>

                <div class="formGroup">
                    <label for="new_password_confirmation">Bevestig Nieuw Wachtwoord</label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" required>
                </div>

                <button type="submit" class="settingsButton">Wachtwoord Wijzigen</button>
            </form>
        </div>

        <div class="settingsCard">
            <h2>Account Verwijderen</h2>
            <p class="deleteWarning">Let op: Deze actie kan niet ongedaan worden gemaakt.</p>
            <form method="POST" action="{{ route('settings.delete') }}" class="settingsForm">
                @csrf
                @method('DELETE')

                <div class="formGroup">
                    <label for="delete_confirmation">Typ "VERWIJDER" om te bevestigen</label>
                    <input type="text" id="delete_confirmation" name="delete_confirmation" required
                           pattern="VERWIJDER" title="Typ 'VERWIJDER' om te bevestigen">
                </div>

                <button type="submit" class="deleteButton">Account Verwijderen</button>
            </form>
        </div>
    </div>
</body>
</html>
