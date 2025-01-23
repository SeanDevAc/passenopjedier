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
            <p>Vacature Bewerken</p>
            <div>
                <a href="{{ route('admin.dashboard') }}">back</a>
            </div>
        </div>

        <div class="editCard">
            <h2>Vacature voor {{ $vacancy->pet_name }}</h2>
            <form method="POST" action="{{ route('admin.vacancies.update', $vacancy->vacancyid) }}" class="editForm">
                @csrf
                @method('PUT')

                <div class="formGroup">
                    <label for="datetime">Datum en Tijd</label>
                    <input type="datetime-local" id="datetime" name="datetime"
                           value="{{ date('Y-m-d\TH:i', strtotime($vacancy->datetime)) }}" required>
                </div>

                <div class="formGroup">
                    <label for="rate">Tarief (€)</label>
                    <input type="number" id="rate" name="rate" step="0.01" min="0"
                           value="{{ $vacancy->rate }}" required>
                </div>

                <div class="formGroup">
                    <label for="description">Beschrijving</label>
                    <textarea id="description" name="description" rows="4" required>{{ $vacancy->description }}</textarea>
                </div>

                <button type="submit" class="saveButton">Opslaan</button>
            </form>
        </div>
    </div>
</body>
</html>
