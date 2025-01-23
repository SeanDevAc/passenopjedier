<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}" media="screen">
    <title>PassenOpJeDier - Vacature Bewerken</title>
</head>
<body>
    <div class="generalWrapper">
        <div class="topNav">
            <p>Welkom {{ Auth::user()->name }}</p>
            <div>
                <a href='/'>back</a>
            </div>
        </div>

        <div class="createVacancyForm">
            <h2>Vacature Bewerken</h2>

            <form method="POST" action="{{ route('vacancy.update', $vacancy->vacancyid) }}">
                @csrf
                @method('PUT')

                <div class="formGroup">
                    <label for="pet_id">Huisdier</label>
                    <select name="pet_id" id="pet_id" required>
                        @foreach($pets as $pet)
                            <option value="{{ $pet->petid }}" {{ $pet->petid == $vacancy->petid ? 'selected' : '' }}>
                                {{ $pet->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="formGroup">
                    <label for="rate">Tarief per uur (€)</label>
                    <input type="number" name="rate" id="rate" step="0.01" min="0" value="{{ $vacancy->rate }}" required>
                </div>

                <div class="formGroup">
                    <label for="datetime">Datum en tijd</label>
                    <input type="datetime-local" name="datetime" id="datetime"
                           value="{{ date('Y-m-d\TH:i', strtotime($vacancy->datetime)) }}" required>
                </div>

                <div class="formGroup">
                    <label for="duration">Duur (uren)</label>
                    <input type="number" name="duration" id="duration" min="1" value="{{ $vacancy->duration }}" required>
                </div>

                <div class="formGroup">
                    <label for="description">Beschrijving</label>
                    <textarea name="description" id="description" rows="4" required>{{ $vacancy->description }}</textarea>
                </div>

                <div class="formGroup">
                    <button type="submit" class="submitButton">Vacature Bijwerken</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
