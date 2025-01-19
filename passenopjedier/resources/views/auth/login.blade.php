<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}" media="screen">
    <title>PassenOpJeDier - Login</title>
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
            <h2>Inloggen</h2>
            <form method="POST" action="{{ route('login') }}" class="authForm">
                @csrf
                <div class="formGroup">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="formGroup">
                    <label for="password">Wachtwoord</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="authButton">Inloggen</button>
            </form>
            <div class="authLinks">
                <p>Nog geen account? <a href="{{ route('register') }}">Registreer hier</a></p>
            </div>
        </div>
    </div>
</body>
</html>
