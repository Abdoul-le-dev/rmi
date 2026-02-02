@extends('admin.layouts.app')

@push('styles_top')
    <!-- Font Awesome 6.5.1 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@section('content')
    <div class="container-fluid py-4">
        <!-- Partie 1: Cards Statistiques -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Sessions Totales</h6>
                                <h3 class="mb-0">{{ $totalSessions ?? 0 }}</h3>
                            </div>
                            <div class="bg-primary bg-opacity-10 p-3 rounded">
                                <i class="fas fa-video text-white fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">En attente</h6>
                                <h3 class="mb-0">{{ $liveSessions ?? 0 }}</h3>
                            </div>
                            <div class="bg-success bg-opacity-10 p-3 rounded">
                                <i class="fas fa-broadcast-tower text-white fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">A venir</h6>
                                <h3 class="mb-0">{{ $scheduledSessions ?? 0 }}</h3>
                            </div>
                            <div class="bg-warning bg-opacity-10 p-3 rounded">
                                <i class="fas fa-clock text-white fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Terminées</h6>
                                <h3 class="mb-0">{{ $completedSessions ?? 0 }}</h3>
                            </div>
                            <div class="bg-info bg-opacity-10 p-3 rounded">
                                <i class="fas fa-check-circle text-white fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">

                <span>  {{ session('success') }}</span>


            </div>
        @endif

        <!-- Partie 2: Filtres -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Filtres de recherche</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('sessions.index') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="instructor" class="form-label">Instructeur</label>
                            <select class="form-control" id="instructor" name="instructor_id">
                                <option value="">Tous les instructeurs</option>
                                @foreach ($instructors ?? [] as $instructor)
                                    <option value="{{ $instructor->id }}"
                                        {{ request('instructor_id') == $instructor->id ? 'selected' : '' }}>
                                        {{ $instructor->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="status" class="form-label">Statut</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">Tous les statuts</option>
                                <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>A venir
                                </option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente
                                </option>
                                <option value="live" {{ request('status') == 'live' ? 'selected' : '' }}>En direct
                                </option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminée
                                </option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulée
                                </option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="is_public" class="form-label">Visibilité</label>
                            <select class="form-control" id="is_public" name="is_public">
                                <option value="">Toutes</option>
                                <option value="1" {{ request('is_public') == '1' ? 'selected' : '' }}>Publique
                                </option>
                                <option value="0" {{ request('is_public') == '0' ? 'selected' : '' }}>Privée</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="room_name" class="form-label">Salle</label>
                            <input type="text" class="form-control" id="room_name" name="room_name"
                                placeholder="Nom de la salle" value="{{ request('room_name') }}">
                        </div>

                        <div class="col-md-3">
                            <label for="date_from" class="form-label">Date de début</label>
                            <input type="date" class="form-control" id="date_from" name="date_from"
                                value="{{ request('date_from') }}">
                        </div>

                        <div class="col-md-3">
                            <label for="date_to" class="form-label">Date de fin</label>
                            <input type="date" class="form-control" id="date_to" name="date_to"
                                value="{{ request('date_to') }}">
                        </div>



                        <div class="col-md-3">
                            <label for="search" class="form-label">Recherche</label>
                            <input type="text" class="form-control" id="search" name="search"
                                placeholder="Titre ou description..." value="{{ request('search') }}">
                        </div>

                        <div class="col-12 mt-3">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-search me-2"></i>Afficher les résultats
                            </button>
                            <a href="{{ route('sessions.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-redo me-2"></i>Réinitialiser
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Partie 3: Tableau -->
        <div class="card border-0 shadow-sm">

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>

                                <th>Titre</th>
                                <th>Instructeur</th>
                                <th>Salle</th>
                                <th>Date</th>
                                <th>Durée</th>
                                <th>Participants</th>
                                <th>Statut</th>
                                <th>Visibilité</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sessions ?? [] as $session)
                                <tr>

                                    <td>
                                        <div class="d-flex align-items-center">

                                            <div>
                                                <div class="fw-semibold">{{ $session->title }}</div>

                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $session->instructor->full_name ?? 'N/A' }}</td>
                                    <td>
                                        <span>{{ $session->room_name }}</span>
                                    </td>
                                    <td>
                                        @if ($session->scheduled_at)
                                            {{ \Carbon\Carbon::parse($session->scheduled_at)->format('d/m/Y H:i') }}
                                        @else
                                            <span class="text-muted">Non planifiée</span>
                                        @endif
                                    </td>
                                    <td>{{ $session->duration_minutes }} min</td>
                                    <td>
                                        @if ($session->max_participants)
                                            <span>Max: {{ $session->max_participants }}</span>
                                        @else
                                            <span class="text-muted">Illimité</span>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($session->status)
                                            @case('live')
                                                <span class="badge bg-success text-white">
                                                    En direct
                                                </span>
                                            @break

                                            @case('scheduled')
                                                <span class="badge bg-warning text-white">A venir</span>
                                            @break

                                            @case('completed')
                                                <span class="badge bg-info text-white">Terminée</span>
                                            @break

                                            @case('cancelled')
                                                <span class="badge bg-danger text-white">Annulée</span>
                                            @break

                                            @case('ended')
                                                <span class="badge bg-danger text-white">Terminée</span>
                                            @break

                                            @case('pending')
                                                <span class="badge bg-warning text-white">En attente</span>
                                            @break

                                            @default
                                                <span class="badge bg-secondary text-white">{{ $session->status }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        @if ($session->is_public)
                                            <span class="badge bg-success text-white">
                                                Public
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-white">
                                                Privé
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">

                                            <a href="{{ route('sessions.edit', $session->id) }}"
                                                class="btn btn-outline-secondary" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('sessions.destroy', $session->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette session ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                <p class="mb-0">Aucune session trouvée</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if (isset($sessions) && $sessions->hasPages())
                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Affichage de {{ $sessions->firstItem() }} à {{ $sessions->lastItem() }} sur
                                {{ $sessions->total() }} résultats
                            </div>
                            {{ $sessions->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endsection

    @push('scripts_bottom')
    @endpush
