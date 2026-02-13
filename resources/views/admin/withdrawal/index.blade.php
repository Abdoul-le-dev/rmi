@extends('admin.layouts.app')

@push('styles_top')
    <!-- Font Awesome 6.5.1 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@section('content')
<div class="py-4 container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">Gestion des Retraits</h2>

            <!-- Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <!-- Statistiques -->
            <div class="mb-4 row">
                <div class="col-md-3">
                    <div class="text-white card bg-warning">
                        <div class="card-body">
                            <h6 class="card-title">En attente</h6>
                            <h3 class="mb-0">{{ $stats['total_pending'] }}</h3>
                            <small>{{ number_format($stats['total_pending_amount'], 0, ',', ' ') }} $</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-white card bg-success">
                        <div class="card-body">
                            <h6 class="card-title">Approuvés</h6>
                            <h3 class="mb-0">{{ $stats['total_approved'] }}</h3>
                            <small>{{ number_format($stats['total_approved_amount'], 0, ',', ' ') }} $</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-white card bg-danger">
                        <div class="card-body">
                            <h6 class="card-title">Rejetés</h6>
                            <h3 class="mb-0">{{ $stats['total_rejected'] }}</h3>
                            <small>Ce mois</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-white card bg-info">
                        <div class="card-body">
                            <h6 class="card-title">Actions</h6>
                            <a href="{{ route('admin.withdrawals.export', request()->query()) }}" 
                               class="btn btn-light btn-sm">
                                <i class="fas fa-download"></i> Exporter CSV
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtres -->
            <div class="mb-4 card">
                <div class="card-body">
                    <form action="{{ route('admin.withdrawals.index') }}" method="GET" class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="status">Statut</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Tous</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approuvés</option>
                                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejetés</option>
                                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Annulés</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="search">Rechercher un utilisateur</label>
                                <input type="text" 
                                       name="search" 
                                       id="search" 
                                       class="form-control" 
                                       placeholder="Nom ou email de l'utilisateur"
                                       value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search"></i> Filtrer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Liste des retraits -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Liste des demandes de retrait ({{ $withdrawals->total() }})</h5>
                </div>
                <div class="card-body">
                    @if($withdrawals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Utilisateur</th>
                                        <th>Montant</th>
                                        <th>Méthode</th>
                                        <th>Statut</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($withdrawals as $withdrawal)
                                        <tr class="{{ $withdrawal->status === 'pending' ? 'table-warning' : '' }}">
                                            <td>#{{ $withdrawal->id }}</td>
                                            <td>
                                                <div>
                                                    <strong>{{ $withdrawal->user->full_name }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $withdrawal->user->email }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <strong>{{ number_format($withdrawal->amount, 2, ',', ' ') }} $</strong>
                                            </td>
                                            <td>
                                                @if($withdrawal->payment_method === 'bank')
                                                    <span class="badge badge-info">Virement bancaire</span>
                                                @else
                                                    <span class="badge badge-warning">Mobile Money</span>
                                                @endif
                                            </td>
                                            <td>{!! $withdrawal->status_badge !!}</td>
                                            <td>
                                                <small>{{ $withdrawal->created_at->format('d/m/Y H:i') }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.withdrawals.show', $withdrawal->id) }}" 
                                                       class="btn btn-sm btn-info" 
                                                       title="Voir les détails">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                    @if($withdrawal->canBeProcessed())
                                                        <form action="{{ route('admin.withdrawals.approve', $withdrawal->id) }}" 
                                                              method="POST" 
                                                              class="d-inline"
                                                              onsubmit="return confirm('Approuver ce retrait ?')">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="btn btn-sm btn-success" 
                                                                    title="Approuver">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>

                                                        <button type="button"
                                                                class="btn btn-sm btn-danger"
                                                                data-toggle="modal"
                                                                data-target="#rejectModal{{ $withdrawal->id }}"
                                                                title="Rejeter">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-3">
                            {{ $withdrawals->links() }}
                        </div>
                    @else
                        <div class="py-5 text-center">
                            <i class="mb-3 fas fa-inbox fa-3x text-muted"></i>
                            <p class="text-muted">Aucune demande de retrait trouvée</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals de rejet (en dehors de la boucle pour éviter les problèmes) -->
@if($withdrawals->count() > 0)
    @foreach($withdrawals as $withdrawal)
        @if($withdrawal->canBeProcessed())
        <div class="modal fade" id="rejectModal{{ $withdrawal->id }}" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel{{ $withdrawal->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('admin.withdrawals.reject', $withdrawal->id) }}" method="POST">
                        @csrf
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title" id="rejectModalLabel{{ $withdrawal->id }}">Rejeter le retrait #{{ $withdrawal->id }}</h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                Le montant de <strong>{{ number_format($withdrawal->amount, 2, ',', ' ') }} $</strong> 
                                sera remboursé à l'utilisateur.
                            </div>
                            
                            <div class="form-group">
                                <label for="rejection_reason{{ $withdrawal->id }}">
                                    Motif du rejet <span class="text-danger">*</span>
                                </label>
                                <textarea name="rejection_reason" 
                                          id="rejection_reason{{ $withdrawal->id }}" 
                                          class="form-control" 
                                          rows="4" 
                                          placeholder="Expliquez pourquoi vous rejetez cette demande..."
                                          required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-times"></i> Rejeter la demande
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    @endforeach
@endif
@endsection