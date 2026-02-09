@extends('admin.layouts.app')

@push('styles_top')
    <!-- Font Awesome 6.5.1 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .status-badge {
            font-size: 0.85rem;
            padding: 0.4rem 0.8rem;
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

        .info-card {
           
            transition: transform 0.2s;
        }

        .info-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .stat-box {
            text-align: center;
            padding: 1rem;
            border-radius: 8px;
            background-color: #fff;
            border: 1px solid #e0e0e0;
            transition: all 0.3s;
        }

        .stat-box:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }

        .stat-box h3 {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: #333;
        }

        .stat-box p {
            margin-bottom: 0;
            font-size: 0.85rem;
            color: #6c757d;
        }

        .stat-box .stat-icon {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .stat-box.primary .stat-icon {
            color: #007bff;
        }

        .stat-box.success .stat-icon {
            color: #28a745;
        }

        .stat-box.danger .stat-icon {
            color: #dc3545;
        }

        .stat-box.warning .stat-icon {
            color: #ffc107;
        }

        .email-content {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            min-height: 150px;
            max-height: 400px;
            overflow-y: auto;
            font-size: 0.95rem;
        }

        .recipient-badge {
            font-size: 0.8rem;
            padding: 0.3rem 0.6rem;
        }

        .recipient-sent {
            background-color: #d4edda;
            color: #155724;
        }

        .recipient-failed {
            background-color: #f8d7da;
            color: #721c24;
        }

        .recipient-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .timeline {
            position: relative;
            padding-left: 25px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 8px;
            top: 0;
            bottom: 0;
            width: 2px;
            background-color: #dee2e6;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 15px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -20px;
            top: 3px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #007bff;
            border: 2px solid #fff;
        }

        .back-button {
            transition: all 0.3s;
        }

        .back-button:hover {
            transform: translateX(-5px);
        }

        .recipients-table {
            font-size: 0.9rem;
        }

        .filter-buttons .btn {
            margin: 0.2rem;
            font-size: 0.85rem;
        }

        .progress-stats {
            margin-top: 1.5rem;
        }

        .progress-stats .stat-item {
            text-align: center;
            padding: 0.75rem;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
        }

        .progress-stats .stat-item i {
            font-size: 1.2rem;
            margin-bottom: 0.25rem;
        }

        .progress-stats .stat-item h5 {
            font-size: 1.2rem;
            margin-bottom: 0;
            font-weight: 600;
        }

        .progress-stats .stat-item small {
            font-size: 0.8rem;
            color: #6c757d;
        }

        .error-tooltip {
            cursor: pointer;
        }

        .card-header h5 {
            font-size: 1rem;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid mt-5">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="{{ route('notifications.history') }}" class="btn btn-outline-secondary btn-sm back-button mb-2">
                    <i class="fas fa-arrow-left me-2"></i>Retour à l'historique
                </a>
                <h2 class="mb-1">
                    Détails de l'Email
                </h2>
                <p class="text-muted mb-0">Email envoyé le {{ $sentEmail->created_at->format('d/m/Y à H:i') }}</p>
            </div>
            <div>
                @if($sentEmail->status === 'failed')
                    <form action="{{ route('notifications.retry', $sentEmail->id) }}" 
                          method="POST" 
                          class="d-inline"
                          onsubmit="return confirm('Voulez-vous vraiment renvoyer cet email ?')">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-sm">
                            <i class="fas fa-redo me-2"></i>Renvoyer
                        </button>
                    </form>
                @endif

                <form action="{{ route('notifications.destroy', $sentEmail->id) }}" 
                      method="POST" 
                      class="d-inline"
                      onsubmit="return confirm('Voulez-vous vraiment supprimer cet email ? Cette action est irréversible.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash me-2"></i>Supprimer
                    </button>
                </form>
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

        <!-- Statistiques principales -->
        <div class="row mb-4">
            <div class="col-md-3">
                
                <div class="stat-box primary">
                    <i class="fas fa-users stat-icon"></i>
                    <h3>{{ $sentEmail->total_recipients }}</h3>
                    <p>Total Destinataires</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box success">
                    <i class="fas fa-check-circle stat-icon"></i>
                    <h3>{{ $sentEmail->sent_count }}</h3>
                    <p>Envoyés avec Succès</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box danger">
                    <i class="fas fa-times-circle stat-icon"></i>
                    <h3>{{ $sentEmail->failed_count }}</h3>
                    <p>Échecs</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box warning">
                    <i class="fas fa-percentage stat-icon"></i>
                    <h3>{{ $sentEmail->success_rate }}%</h3>
                    <p>Taux de Réussite</p>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Colonne gauche : Informations générales -->
            <div class="col-md-8">
                <!-- Informations de l'email -->
                <div class="card info-card mb-3">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2 text-primary"></i>Informations Générales
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong><i class="fas fa-hashtag me-2 text-muted"></i>ID :</strong>
                                <span class="ms-2">{{ $sentEmail->id }}</span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong><i class="fas fa-user me-2 text-muted"></i>Envoyé par :</strong>
                                <span class="ms-2">{{ $sentEmail->user->full_name ?? 'Système' }}</span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong><i class="fas fa-tasks me-2 text-muted"></i>Statut :</strong>
                                @if($sentEmail->status === 'pending')
                                    <span class="badge status-badge status-pending ms-2">
                                        <i class="fas fa-clock me-1"></i>En attente
                                    </span>
                                @elseif($sentEmail->status === 'processing')
                                    <span class="badge status-badge status-processing ms-2">
                                        <i class="fas fa-spinner fa-spin me-1"></i>En cours
                                    </span>
                                @elseif($sentEmail->status === 'completed')
                                    <span class="badge status-badge status-completed ms-2">
                                        <i class="fas fa-check-circle me-1"></i>Terminé
                                    </span>
                                @else
                                    <span class="badge status-badge status-failed ms-2">
                                        <i class="fas fa-exclamation-circle me-1"></i>Échoué
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong><i class="fas fa-users-cog me-2 text-muted"></i>Type :</strong>
                                @if($sentEmail->recipient_type === 'users')
                                    <span class="badge bg-primary ms-2">
                                        <i class="fas fa-users me-1"></i>Utilisateurs Enregistrés
                                    </span>
                                @else
                                    <span class="badge bg-secondary ms-2">
                                        <i class="fas fa-at me-1"></i>Liste Personnalisée
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong><i class="fas fa-calendar-plus me-2 text-muted"></i>Date de création :</strong>
                                <span class="ms-2">{{ $sentEmail->created_at->format('d/m/Y H:i:s') }}</span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong><i class="fas fa-calendar-check me-2 text-muted"></i>Date d'envoi :</strong>
                                <span class="ms-2">
                                    {{ $sentEmail->sent_at ? $sentEmail->sent_at->format('d/m/Y H:i:s') : 'Non envoyé' }}
                                </span>
                            </div>
                        </div>

                        @if($sentEmail->excel_file_path)
                            <div class="alert alert-info mt-3 mb-0">
                                <i class="fas fa-file-excel me-2"></i>
                                <strong>Fichier Excel importé :</strong>
                                <a class="ms-2" href="{{ Storage::disk('laravel_public')->url($sentEmail->excel_file_path) }}" target="_blank">{{ basename($sentEmail->excel_file_path) }}</a>
                            </div>
                        @endif

                        @if($sentEmail->error_message)
                            <div class="alert alert-danger mt-3 mb-0">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Message d'erreur :</strong>
                                <p class="mb-0 mt-2">{{ $sentEmail->error_message }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sujet de l'email -->
                <div class="card info-card mb-3">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-heading me-2 text-info"></i>Sujet
                        </h5>
                    </div>
                    <div class="card-body">
                        <h5 class="mb-0">{{ $sentEmail->subject }}</h5>
                    </div>
                </div>

                <!-- Contenu de l'email -->
                <div class="card info-card mb-3">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-file-alt me-2 text-success"></i>Contenu de l'Email
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="email-content">
                            {!! $sentEmail->content !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne droite : Timeline et progression -->
            <div class="col-md-4">
                <!-- Progression -->
                <div class="card info-card mb-3">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-pie me-2 text-primary"></i>Progression
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="progress mb-3" style="height: 25px;">
                            @php
                                $percentage = $sentEmail->total_recipients > 0 
                                    ? round(($sentEmail->sent_count / $sentEmail->total_recipients) * 100) 
                                    : 0;
                            @endphp
                            <div class="progress-bar bg-success" 
                                 role="progressbar" 
                                 style="width: {{ $percentage }}%"
                                 aria-valuenow="{{ $percentage }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                                <strong>{{ $percentage }}%</strong>
                            </div>
                        </div>
                        
                        <div class="row progress-stats">
                            <div class="col-4">
                                <div class="stat-item">
                                    <i class="fas fa-paper-plane text-success"></i>
                                    <h5 class="mt-1">{{ $sentEmail->sent_count }}</h5>
                                    {{-- <small>Envoyés</small> --}}
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-item">
                                    <i class="fas fa-exclamation-triangle text-danger"></i>
                                    <h5 class="mt-1">{{ $sentEmail->failed_count }}</h5>
                                    {{-- <small>Échoués</small> --}}
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-item">
                                    <i class="fas fa-clock text-warning"></i>
                                    <h5 class="mt-1">
                                        {{ $sentEmail->total_recipients - $sentEmail->sent_count - $sentEmail->failed_count }}
                                    </h5>
                                    {{-- <small>En attente</small> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="card info-card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-history me-2 text-secondary"></i>Historique
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item">
                                <strong>Créé</strong>
                                <p class="text-muted mb-0">
                                    <small>{{ $sentEmail->created_at->format('d/m/Y à H:i:s') }}</small>
                                </p>
                            </div>

                            @if($sentEmail->sent_at)
                                <div class="timeline-item">
                                    <strong>Envoi démarré</strong>
                                    <p class="text-muted mb-0">
                                        <small>{{ $sentEmail->sent_at->format('d/m/Y à H:i:s') }}</small>
                                    </p>
                                </div>
                            @endif

                            @if($sentEmail->status === 'completed')
                                <div class="timeline-item">
                                    <strong>Terminé</strong>
                                    <p class="text-muted mb-0">
                                        <small>{{ $sentEmail->updated_at->format('d/m/Y à H:i:s') }}</small>
                                    </p>
                                </div>
                            @endif

                            @if($sentEmail->status === 'failed')
                                <div class="timeline-item">
                                    <strong class="text-danger">Échoué</strong>
                                    <p class="text-muted mb-0">
                                        <small>{{ $sentEmail->updated_at->format('d/m/Y à H:i:s') }}</small>
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des destinataires (Pleine largeur) -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="card info-card">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="mb-0">
                            <i class="fas fa-address-book me-2"></i>
                            Destinataires ({{ $sentEmail->emailRecipients->count() }})
                        </h5>
                        <div class="filter-buttons">
                            <button class="btn btn-sm btn-outline-secondary filter-btn active" data-filter="all">
                                Tous
                            </button>
                            <button class="btn btn-sm btn-outline-success filter-btn" data-filter="sent">
                                Envoyés
                            </button>
                            <button class="btn btn-sm btn-outline-danger filter-btn" data-filter="failed">
                                Échoués
                            </button>
                            <button class="btn btn-sm btn-outline-warning filter-btn" data-filter="pending">
                                En attente
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if($sentEmail->emailRecipients->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped recipients-table mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="40%">Email</th>
                                            <th width="15%">Statut</th>
                                            <th width="20%">Date d'envoi</th>
                                            <th width="20%">Détails</th>
                                        </tr>
                                    </thead>
                                    <tbody id="recipientsTableBody">
                                        @foreach($sentEmail->emailRecipients as $index => $recipient)
                                            <tr class="recipient-row" data-status="{{ $recipient->status }}">
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <i class="fas fa-envelope me-2 text-muted"></i>
                                                    {{ $recipient->email }}
                                                </td>
                                                <td>
                                                    @if($recipient->status === 'sent')
                                                        <span class="badge recipient-badge recipient-sent">
                                                            <i class="fas fa-check me-1"></i>Envoyé
                                                        </span>
                                                    @elseif($recipient->status === 'failed')
                                                        <span class="badge recipient-badge recipient-failed">
                                                            <i class="fas fa-times me-1"></i>Échoué
                                                        </span>
                                                    @else
                                                        <span class="badge recipient-badge recipient-pending">
                                                            <i class="fas fa-clock me-1"></i>En attente
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($recipient->sent_at)
                                                        <small>
                                                            <i class="fas fa-calendar me-1 text-muted"></i>
                                                            {{ $recipient->sent_at->format('d/m/Y H:i:s') }}
                                                        </small>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($recipient->error_message)
                                                        <button type="button"
                                                                class="btn btn-sm btn-outline-danger error-tooltip" 
                                                                data-bs-toggle="tooltip" 
                                                                data-bs-placement="left"
                                                                title="{{ $recipient->error_message }}">
                                                            <i class="fas fa-exclamation-circle"></i> Voir l'erreur
                                                        </button>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucun destinataire trouvé</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts_bottom')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialiser les tooltips Bootstrap
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Filtrage des destinataires
            const filterButtons = document.querySelectorAll('.filter-btn');
            const recipientRows = document.querySelectorAll('.recipient-row');

            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const filter = this.getAttribute('data-filter');

                    // Mettre à jour les boutons actifs
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');

                    // Filtrer les lignes
                    recipientRows.forEach(row => {
                        const status = row.getAttribute('data-status');
                        
                        if (filter === 'all' || status === filter) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            });
        });

        // Auto-refresh si le statut est "processing"
        @if($sentEmail->status === 'processing')
            setTimeout(function() {
                location.reload();
            }, 15000); // Rafraîchir toutes les 15 secondes
        @endif
    </script>
@endpush