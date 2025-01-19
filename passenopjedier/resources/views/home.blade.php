<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}" media="screen">
    <title>PassenOpJeDier - Home</title>
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
            <a href='/'>back</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Uitloggen</button>
            </form>
        </div>

        <!-- User Card -->
        <div class="userInfoCard">
            <img class="userProfileImage" src="{{ asset('img/pfp1.jpg') }}" alt="Profile Picture" />
            <div class="userInfo">
                <p>Naam: {{ $user->name }}</p>
                <p>Leeftijd: {{ $user->birthdate ?? 'Niet opgegeven' }}</p>
            </div>
        </div>

        <!-- Pet Cards -->
        @if(isset($pets) && $pets->count() > 0)
            @foreach($pets as $pet)
                <div class="petInfoCard">
                    <img class="petProfileImage" src="{{ asset($pet->image_path ?? 'img/dog.webp') }}" alt="Pet Picture" />
                    <div class="petInfo">
                        <p>Naam: {{ $pet->name }}</p>
                        <p>Leeftijd: {{ $pet->age }}</p>
                        <p>Ras: {{ $pet->race }}</p>
                    </div>
                    <div class="petDescription">
                        <p>Beschrijving: {{ $pet->bio }}</p>
                    </div>
                </div>
            @endforeach
        @endif

        <!-- Vacancies Section -->
        <div class="vacancyFilterSection">
            <input type="text" placeholder="Zoeken..." />
            <button>Filter</button>
        </div>

        @if(isset($vacancys) && $vacancys->count() > 0)
            @foreach($vacancys as $vacancy)
                <div class="vacancyCards">
                    <div class="vacancyCardTitle">
                        <div class="profileImageContainer">
                            <img src="{{ asset('img/profile-placeholder.jpg') }}" alt="Profile" style="width: 50px; height: 50px; border-radius: 50%;" />
                        </div>
                        <div class="vacancyInfo">
                            <p>Naam: {{ $vacancy->owner_name }}</p>
                            <p>Tarief: €{{ $vacancy->rate }}</p>
                            <p>Locatie: {{ $vacancy->location ?? 'Niet opgegeven' }}</p>
                        </div>
                        <div class="actionButtons">
                            <button><a href="/profile/{{ $vacancy->ownerid }}">Bekijk profiel</a></button>
                            <form method="POST" action="{{ route('apply.vacancy') }}">
                                @csrf
                                <input type="hidden" name="vacancy_id" value="{{ $vacancy->vacancyid }}">
                                <button type="submit">Apply</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

        <!-- Reviews Section -->
        @if(isset($reviews) && $reviews->count() > 0)
            @foreach($reviews as $review)
                <div class="employeeCards">
                    <div class="employeeCardUserInfo">
                        <p>Naam: {{ $review->sitter_name }}</p>
                        <p>Rating: {{ $review->rating }} / 5</p>
                        <p>{{ $review->description }}</p>
                    </div>
                </div>
            @endforeach
        @endif

        <!-- Applications Section -->
        <div class="applicationsSection">
            <h2>Sollicitaties op jouw vacatures</h2>
            @if(isset($applications) && $applications->count() > 0)
                @foreach($applications as $application)
                    <div class="vacancyCards">
                        <div class="vacancyCardTitle">
                            <div class="profileImageContainer">
                                <img src="{{ asset('img/profile-placeholder.jpg') }}" alt="Profile" style="width: 50px; height: 50px; border-radius: 50%;" />
                            </div>
                            <div class="vacancyInfo">
                                <p>Sollicitant: {{ $application->applicant_name }}</p>
                                <p>Tarief: €{{ $application->rate }}</p>
                                <p>Datum: {{ \Carbon\Carbon::parse($application->datetime)->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="actionButtons">
                                <button><a href="/profile/{{ $application->applicantid }}">Bekijk profiel</a></button>
                                <button class="accept-btn">Accepteer</button>
                                <button class="reject-btn">Weiger</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <p>Geen sollicitaties gevonden</p>
            @endif
        </div>
    </div>
</body>
</html>
