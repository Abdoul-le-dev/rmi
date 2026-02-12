@extends('admin.layouts.app')
@section('title', 'Gestion des Rendez-vous')
@push('styles_top')
    <!-- Font Awesome 6.5.1 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
    .badge-pill {
        padding: 8px 15px;
    }
    .table td {
        vertical-align: middle;
    }
</style>
@endpush
@section('content')
<div class="container-fluid py-4 mt-5">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Gestion des Rendez-vous</h1>
                @if($pendingCount > 0)
                    <span class="badge badge-warning badge-pill" style="font-size: 1.2rem;">
                        {{ $pendingCount }} en attente
                    </span>
                @endif
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Filtres -->
    <div class="card mb-4 pt-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.appointments.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Statut</label>
                            <select name="status" class="form-control">
                                <option value="">Tous</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approuvé</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejeté</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminé</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Date début</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Date fin</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-filter"></i> Filtrer
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des rendez-vous -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Utilisateur</th>
                            <th>Sujet</th>
                            <th>Date</th>
                            <th>Durée</th>
                            <th>Instructeur</th>
                            <th>Statut</th>
                            <th>Créé le</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $appointment)
                            <tr>
                                <td><strong>#{{ $appointment->id }}</strong></td>
                                <td>
                                    <div>{{ $appointment->full_name }}</div>
                                    <small class="text-muted">{{ $appointment->email }}</small>
                                </td>
                                <td>{{ Str::limit($appointment->subject, 40) }}</td>
                                <td>
                                    <i class="far fa-calendar"></i>
                                    {{ $appointment->appointment_date->format('d/m/Y') }}<br>
                                    <small class="text-muted">
                                        <i class="far fa-clock"></i> {{ $appointment->appointment_date->format('H:i') }}
                                    </small>
                                </td>
                                <td>{{ $appointment->duration_minutes }} min</td>
                                <td>
                                    @if($appointment->instructor)
                                        {{ $appointment->instructor->full_name }}
                                    @else
                                        <span class="text-muted">Non assigné</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ $appointment->status_color }}">
                                        {{ $appointment->status_label }}
                                    </span>
                                </td>
                                <td>
                                    <small>{{ $appointment->created_at->format('d/m/Y H:i') }}</small>
                                </td>
                                <td>
                                    <a href="{{ route('admin.appointments.show', $appointment->id) }}" 
                                       class="btn btn-sm btn-info" title="Voir détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($appointment->status === 'approved' && $appointment->appointment_date->isPast())
                                        <form action="{{ route('admin.appointments.mark-completed', $appointment->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success" 
                                                    title="Marquer comme terminé"
                                                    onclick="return confirm('Marquer ce rendez-vous comme terminé ?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Aucun rendez-vous trouvé</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($appointments->hasPages())
            <div class="card-footer">
                {{ $appointments->links() }}
            </div>
        @endif
    </div>
</div>


@endsection
    @push('scripts_bottom')
    @endpush
