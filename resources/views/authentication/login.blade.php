<!DOCTYPE html>
<html lang="en">
  <body>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width , initial-scale=1.0">
        <meta https-equiv="X-UA-Compatible" content="ie=edge">
        <title>My First Webpage</title>
    </head>
        <h1> Login page </h1>
        <div> 
            <a href='{{ route('authentication.register') }}'> Ik wil registreren
            <a href='/attendant-homepage/1'> Inloggen als oppasser
            <a href='/consumer-homepage/1'> Inloggen als consument
        </div>
  </body>
</html>