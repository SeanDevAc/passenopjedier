<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Dashboard - PassenOpJeDier</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-left">
                <a href="/" class="nav-logo">PassenOpJeDier</a>
            </div>
            <div class="nav-right">
                <a href="/" class="nav-link">Home</a>
                @if(Auth::user()->is_admin)
                    <a href="{{ route('admin.dashboard') }}" class="nav-link active">Admin</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="inline-form">
                    @csrf
                    <button type="submit" class="nav-link logout-btn">Uitloggen</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="admin-dashboard">
        <div class="admin-section">
            <div class="admin-section-header">
                <h2>Gebruikers Beheer</h2>
            </div>
            <div class="list-container">
                @foreach($users as $user)
                    <div class="list-item">
                        <div class="list-content">
                            <div class="list-header">
                                <h3>{{ $user->name }}</h3>
                                <div class="badge-container">
                                    <span class="badge {{ $user->is_admin ? 'badge-admin' : 'badge-user' }}">
                                        {{ $user->is_admin ? 'Admin' : 'Gebruiker' }}
                                    </span>
                                    <span class="badge {{ $user->is_banned ? 'badge-banned' : 'badge-active' }}">
                                        {{ $user->is_banned ? 'Geblokkeerd' : 'Actief' }}
                                    </span>
                                </div>
                            </div>
                            <div class="list-details">
                                <p><strong>Email:</strong> {{ $user->email }}</p>
                                {{-- <p><strong>Lid sinds:</strong> {{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}</p> --}}
                            </div>
                        </div>
                        <div class="list-actions">
                            <form method="POST" action="{{ route('admin.users.toggle-admin', $user->id) }}" class="inline-form">
                                @csrf
                                @method('PUT')
                                <button class="admin-btn {{ $user->is_admin ? 'admin-btn-warning' : 'admin-btn-success' }}">
                                    {{ $user->is_admin ? 'Verwijder Admin' : 'Maak Admin' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.users.ban', $user->id) }}" class="inline-form">
                                @csrf
                                @method('PUT')
                                <button class="admin-btn {{ $user->is_banned ? 'admin-btn-success' : 'admin-btn-warning' }}">
                                    {{ $user->is_banned ? 'Deblokkeer' : 'Blokkeer' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.users.delete', $user->id) }}" class="inline-form">
                                @csrf
                                @method('DELETE')
                                <button class="admin-btn admin-btn-danger" onclick="return confirm('Weet je zeker dat je deze gebruiker wilt verwijderen?')">
                                    Verwijder
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="admin-section">
            <div class="admin-section-header">
                <h2>Reviews Beheer</h2>
            </div>
            <div class="list-container">
                @foreach($reviews as $review)
                    <div class="list-item">
                        <div class="list-content">
                            <div class="list-header">
                                <h3>Review van {{ $review->owner_name }}</h3>
                                <span class="badge badge-rating">{{ $review->rating }} / 5</span>
                            </div>
                            <div class="list-details">
                                <p><strong>Voor:</strong> {{ $review->sitter_name }}</p>
                                <p><strong>Beoordeling:</strong> {{ $review->rating }}/5</p>
                                <p><strong>Commentaar:</strong> {{ $review->description }}</p>
                                <p><strong>Datum:</strong> {{ \Carbon\Carbon::parse($review->placedat)->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        <div class="list-actions">
                            <form method="POST" action="{{ route('admin.reviews.delete', $review->reviewid) }}" class="inline-form">
                                @csrf
                                @method('DELETE')
                                <button class="admin-btn admin-btn-danger" onclick="return confirm('Weet je zeker dat je deze review wilt verwijderen?')">
                                    Verwijder
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Vacatures Beheer -->
        <div class="admin-section">
            <div class="admin-section-header">
                <h2>Vacatures Beheer</h2>
            </div>
            <div class="list-container">
                @foreach($vacancies as $vacancy)
                    <div class="list-item">
                        <div class="list-content">
                            <div class="list-header">
                                <h3>{{ $vacancy->owner_name }}'s Vacature</h3>
                                <span class="badge badge-price">€{{ $vacancy->rate }}/uur</span>
                            </div>
                            <div class="list-details">
                                <p><strong>Huisdier:</strong> {{ $vacancy->pet_name ?? 'Geen huisdier' }}</p>
                                <p><strong>Oppasser:</strong> {{ $vacancy->sitter_name ?? 'Nog geen oppasser' }}</p>
                                <p><strong>Datum:</strong> {{ \Carbon\Carbon::parse($vacancy->datetime)->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="list-actions">
                            <form method="POST" action="{{ route('admin.vacancies.delete', $vacancy->vacancyid) }}" class="inline-form">
                                @csrf
                                @method('DELETE')
                                <button class="admin-btn admin-btn-danger" onclick="return confirm('Weet je zeker dat je deze vacature wilt verwijderen?')">
                                    Verwijder
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Sollicitaties Beheer -->
        <div class="admin-section">
            <div class="admin-section-header">
                <h2>Sollicitaties Beheer</h2>
            </div>
            <div class="list-container">
                @foreach($applications as $application)
                    <div class="list-item">
                        <div class="list-content">
                            <div class="list-header">
                                <h3>Sollicitatie van {{ $application->applicant_name }}</h3>
                                <span class="badge badge-price">€{{ $application->rate }}/uur</span>
                            </div>
                            <div class="list-details">
                                <p><strong>Eigenaar:</strong> {{ $application->owner_name }}</p>
                                <p><strong>Datum:</strong> {{ \Carbon\Carbon::parse($application->datetime)->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="list-actions">
                            <form method="POST" action="{{ route('admin.applications.delete', $application->applicationid) }}" class="inline-form">
                                @csrf
                                @method('DELETE')
                                <button class="admin-btn admin-btn-danger" onclick="return confirm('Weet je zeker dat je deze sollicitatie wilt verwijderen?')">
                                    Verwijder
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navButtons = document.querySelectorAll('.adminNavButton');
            const cards = document.querySelectorAll('.adminCard');

            navButtons.forEach(button => {
                button.addEventListener('click', () => {
                    // Remove active class from all buttons and hide all cards
                    navButtons.forEach(btn => btn.classList.remove('active'));
                    cards.forEach(card => card.classList.add('hidden'));

                    // Add active class to clicked button and show corresponding card
                    button.classList.add('active');
                    document.getElementById(button.dataset.target).classList.remove('hidden');
                });
            });
        });
    </script>
</body>
</html>
