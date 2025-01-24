<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}" media="screen">
    <title>PassenOpJeDier - Review Schrijven</title>
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
            <p>Welkom {{ $user->name }}</p>
            <div>
                <a href='/'>back</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Uitloggen</button>
                </form>
            </div>
        </div>

        <div class="userInfoCard">
            <div class="profileImageContainer">
                <img class="userProfileImage" src="{{ asset('img/pfp1.jpg') }}" alt="Profile Picture" />
            </div>
            <div class="userInfo">
                <h3>{{ $user->name }}</h3>
                <p>Geboortedatum: {{ $user->birthdate ?? '[birthdate]' }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('reviews.store') }}" id="reviewForm" class="reviewForm">
            @csrf
            <div class="reviewInputSection">
                <div class="reviewContext">
                    <label>Schrijf een review voor oppas van {{ $vacancy->pet_name }}...</label>
                    <textarea id="description" name="description" required></textarea>
                </div>
                <div class="star-rating">
                    <span data-value="1">&#9733;</span>
                    <span data-value="2">&#9733;</span>
                    <span data-value="3">&#9733;</span>
                    <span data-value="4">&#9733;</span>
                    <span data-value="5">&#9733;</span>
                </div>
                <input type="hidden" id="rating" name="rating" value="0">
                <input type="hidden" name="sitterid" value="{{ $sitter->id }}">
                <input type="hidden" name="petid" value="{{ $vacancy->petid }}">
                <p id="rating-result">Beoordeling: 0 sterren</p>
            </div>
            <button type="submit" class="reviewPostButton">Review Plaatsen</button>
        </form>
    </div>

    <script>
        const stars = document.querySelectorAll('.star-rating span');
        const ratingInput = document.getElementById('rating');
        const result = document.getElementById('rating-result');
        let selectedRating = 0;

        stars.forEach((star) => {
            star.addEventListener('mouseover', () => {
                updateStars(star.dataset.value);
            });

            star.addEventListener('mouseout', () => {
                updateStars(selectedRating);
            });

            star.addEventListener('click', () => {
                selectedRating = star.dataset.value;
                ratingInput.value = selectedRating;
                result.textContent = `Beoordeling: ${selectedRating} sterren`;
            });
        });

        function updateStars(rating) {
            stars.forEach((star) => {
                star.style.color = star.dataset.value <= rating ? '#ffc107' : '#ddd';
            });
        }
    </script>
</body>
</html>
