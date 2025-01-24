<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}" media="screen">
    <title>Home - PassenOpJeDier</title>
    <style>
        .profileImageContainer {
            width: 125px;  /* was 250px */
            height: 125px;  /* was 250px */
            overflow: hidden;
            border-radius: 8px;
            margin-right: 20px;
        }

        .petProfileImage {
            width: 100%;
            height: 100%;
            object-fit: cover;  /* Dit zorgt ervoor dat de afbeelding mooi wordt geschaald */
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .petInfoCard {
            display: flex;
            padding: 20px;
            background: white;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .profileSection {
            display: flex;
            align-items: flex-start;
            width: 100%;
        }

        .petInfo {
            flex-grow: 1;
            padding: 0 20px;
        }
    </style>
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
            <p>Welkom {{ Auth::user()->name }}</p>
            <div>
                @if($user->is_admin)
                    <a href="{{ route('admin.dashboard') }}" class="iconButton" title="Admin Dashboard">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit">Uitloggen</button>
                </form>
            </div>
        </div>

        <!-- User Profile Section -->
        <div class="userInfoCard">
            <div class="profileSection">
                <div class="profileImageContainer">
                    @if($user && $user->profileimage)
                        @php
                            try {
                                $imageData = stream_get_contents($user->profileimage);
                                if ($imageData !== false) {
                                    $base64Image = base64_encode($imageData);
                                    echo '<img src="data:image/jpeg;base64,' . $base64Image . '" alt="Profile" class="userProfileImage">';
                                }
                            } catch (Exception $e) {
                                // Log error en toon default image
                                \Log::error('Error loading profile image: ' . $e->getMessage());
                            }
                        @endphp
                    @else
                        <img src="{{ asset('img/profile-placeholder.jpg') }}" alt="Default Profile" class="userProfileImage">
                    @endif
                </div>
                <div class="userInfo">
                    <h2>{{ $user->name }}</h2>
                    <p>{{ $user->bio }}</p>
                    <div class="userActions">
                        <a href="{{ route('settings') }}" class="editBtn">Bewerk Profiel</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jouw Huisdieren Section -->
        @if($user->haspet)
            <div class="section">
                <div class="sectionHeader">
                    <h2>Jouw Huisdieren</h2>
                    <button class="createButton">
                        <a href="{{ route('pet.create') }}">Nieuw Huisdier</a>
                    </button>
                </div>

                @if(isset($pets) && $pets->count() > 0)
                    @foreach($pets as $pet)
                        <div class="petInfoCard">
                            <div class="profileSection">
                                <div class="profileImageContainer">
                                    @if($pet->image)
                                        <img src="data:image/jpeg;base64,{{ base64_encode(stream_get_contents($pet->image)) }}" alt="Pet" class="petProfileImage">
                                    @endif
                                </div>
                                <div class="petInfo">
                                    <h2>{{ $pet->name }}</h2>
                                    <p>{{ $pet->race }}</p>
                                    <p>{{ $pet->bio }}</p>
                                </div>
                                <div class="petActions">
                                    <a href="{{ route('pet.edit', $pet->petid) }}" class="editBtn">Bewerk</a>
                                    <form method="POST" action="{{ route('pet.destroy', $pet->petid) }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="deleteBtn" onclick="return confirm('Weet je zeker dat je dit huisdier wilt verwijderen?')">Verwijder</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="emptyMessage">Je hebt nog geen huisdieren toegevoegd</p>
                @endif
            </div>
        @endif

        <div class="section">
            <h2>Ontvangen Sollicitaties</h2>
            @if(isset($applications) && $applications->count() > 0)
                <div class="cardGrid">
                    @foreach($applications as $application)
                        <div class="card">
                            <div class="cardHeader">
                                <h3>{{ $application->applicant_name }}</h3>
                                <span class="rate">€{{ $application->rate }}/uur</span>
                            </div>
                            <div class="cardContent">
                                <p><strong>Huisdier:</strong> {{ $application->pet_name }}</p>
                                <p><strong>Datum:</strong> {{ \Carbon\Carbon::parse($application->datetime)->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="cardActions">
                                <a href="/profile/{{ $application->applicantid }}" class="viewBtn">Bekijk profiel</a>
                                <form method="POST" action="{{ route('applications.accept', $application->applicationid) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="acceptBtn">Accepteer</button>
                                </form>
                                <form method="POST" action="{{ route('applications.reject', $application->applicationid) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="rejectBtn">Weiger</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="emptyMessage">Geen sollicitaties ontvangen</p>
            @endif
        </div>

        <!-- Eigen Open Vacatures Section -->
        <div class="section">
            <div class="sectionHeader">
                <h2>Jouw Open Vacatures</h2>
                <button class="createButton"><a href="{{ route('vacancy.create') }}">Nieuwe Vacature</a></button>
            </div>
            @if($ownVacancies->count() > 0)
                <div class="cardGrid">
                    @foreach($ownVacancies as $vacancy)
                        <div class="card">
                            <div class="cardHeader">
                                <h3>{{ $vacancy->pet_name }}</h3>
                                <span class="rate">€{{ $vacancy->rate }}/uur</span>
                            </div>
                            <div class="cardContent">
                                <p><strong>Datum:</strong> {{ \Carbon\Carbon::parse($vacancy->datetime)->format('d/m/Y H:i') }}</p>
                                <p><strong>Duur:</strong> {{ $vacancy->duration }} uur</p>
                                <p class="description">{{ $vacancy->description }}</p>
                            </div>
                            <div class="cardActions">
                                <a href="{{ route('vacancy.edit', $vacancy->vacancyid) }}" class="editBtn">Bewerk</a>
                                <form method="POST" action="{{ route('vacancy.destroy', $vacancy->vacancyid) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="deleteBtn" onclick="return confirm('Weet je zeker dat je deze vacature wilt verwijderen?')">Verwijder</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="emptyMessage">Je hebt nog geen open vacatures</p>
            @endif
        </div>

        <!-- Gemaakte Afspraken Section -->
        <div class="section">
            <h2>Gemaakte Afspraken</h2>
            @if($appointments->count() > 0)
                <div class="cardGrid">
                    @foreach($appointments as $appointment)
                        <div class="card">
                            <div class="cardHeader">
                                {{-- <h3>{{ $appointment->pet_name }}</h3> --}}
                                <span class="rate">€{{ $appointment->rate }}/uur</span>
                            </div>
                            <div class="cardContent">
                                <p><strong>Oppasser:</strong> {{ $appointment->sitter_name }}</p>
                                <p><strong>Datum:</strong> {{ \Carbon\Carbon::parse($appointment->datetime)->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="cardActions">
                                <a href="/profile/{{ $appointment->sitterid }}" class="viewBtn">Bekijk profiel</a>
                                <form method="POST" action="{{ route('appointments.cancel', $appointment->vacancyid) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="cancelBtn">Annuleer afspraak</button>
                                </form>
                                <a href="{{ route('review.write', ['sitter' => $appointment->sitterid]) }}" class="reviewBtn">Review schrijven</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="emptyMessage">Geen afspraken gevonden</p>
            @endif
        </div>

        <!-- Mijn Sollicitaties Section -->
        <div class="section">
            <h2>Mijn Sollicitaties</h2>
            @if(isset($myApplications) && $myApplications->count() > 0)
                <div class="cardGrid">
                    @foreach($myApplications as $application)
                        <div class="card">
                            <div class="cardHeader">
                                <h3>{{ $application->pet_name }}</h3>
                                <span class="rate">€{{ $application->rate }}/uur</span>
                            </div>
                            <div class="cardContent">
                                <p><strong>Eigenaar:</strong> {{ $application->owner_name }}</p>
                                <p><strong>Datum:</strong> {{ \Carbon\Carbon::parse($application->datetime)->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="cardActions">
                                <a href="/profile/{{ $application->ownerid }}" class="viewBtn">Bekijk eigenaar</a>
                                <form method="POST" action="{{ route('applications.withdraw', $application->applicationid) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="deleteBtn" onclick="return confirm('Weet je zeker dat je deze sollicitatie wilt intrekken?')">Verwijderen</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="emptyMessage">Je hebt nog geen sollicitaties gedaan</p>
            @endif
        </div>

        <h2>Open vacatures</h2>
        <!-- Vacancies Section -->
        <div class="vacancyFilterSection">
            <form action="{{ route('home.filter') }}" method="GET">
                <div class="form-group">
                    <input type="text" name="name" placeholder="Zoek op naam" class="form-control" value="{{ request('name') }}">
                </div>
                <div class="form-group">
                    <input type="number" name="price" placeholder="Minimale prijs" class="form-control" value="{{ request('price') }}" min="0" step="0.01">
                </div>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>

        @if(isset($vacancys) && $vacancys->count() > 0)
            @foreach($vacancys as $vacancy)
                <div class="vacancyCards">
                    <div class="vacancyCardTitle">
                        <div class="vacancyInfo">
                            <p>Naam: {{ $vacancy->owner_name }}</p>
                            {{-- <p>Huisdier: {{ $vacancy->name }}</p> --}}
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

    </div>
</body>
</html>
