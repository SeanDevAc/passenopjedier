<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <a href='/'>back</a>
    Login

    @foreach($human as $h)
        <p> Naam: {{ $h->name }} </p>
    @endforeach

    <form method="POST" action="{{ route('update.name') }}">
        @csrf
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="name">Nieuwe naam:</label>
        <input type="text" id="name" name="name" required>

        <button type="submit">Werk naam bij</button>
    </form>

    <!-- Succes- en foutberichten tonen -->
    @if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
    @endif

    @if(session('error'))
    <p style="color: red;">{{ session('error') }}</p>
    @endif


</body>
</html>
