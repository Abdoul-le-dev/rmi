@extends(getTemplate() .'.panel.layouts.panel_layout')

@push('styles_top')
       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
    .table td {
        vertical-align: middle;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0"><i class="fas fa-calendar-alt"></i> Mes Rendez-vous</h1>
        </div>
    </div>

    <!-- Rendez-vous à venir -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="far fa-calendar-check"></i> Rendez-vous à venir ({{ $upcomingAppointments->count() }})</h5>
        </div>
        <div class="card-body p-0">
            @if($upcomingAppointments->isEmpty())
                <div class="text-center py-5">
                    <i class="far fa-calendar-times fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Aucun rendez-vous à venir</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Date & Heure</th>
                                <th>Participant</th>
                                <th>Sujet</th>
                                <th>Durée</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($upcomingAppointments as $appointment)
                                <tr class="{{ $appointment->appointment_date->isToday() ? 'table-warning' : '' }}">
                                    <td>
                                        <strong>
                                            <i class="far fa-calendar"></i> {{ $appointment->appointment_date->format('d/m/Y') }}
                                        </strong><br>
                                        <small>
                                            <i class="far fa-clock"></i> {{ $appointment->appointment_date->format('H:i') }}
                                        </small>
                                        @if($appointment->appointment_date->isToday())
                                            <br><span class="badge badge-warning">Aujourd'hui</span>
                                        @elseif($appointment->appointment_date->isTomorrow())
                                            <br><span class="badge badge-info">Demain</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div><strong>{{ $appointment->full_name }}</strong></div>
                                        <small class="text-muted">{{ $appointment->email }}</small>
                                    </td>
                                    <td>{{ $appointment->subject }}</td>
                                    <td>{{ $appointment->duration_minutes }} min</td>
                                    <td>
                                        <a href="{{ route('instructor.appointments.show', $appointment->id) }}" 
                                           class="btn btn-sm btn-info" title="Détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($appointment->moderator_meeting_url)
                                            <a href="{{ $appointment->moderator_meeting_url }}" 
                                               target="_blank" 
                                               class="btn btn-sm btn-success" 
                                               title="Rejoindre">
                                                <i class="fas fa-video"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Historique -->
    <div class="card">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="fas fa-history"></i> Historique</h5>
        </div>
        <div class="card-body p-0">
            @if($pastAppointments->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Aucun historique</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Date</th>
                                <th>Participant</th>
                                <th>Sujet</th>
                                <th>Durée</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pastAppointments as $appointment)
                                <tr>
                                    <td>
                                        <small>{{ $appointment->appointment_date->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $appointment->full_name }}</div>
                                        <small class="text-muted">{{ $appointment->email }}</small>
                                    </td>
                                    <td>{{ Str::limit($appointment->subject, 40) }}</td>
                                    <td>{{ $appointment->duration_minutes }} min</td>
                                    <td>
                                        <span class="badge badge-{{ $appointment->status_color }} text-dark">
                                            {{ $appointment->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('instructor.appointments.show', $appointment->id) }}" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        @if($pastAppointments->hasPages())
            <div class="card-footer">
                {{ $pastAppointments->links() }}
            </div>
        @endif
    </div>
</div>


@endsection