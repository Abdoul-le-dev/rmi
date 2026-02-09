@extends('admin.layouts.app')

@push('styles_top')
    <!-- Font Awesome 6.5.1 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .status-badge {
            font-size: 0.85rem;
            padding: 0.35rem 0.75rem;
            font-weight: 500;
        }

        .status-pending {
            background-color: #ffc107;
            color: #000;
        }

        .status-processing {
            background-color: #17a2b8;
            color: #fff;
        }

        .status-completed {
            background-color: #28a745;
            color: #fff;
        }

        .status-failed {
            background-color: #dc3545;
            color: #fff;
        }

        .stats-card {
            border-left: 4px solid;
            transition: transform 0.2s;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .stats-card.success {
            border-left-color: #28a745;
        }

        .stats-card.warning {
            border-left-color: #ffc107;
        }

        .stats-card.danger {
            border-left-color: #dc3545;
        }

        .stats-card.info {
            border-left-color: #17a2b8;
        }

        .progress-thin {
            height: 5px;
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }

        .filter-card {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .action-buttons .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .email-preview {
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .recipient-type-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid mt-5">
        <!-- Header -->
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1">
                   Historique des Emails
                </h2>
                <p class="mb-0 text-muted">Consultez l'historique de vos campagnes d'emails</p>
            </div>
            <div>
                <a href="{{ route('notifications.index') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nouvel Email
                </a>
            </div>
        </div>

        <!-- Messages Flash -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Statistiques -->
        <div class="mb-4 row">
            <div class="col-md-3">
                <div class="card stats-card success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1 text-muted">Total Envoyés</p>
                                <h3 class="mb-0">{{ $sentEmails->sum('sent_count') }}</h3>
                            </div>
                            <div class="text-success">
                                <i class="fas fa-paper-plane fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stats-card danger">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1 text-muted">Échecs</p>
                                <h3 class="mb-0">{{ $sentEmails->sum('failed_count') }}</h3>
                            </div>
                            <div class="text-danger">
                                <i class="fas fa-exclamation-triangle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stats-card info">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1 text-muted">Campagnes</p>
                                <h3 class="mb-0">{{ $sentEmails->total() }}</h3>
                            </div>
                            <div class="text-info">
                                <i class="fas fa-envelope-open-text fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stats-card warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1 text-muted">Taux de Réussite</p>
                                <h3 class="mb-0">
                                    @php
                                        $totalSent = $sentEmails->sum('sent_count');
                                        $totalRecipients = $sentEmails->sum('total_recipients');
                                        $successRate = $totalRecipients > 0 ? round(($totalSent / $totalRecipients) * 100, 1) : 0;
                                    @endphp
                                    {{ $successRate }}%
                                </h3>
                            </div>
                            <div class="text-warning">
                                <i class="fas fa-chart-line fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="card filter-card">
            <form method="GET" action="{{ route('notifications.history') }}" id="filterForm">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label for="status" class="form-label fw-bold">
                            <i class="fas fa-filter me-1"></i>Statut
                        </label>
                        <select name="status" id="status" class="form-control">
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Tous les statuts</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>En cours</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminé</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Échoué</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="date_from" class="form-label fw-bold">
                            <i class="fas fa-calendar-alt me-1"></i>Date début
                        </label>
                        <input type="date" name="date_from" id="date_from" class="form-control" 
                               value="{{ request('date_from') }}">
                    </div>

                    <div class="col-md-3">
                        <label for="date_to" class="form-label fw-bold">
                            <i class="fas fa-calendar-alt me-1"></i>Date fin
                        </label>
                        <input type="date" name="date_to" id="date_to" class="form-control" 
                               value="{{ request('date_to') }}">
                    </div>

                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Filtrer
                        </button>
                    </div>
                </div>

                @if(request()->hasAny(['status', 'date_from', 'date_to']))
                    <div class="mt-3 row">
                        <div class="col-12">
                            <a href="{{ route('notifications.history') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>Réinitialiser les filtres
                            </a>
                        </div>
                    </div>
                @endif
            </form>
        </div>

        <!-- Tableau des emails -->
        <div class="card">
            <div class="bg-white card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Liste des Emails Envoyés
                </h5>
            </div>
            <div class="p-0 card-body">
                @if($sentEmails->count() > 0)
                    <div class="table-responsive">
                        <table class="table mb-0 table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="20%">Sujet</th>
                                    <th width="15%">Type</th>
                                    <th width="10%">Destinataires</th>
                                    <th width="12%">Statut</th>
                                    <th width="10%">Progression</th>
                                    <th width="15%">Date</th>
                                    <th width="13%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sentEmails as $email)
                                    <tr onclick="window.location='{{ route('notifications.show', $email->id) }}'">
                                        <td>{{ $email->id }}</td>
                                        <td>
                                            <div class="email-preview fw-bold" title="{{ $email->subject }}">
                                                {{ $email->subject }}
                                            </div>
                                        </td>
                                        <td>
                                            @if($email->recipient_type === 'users')
                                                <span class="badge bg-primary recipient-type-badge">
                                                    <i class="fas fa-users me-1"></i>Utilisateurs
                                                </span>
                                            @else
                                                <span class="badge bg-secondary recipient-type-badge">
                                                    <i class="fas fa-at me-1"></i>Personnalisé
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $email->total_recipients }}</strong>
                                            <small class="text-muted d-block">
                                                <i class="fas fa-check text-success"></i> {{ $email->sent_count }}
                                                <i class="fas fa-times text-danger ms-2"></i> {{ $email->failed_count }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($email->status === 'pending')
                                                <span class="badge status-badge status-pending">
                                                    <i class="fas fa-clock me-1"></i>En attente
                                                </span>
                                            @elseif($email->status === 'processing')
                                                <span class="badge status-badge status-processing">
                                                    <i class="fas fa-spinner fa-spin me-1"></i>En cours
                                                </span>
                                            @elseif($email->status === 'completed')
                                                <span class="badge status-badge status-completed">
                                                    <i class="fas fa-check-circle me-1"></i>Terminé
                                                </span>
                                            @else
                                                <span class="badge status-badge status-failed">
                                                    <i class="fas fa-exclamation-circle me-1"></i>Échoué
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $percentage = $email->total_recipients > 0 
                                                    ? round(($email->sent_count / $email->total_recipients) * 100) 
                                                    : 0;
                                            @endphp
                                            <div class="progress progress-thin">
                                                <div class="progress-bar 
                                                    @if($percentage === 100) bg-success
                                                    @elseif($percentage > 50) bg-info
                                                    @else bg-warning
                                                    @endif" 
                                                    role="progressbar" 
                                                    style="width: {{ $percentage }}%"
                                                    aria-valuenow="{{ $percentage }}" 
                                                    aria-valuemin="0" 
                                                    aria-valuemax="100">
                                                </div>
                                            </div>
                                            <small class="text-muted">{{ $percentage }}%</small>
                                        </td>
                                        <td>
                                            <small>
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $email->created_at->format('d/m/Y') }}
                                                <br>
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $email->created_at->format('H:i') }}
                                            </small>
                                        </td>
                                        <td onclick="event.stopPropagation();">
                                            <div class="action-buttons">
                                                <a href="{{ route('notifications.show', $email->id) }}" 
                                                   class="btn btn-sm btn-info" 
                                                   title="Voir les détails">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                @if($email->status === 'failed')
                                                    <form action="{{ route('notifications.retry', $email->id) }}" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('Voulez-vous vraiment renvoyer cet email ?')">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-warning" 
                                                                title="Renvoyer">
                                                            <i class="fas fa-redo"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                <form action="{{ route('notifications.destroy', $email->id) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Voulez-vous vraiment supprimer cet email ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-danger" 
                                                            title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="bg-white card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                Affichage de {{ $sentEmails->firstItem() ?? 0 }} à {{ $sentEmails->lastItem() ?? 0 }} 
                                sur {{ $sentEmails->total() }} résultats
                            </div>
                            <div>
                                {{ $sentEmails->links() }}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="py-5 text-center">
                        <i class="mb-3 fas fa-inbox fa-4x text-muted"></i>
                        <h5 class="text-muted">Aucun email trouvé</h5>
                        <p class="text-muted">
                            @if(request()->hasAny(['status', 'date_from', 'date_to']))
                                Aucun résultat ne correspond à vos critères de recherche.
                            @else
                                Vous n'avez pas encore envoyé d'email.
                            @endif
                        </p>
                        <a href="{{ route('notifications.index') }}" class="mt-3 btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Envoyer un email
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts_bottom')
    <script>
        // Auto-submit du formulaire lors du changement de statut
        document.getElementById('status').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });

        // Rafraîchir la page toutes les 30 secondes si des emails sont en cours de traitement
        @if($sentEmails->where('status', 'processing')->count() > 0)
            setTimeout(function() {
                location.reload();
            }, 30000);
        @endif
    </script>
@endpush