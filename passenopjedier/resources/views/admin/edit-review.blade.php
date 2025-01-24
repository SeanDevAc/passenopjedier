<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}" media="screen">
    <title>PassenOpJeDier - Review Bewerken</title>
</head>
<body>
    <div class="generalWrapper">
        <div class="topNav">
            <p>Review Bewerken</p>
            <div>
                <a href="{{ route('admin.dashboard') }}">back</a>
            </div>
        </div>

        <div class="editCard">
            <h2>Review van {{ $review->owner_name }} voor {{ $review->sitter_name }}</h2>
            <form method="POST" action="{{ route('admin.reviews.update', $review->reviewid) }}" class="editForm">
                @csrf
                @method('PUT')

                <div class="formGroup">
                    <label for="rating">Rating</label>
                    <select id="rating" name="rating" required>
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ $review->rating == $i ? 'selected' : '' }}>
                                {{ $i }} sterren
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="formGroup">
                    <label for="description">Beschrijving</label>
                    <textarea id="description" name="description" rows="4" required>{{ $review->description }}</textarea>
                </div>

                <button type="submit" class="saveButton">Opslaan</button>
            </form>
        </div>
    </div>
</body>
</html>
