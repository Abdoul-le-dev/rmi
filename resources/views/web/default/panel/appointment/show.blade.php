@extends(getTemplate() .'.panel.layouts.panel_layout')

@push('styles_top')
       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
   <style>
    .badge-lg {
        font-size: 1rem;
        padding: 8px 15px;
    }
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    .timeline-item {
        position: relative;
        padding-bottom: 20px;
    }
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -24px;
        top: 10px;
        bottom: -10px;
        width: 2px;
        background: #e9ecef;
    }
    .timeline-item:last-child::before {
        display: none;
    }
    .timeline-marker {
        position: absolute;
        left: -30px;
        top: 0;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid white;
        box-shadow: 0 0 0 2px #e9ecef;
    }
    .timeline-content {
        padding-top: 0;
    }
    .timeline-content p {
        font-size: 14px;
        line-height: 1.4;
    }
    .timeline-content small {
        font-size: 11px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4 mt-5">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-2"><i class="fas fa-calendar-check"></i> Détails du rendez-vous</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent p-0 mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('instructor.appointments.index') }}">Mes rendez-vous</a></li>
                            <li class="breadcrumb-item active">Rendez-vous #{{ $appointment->id }}</li>
                        </ol>
                    </nav>
                </div>
                <span class="badge badge-{{ $appointment->status_color }} badge-lg text-dark" style="font-size: 1.2rem; padding: 10px 20px;">
                    {{ $appointment->status_label }}
                </span>
            </div>
        </div>
    </div>

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
                            <label class="text-muted mb-1"><i class="far fa-bookmark"></i> Sujet</label>
                            <h5 class="mb-0">{{ $appointment->subject }}</h5>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted mb-1"><i class="far fa-calendar"></i> Date et heure</label>
                            <h5 class="mb-0">{{ $appointment->formatted_date }}</h5>
                            @if($appointment->is_upcoming && $appointment->appointment_date->isToday())
                                <span class="badge badge-warning mt-1">
                                    <i class="far fa-clock"></i> Aujourd'hui
                                </span>
                            @elseif($appointment->is_upcoming && $appointment->appointment_date->isTomorrow())
                                <span class="badge badge-info mt-1">
                                    <i class="far fa-clock"></i> Demain
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted mb-1"><i class="far fa-hourglass"></i> Durée</label>
                            <p class="mb-0 font-weight-bold">{{ $appointment->duration_minutes }} minutes</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted mb-1"><i class="far fa-calendar-plus"></i> Demandé le</label>
                            <p class="mb-0">{{ $appointment->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted mb-2"><i class="far fa-comment"></i> Message du participant</label>
                        <div class="alert alert-light border">
                            <p class="mb-0">{{ $appointment->message }}</p>
                        </div>
                    </div>

                    @if($appointment->admin_notes)
                        <div class="mb-3">
                            <label class="text-muted mb-2"><i class="fas fa-sticky-note"></i> Notes de l'équipe</label>
                            <div class="alert alert-info border-info">
                                <p class="mb-0">{{ $appointment->admin_notes }}</p>
                            </div>
                        </div>
                    @endif

                    @if($appointment->status === 'approved' && $appointment->moderator_meeting_url)
                        <div class="alert alert-success border-success">
                            <h6 class="alert-heading mb-3">
                                <i class="fas fa-video"></i> Accès à la visioconférence
                            </h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="d-block text-muted mb-1">Salle : {{ $appointment->meeting_room }}</small>
                                    @if($appointment->is_upcoming)
                                        @if($appointment->appointment_date->diffInMinutes(now()) <= 30 && $appointment->appointment_date->isFuture())
                                            <span class="badge badge-warning">
                                                <i class="far fa-clock"></i> Démarre dans {{ $appointment->appointment_date->diffForHumans() }}
                                            </span>
                                        @elseif($appointment->appointment_date->isPast() && $appointment->appointment_date->diffInHours(now()) < 2)
                                            <span class="badge badge-success">
                                                <i class="fas fa-play"></i> En cours ou terminé récemment
                                            </span>
                                        @endif
                                    @endif
                                </div>
                                <a href="{{ $appointment->moderator_meeting_url }}" 
                                   target="_blank" 
                                   class="btn btn-primary">
                                    <i class="fas fa-video" style="margin-right: 8px;"></i> Rejoindre la salle
                                </a>
                            </div>
                        </div>
                    @endif

                    @if($appointment->is_upcoming)
                        <div class="alert alert-warning border-warning">
                            <h6 class="alert-heading">
                                <i class="fas fa-exclamation-triangle"></i> Rappels importants
                            </h6>
                            <ul class="mb-0 pl-3">
                                <li>Connectez-vous 5 minutes avant le début</li>
                                <li>Vérifiez votre microphone et caméra</li>
                                <li>Préparez le contenu à aborder</li>
                                <li>Ayez vos supports de cours à portée de main</li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Informations participant -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header bg-info text-dark">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Participant</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h5 class="mb-1">{{ $appointment->full_name }}</h5>
                    </div>
                    <hr>
                    <p class="mb-2">
                        <i class="far fa-envelope text-muted"></i> 
                        <strong>Email :</strong>
                        <a href="mailto:{{ $appointment->email }}">{{ $appointment->email }}</a>
                    </p>
                  
                   
                </div>
            </div>

            <!-- Compte à rebours si rendez-vous proche -->
            @if($appointment->is_upcoming && $appointment->appointment_date->diffInHours(now()) < 24)
                <div class="card mb-4 border-warning">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="far fa-clock"></i> Temps restant</h5>
                    </div>
                    <div class="card-body text-center">
                        @if($appointment->appointment_date->isFuture())
                            <div class="countdown" style="font-size: 2rem; font-weight: bold; color: #ff6b6b;">
                                {{ $appointment->appointment_date->diffForHumans() }}
                            </div>
                            <small class="text-muted d-block mt-2">
                                Soit le {{ $appointment->appointment_date->format('d/m/Y à H:i') }}
                            </small>
                        @else
                            <div style="font-size: 1.5rem; color: #28a745;">
                                <i class="fas fa-check-circle"></i> En cours ou terminé
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Timeline -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-history"></i> Historique</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <small class="text-muted">{{ $appointment->created_at->format('d/m/Y H:i') }}</small>
                                <p class="mb-0">Demande créée</p>
                            </div>
                        </div>
                        @if($appointment->approved_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <small class="text-muted">{{ $appointment->approved_at->format('d/m/Y H:i') }}</small>
                                    <p class="mb-0">Rendez-vous approuvé</p>
                                    @if($appointment->approvedBy)
                                        <small class="text-muted">par {{ $appointment->approvedBy->full_name }}</small>
                                    @endif
                                </div>
                            </div>
                        @endif
                        @if($appointment->confirmation_sent)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <p class="mb-0">Email de confirmation envoyé</p>
                                </div>
                            </div>
                        @endif
                        @if($appointment->reminder_sent)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-warning"></div>
                                <div class="timeline-content">
                                    <p class="mb-0">Rappel envoyé</p>
                                </div>
                            </div>
                        @endif
                        @if($appointment->status === 'completed')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <p class="mb-0">Rendez-vous terminé</p>
                                </div>
                            </div>
                        @endif
                        @if($appointment->status === 'cancelled')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-danger"></div>
                                <div class="timeline-content">
                                    <p class="mb-0">Rendez-vous annulé</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions en bas -->
    @if($appointment->is_upcoming)
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1"><i class="fas fa-lightbulb"></i> Prêt pour le rendez-vous ?</h5>
                                <p class="mb-0 text-muted">Assurez-vous d'être bien préparé avant de rejoindre la salle</p>
                            </div>
                            @if($appointment->moderator_meeting_url)
                                <a href="{{ $appointment->moderator_meeting_url }}" 
                                   target="_blank" 
                                   class="btn btn-primary btn-lg">
                                    <i class="fas fa-video"></i> Rejoindre maintenant
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>


@endsection
