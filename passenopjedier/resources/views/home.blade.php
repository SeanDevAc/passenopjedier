<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}" media="screen">
    <title>Document</title>
</head>
<body>
    <div class="generalWrapper">
        <div class="topNav">
            <p>Welkom {{ $dummyData['sitterName'] }}</p>
            <a href='/'>back</a>
        </div>

        <div class="userInfoCard">
            <img class="userProfileImage" src="{{ asset('img/pfp1.jpg')}}" />
            <div class="userInfo">
                <p> Naam: {{ $dummyData['sitterName'] }} </p>
                <p> Leeftijd: {{ $dummyData['sitterAge'] }} </p>
            </div>
            <div class="userDescription">
                <p> Beschrijving: {{ $dummyData['sitterBeschrijving'] }} </p>
            </div>
        </div>

        <div class="petInfoCard">
            <img class="petProfileImage" src="{{ asset('img/dog.webp')}}" />
            <div class="petInfo">
                <p> Naam: {{ $dummyData['hdName'] }} </p>
                <p> Leeftijd: {{ $dummyData['hdAge'] }} </p>
                <p> Ras: {{ $dummyData['hdRace'] }} </p>
            </div>
            <div class="petDescription">
                <p> Beschrijving: {{ $dummyData['hdBeschrijving'] }} </p>
            </div>
        </div>

        <div class="jobFilterSection">
            <p> Searchbar... </p>
            <p> Filter icon </p>
        </div>

        <div class="jobCards">
            <div class="jobCardTitle">
                <button><a href="/profile/{{ $dummyData['ownerId'] }}"> x </a></button>
                <p> Advertentie door: {{ $dummyData['ownerName'] }} </p>
                <button> apply </button>
            </div>
            <div class="jobCardInfo">
                <p> Naam: {{ $dummyData['hdName'] }} </p>
                <p> Leeftijd: {{ $dummyData['hdAge'] }} </p>
                <p> Ras: {{ $dummyData['hdRace'] }} </p>
                <p> Tarief: {{ $dummyData['hdTarief'] }} </p>
                <p> Duur: {{ $dummyData['hdTime'] }} </p>
                <p> Adres: {{ $dummyData['hdAdres'] }} </p>
            </div>
        </div>

        <div class="employeeCards">
            <button><a href="/profile/{{ $dummyData['sitterId'] }}"> x </a></button>
            <div class="employeeCardUserInfo">
                <p> Naam: {{ $dummyData['sitterName'] }} </p>
                <p> Rating: {{ $dummyData['sitterRating'] }} </p>
            </div>
            <button> approve </button>
            <button> deny </button>
        </div>
    </div>
</body>
</html>
