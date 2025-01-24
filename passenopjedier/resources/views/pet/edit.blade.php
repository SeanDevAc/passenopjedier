<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}" media="screen">
    <title>Bewerk Huisdier - PassenOpJeDier</title>
    <style>
        .editPetForm {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form-group textarea {
            min-height: 100px;
        }

        .currentImage {
            margin: 1rem 0;
        }

        .currentImage img {
            max-width: 150px;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .buttons {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .submitBtn {
            background: #4CAF50;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .cancelBtn {
            background: #f44336;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }

        .error {
            color: red;
            margin-top: 0.25rem;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <div class="generalWrapper">
        <div class="topNav">
            <h1>Bewerk Huisdier</h1>
            <a href="{{ route('home') }}">Terug naar Home</a>
        </div>

        <div class="editPetForm">
            <form method="POST" action="{{ route('pet.update', $pet->petid) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Naam</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $pet->name) }}" required>
                    @error('name')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="race">Ras</label>
                    <input type="text" id="race" name="race" value="{{ old('race', $pet->race) }}" required>
                    @error('race')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="bio">Beschrijving</label>
                    <textarea id="bio" name="bio">{{ old('bio', $pet->bio) }}</textarea>
                    @error('bio')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="image">Afbeelding</label>
                    @if($pet->image)
                        <div class="currentImage">
                            <p>Huidige afbeelding:</p>
                            <img src="data:image/jpeg;base64,{{ base64_encode(stream_get_contents($pet->image)) }}" alt="Huidige huisdier foto">
                        </div>
                    @endif
                    <input type="file" id="image" name="image">
                    @error('image')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="buttons">
                    <button type="submit" class="submitBtn">Opslaan</button>
                    <a href="{{ route('home') }}" class="cancelBtn">Annuleren</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
