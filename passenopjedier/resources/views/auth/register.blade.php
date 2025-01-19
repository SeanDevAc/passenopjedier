<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}" media="screen">
    <title>PassenOpJeDier - Registreren</title>
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

        <div class="authCard">
            <h2>Registreren</h2>
            <form method="POST" action="{{ route('register') }}" class="authForm">
                @csrf
                <div class="formGroup">
                    <label for="name">Naam</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="formGroup">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="formGroup">
                    <label for="password">Wachtwoord</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="formGroup">
                    <label for="password_confirmation">Bevestig wachtwoord</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required>
                </div>

                <button type="submit" class="authButton">Registreren</button>
            </form>
            <div class="authLinks">
                <p>Al een account? <a href="{{ route('login') }}">Log hier in</a></p>
            </div>
        </div>
    </div>
</body>
</html>
