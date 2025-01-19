<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}" media="screen">
    <title>PassenOpJeDier - Profiel van {{ $user->name }}</title>
</head>
<body>
    <div class="generalWrapper">
        <div class="topNav">
            <p>Profiel van {{ $user->name }}</p>
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
                <p>Email: {{ $user->email }}</p>
                <p>Leeftijd: {{ $user->age ?? 'Niet opgegeven' }}</p>
                <p>Lid sinds: {{ isset($user->created_at) ? date('d-m-Y', strtotime($user->created_at)) : 'Onbekend' }}</p>
            </div>
        </div>

        <div class="sectionTitle">
            <h2>Huisdieren</h2>
        </div>

        @if(isset($pets) && count($pets) > 0)
            @foreach($pets as $pet)
                <div class="petInfoCard">
                    <div class="profileImageContainer">
                        <img class="petProfileImage" src="{{ asset($pet->image_path ?? 'img/dog.webp') }}" alt="Pet Picture" />
                    </div>
                    <div class="petInfo">
                        <h3>{{ $pet->name }}</h3>
                        <p>Leeftijd: {{ $pet->age }} jaar</p>
                        <p>Ras: {{ $pet->race }}</p>
                    </div>
                </div>
            @endforeach
        @else
            <div class="emptyState">
                <p>Deze gebruiker heeft nog geen huisdieren.</p>
            </div>
        @endif

        <div class="sectionTitle">
            <h2>Reviews</h2>
        </div>

        @if(isset($reviews) && count($reviews) > 0)
            @foreach($reviews as $review)
                <div class="reviewCard">
                    <div class="reviewHeader">
                        <h3>Review van {{ $review->reviewer_name }}</h3>
                        <div class="rating">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="star {{ $i <= $review->rating ? 'filled' : '' }}">&#9733;</span>
                            @endfor
                        </div>
                    </div>
                    <div class="reviewContent">
                        <p>{{ $review->description }}</p>
                        <small>Geplaatst op: {{ date('d-m-Y', strtotime($review->placedat)) }}</small>
                    </div>
                </div>
            @endforeach
        @else
            <div class="emptyState">
                <p>Er zijn nog geen reviews voor deze gebruiker.</p>
            </div>
        @endif
    </div>
</body>
</html>
