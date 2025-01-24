<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}" media="screen">
    <title>PassenOpJeDier - Nieuwe Vacature</title>
</head>
<body>
    <div class="generalWrapper">
        <div class="topNav">
            <p>Welkom {{ Auth::user()->name }}</p>
            <div>
                <a href='/'>back</a>
                @if(Auth::user()->is_admin)
                    <a href="{{ route('admin.dashboard') }}" class="iconButton" title="Admin Dashboard">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Uitloggen</button>
                </form>
            </div>
        </div>

        <div class="createVacancyForm">
            <h2>Nieuwe Vacature Aanmaken</h2>

            <form method="POST" action="{{ route('vacancy.store') }}">
                @csrf

                <div class="formGroup">
                    <label for="pet_id">Huisdier</label>
                    <select name="pet_id" id="pet_id" required>
                        <option value="">Selecteer een huisdier</option>
                        @foreach($pets as $pet)
                            <option value="{{ $pet->petid }}">{{ $pet->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="formGroup">
                    <label for="rate">Tarief per uur (€)</label>
                    <input type="number" name="rate" id="rate" step="0.01" min="0" required>
                </div>

                <div class="formGroup">
                    <label for="datetime">Datum en tijd</label>
                    <input type="datetime-local" name="datetime" id="datetime" required>
                </div>

                <div class="formGroup">
                    <label for="duration">Duur (uren)</label>
                    <input type="number" name="duration" id="duration" min="1" required>
                </div>

                <div class="formGroup">
                    <label for="description">Beschrijving</label>
                    <textarea name="description" id="description" rows="4" required></textarea>
                </div>

                <div class="formGroup">
                    <button type="submit" class="submitButton">Vacature Aanmaken</button>
                </div>
            </form>
        </div>
    </div>

    <style>
    .createVacancyForm {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .formGroup {
        margin-bottom: 20px;
    }

    .formGroup label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
    }

    .formGroup input,
    .formGroup select,
    .formGroup textarea {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .submitButton {
        background: #4299e1;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .submitButton:hover {
        background: #3182ce;
    }
    </style>
</body>
</html>
