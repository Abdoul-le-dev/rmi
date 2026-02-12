@extends('admin.layouts.app')
@section('title', 'Détails du rendez-vous #' . $appointment->id)
@push('styles_top')
    <!-- Font Awesome 6.5.1 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .badge-lg {
            font-size: 1rem;
            padding: 8px 15px;
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid py-4 mt-5">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-2">Rendez-vous #{{ $appointment->id }}</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-transparent p-0 mb-0">
                                <li class="breadcrumb-item"><a
                                        href="{{ route('admin.appointments.index') }}">Rendez-vous</a></li>
                                <li class="breadcrumb-item active">Détails</li>
                            </ol>
                        </nav>
                    </div>
                    <span class="badge badge-{{ $appointment->status_color }} badge-lg"
                        style="font-size: 1.2rem; padding: 10px 20px;">
                        {{ $appointment->status_label }}
                    </span>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <div class="row">
            <!-- Informations principales -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informations du rendez-vous</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="text-muted">Sujet</label>
                                <h5>{{ $appointment->subject }}</h5>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted">Date et heure</label>
                                <h5>
                                    <i class="far fa-calendar"></i> {{ $appointment->formatted_date }}
                                </h5>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="text-muted">Durée</label>
                                <p class="mb-0">{{ $appointment->duration_minutes }} minutes</p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted">Demandé le</label>
                                <p class="mb-0">{{ $appointment->created_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted">Message de l'utilisateur</label>
                            <div class="alert alert-light">
                                {{ $appointment->message }}
                            </div>
                        </div>

                        @if ($appointment->admin_notes)
                            <div class="mb-3">
                                <label class="text-muted">Notes de l'administrateur</label>
                                <div class="alert alert-warning">
                                    {{ $appointment->admin_notes }}
                                </div>
                            </div>
                        @endif

                        @if ($appointment->status === 'approved' && $appointment->moderator_meeting_url)
                            <div class="alert alert-success">
                                <h6 class="alert-heading"><i class="fas fa-video"></i> Lien de visioconférence :</h6>
                                 <span>{{ $appointment->moderator_meeting_url }}</span>
                                <small class="d-block mt-2 text-muted">Salle : {{ $appointment->meeting_room }}</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Informations utilisateur et actions -->
            <div class="col-lg-4">
                <!-- Utilisateur -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-user"></i> Utilisateur</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-1"><strong>Nom :</strong> {{ $appointment->full_name }}</p>
                        <p class="mb-1"><strong>Email : </strong> {{ $appointment->email }}</p>
                      
                    </div>
                </div>

                <!-- Instructeur assigné -->
                @if ($appointment->instructor)
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-chalkboard-teacher"></i> Instructeur assigné</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-1"><strong>{{ $appointment->instructor->full_name }}</strong></p>
                            <p class="mb-0 text-muted">{{ $appointment->instructor->email }}</p>
                        </div>
                    </div>
                @endif

                <!-- Historique -->
                <div class="card mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="fas fa-history"></i> Historique</h5>
                    </div>
                    <div class="card-body">
                        <small>
                            <p class="mb-2"><strong>Créé
                                    :</strong><br>{{ $appointment->created_at->format('d/m/Y H:i') }}</p>
                            @if ($appointment->approved_at)
                                <p class="mb-2"><strong>Approuvé
                                        :</strong><br>{{ $appointment->approved_at->format('d/m/Y H:i') }}</p>
                            @endif
                            @if ($appointment->approvedBy)
                                <p class="mb-0"><strong>Par :</strong><br>{{ $appointment->approvedBy->full_name }}</p>
                            @endif
                        </small>
                    </div>
                </div>

                <!-- Actions -->
                @if ($appointment->status === 'pending')
                    <div class="card border-warning">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="fas fa-tasks"></i> Actions requises</h5>
                        </div>
                        <div class="card-body">
                            <!-- Formulaire d'approbation -->
                            <form action="{{ route('admin.appointments.approve', $appointment->id) }}" method="POST"
                                class="mb-3">
                                @csrf
                                <div class="form-group">
                                    <label><strong>Assigner à un instructeur *</strong></label>
                                    <select name="instructor_id"
                                        class="form-control @error('instructor_id') is-invalid @enderror" required>
                                        <option value="">-- Sélectionner --</option>
                                        @foreach ($instructors as $instructor)
                                            <option value="{{ $instructor->id }}">{{ $instructor->full_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('instructor_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Notes (optionnel)</label>
                                    <textarea name="admin_notes" class="form-control" rows="3" placeholder="Message pour l'utilisateur..."></textarea>
                                </div>

                                <button type="submit" class="btn btn-success btn-block">
                                    <i class="fas fa-check"></i> Approuver le rendez-vous
                                </button>
                            </form>

                            <hr>

                            <!-- Formulaire de rejet -->
                            <button type="button" class="btn btn-danger btn-block" data-toggle="modal"
                                data-target="#rejectModal">
                                <i class="fas fa-times"></i> Rejeter la demande
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal de rejet -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.appointments.reject', $appointment->id) }}" method="POST">
                    @csrf
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Rejeter la demande</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label><strong>Raison du rejet *</strong></label>
                            <textarea name="admin_notes" class="form-control @error('admin_notes') is-invalid @enderror" rows="4" required
                                placeholder="Expliquez la raison du rejet..."></textarea>
                            @error('admin_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Cette raison sera envoyée par email à l'utilisateur.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times"></i> Confirmer le rejet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection

@push('scripts_bottom')
@endpush
