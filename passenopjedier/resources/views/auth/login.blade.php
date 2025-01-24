<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}" media="screen">
    <title>PassenOpJeDier - Login</title>
</head>
<body>
    <div class="generalWrapper">
        <div class="topNav">
            <p>Login</p>
            <a href="/">back</a>
        </div>

        <div class="loginCard">
            <div class="loginHeader">
                <h1>Welkom terug!</h1>
                <p>Log in om verder te gaan</p>
            </div>

            @if($errors->any())
                <div class="alert alert-error" id="errorAlert">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if(session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if(session('banned'))
                <div id="banPopup" class="popup">
                    <div class="popup-content">
                        <h2>Account Geblokkeerd</h2>
                        <p>Je account is geblokkeerd door een beheerder. Neem contact op voor meer informatie.</p>
                        <button onclick="closePopup()" class="closeButton">Sluiten</button>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="formGroup">
                    <label for="email">Email</label>
                    <div class="inputWrapper">
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                               class="{{ $errors->has('email') ? 'input-error' : '' }}" required autofocus>
                    </div>
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="formGroup">
                    <label for="password">Wachtwoord</label>
                    <div class="inputWrapper">
                        <input type="password" id="password" name="password"
                               class="{{ $errors->has('password') ? 'input-error' : '' }}" required>
                    </div>
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="submitButton">Inloggen</button>

                <div class="formLinks">
                    <a href="{{ route('register') }}" class="registerLink">Nog geen account? Registreer hier</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function closePopup() {
            document.getElementById('banPopup').style.display = 'none';
        }

        document.addEventListener('DOMContentLoaded', function() {
            const popup = document.getElementById('banPopup');
            if (popup) {
                popup.style.display = 'flex';
            }

            // Fade out error message after 5 seconds
            const errorAlert = document.getElementById('errorAlert');
            if (errorAlert) {
                setTimeout(() => {
                    errorAlert.style.opacity = '0';
                    setTimeout(() => {
                        errorAlert.style.display = 'none';
                    }, 300);
                }, 5000);
            }
        });
    </script>
</body>
</html>
