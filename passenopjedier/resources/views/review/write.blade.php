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
    <div class="topNav">
        <p>Welkom {{ $dummyData['sitterName'] }}</p>
        <a href='/'>back</a>
    </div>
    <div class="userInfoCard">
        <img class= "userProfileImage" src="{{ asset('img/pfp1.jpg')}}" />
        <div class= "userInfo">
            <p> Naam: {{ $dummyData['sitterName'] }} </p>
            <p> Leeftijd: {{ $dummyData['sitterAge'] }} </p>
        </div>
    </div>
    <div class="reviewInputSection">
        <div class="reviewContext">
            <label>Schrijf een review... </label>
            <input type="text" id="review" name="review">
        </div>
        <div class="reviewRating">
            <button>5 sterren</button>
        </div>
    </div>
    <button class="reviewPostButton">Post</button>
</body>
</html>
