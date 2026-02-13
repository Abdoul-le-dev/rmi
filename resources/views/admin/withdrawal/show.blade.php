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
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.withdrawals.index') }}">Retraits</a></li>
                    <li class="breadcrumb-item active">Détails #{{ $withdrawal->id }}</li>
                </ol>
            </nav>

            <div class="mb-4 d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Détails du retrait #{{ $withdrawal->id }}</h2>
                <div>
                    {!! $withdrawal->status_badge !!}
                </div>
            </div>

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

            <div class="row">
                <!-- Informations de l'utilisateur -->
                <div class="col-md-6">
                    <div class="mb-4 card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-user"></i> Utilisateur</h5>
                        </div>
                        <div class="card-body">
                            <table class="mb-0 table table-borderless">
                                <tr>
                                    <th width="40%">Nom :</th>
                                    <td>{{ $withdrawal->user->full_name }}</td>
                                </tr>
                                <tr>
                                    <th>Email :</th>
                                    <td>{{ $withdrawal->user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Solde actuel :</th>
                                    <td><strong>{{ number_format($withdrawal->user->balance, 2, ',', ' ') }} $</strong></td>
                                </tr>
                                <tr>
                                    <th>Membre depuis :</th>
                                    <td>{{ \Carbon\Carbon::parse($withdrawal->user->created_at)->format('d/m/Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Informations du retrait -->
                <div class="col-md-6">
                    <div class="mb-4 card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-money-bill-wave"></i> Détails du retrait</h5>
                        </div>
                        <div class="card-body">
                            <table class="mb-0 table table-borderless">
                                <tr>
                                    <th width="40%">Référence :</th>
                                    <td>#{{ $withdrawal->id }}</td>
                                </tr>
                                <tr>
                                    <th>Montant :</th>
                                    <td>
                                        <h4 class="mb-0 text-primary">{{ number_format($withdrawal->amount, 2, ',', ' ') }} $</h4>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Méthode :</th>
                                    <td>
                                        @if($withdrawal->payment_method === 'bank')
                                            <span class="badge badge-info">Virement bancaire</span>
                                        @else
                                            <span class="badge badge-warning">Mobile Money</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Date de demande :</th>
                                    <td>{{ $withdrawal->created_at->format('d/m/Y à H:i') }}</td>
                                </tr>
                                @if($withdrawal->processed_at)
                                    <tr>
                                        <th>Date de traitement :</th>
                                        <td>{{ $withdrawal->processed_at->format('d/m/Y à H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Traité par :</th>
                                        <td>{{ $withdrawal->processor ? $withdrawal->processor->full_name : 'N/A' }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Informations de paiement -->
                <div class="col-md-12">
                    <div class="mb-4 card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-credit-card"></i> Informations de paiement</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($withdrawal->payment_details as $label => $value)
                                    @if($value)
                                        <div class="col-md-6 mb-3">
                                            <strong>{{ $label }} :</strong>
                                            <div class="mb-0 mt-1 alert alert-light">
                                                {{ $value }}
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Motif de rejet -->
                @if($withdrawal->status === 'rejected' && $withdrawal->rejection_reason)
                    <div class="col-md-12">
                        <div class="mb-4 card border-danger">
                            <div class="text-white card-header bg-danger">
                                <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Motif du rejet</h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-0">{{ $withdrawal->rejection_reason }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Actions -->
                @if($withdrawal->canBeProcessed())
                    <div class="col-md-12">
                        <div class="card border-warning">
                            <div class="card-header bg-warning">
                                <h5 class="mb-0"><i class="fas fa-tasks"></i> Actions à effectuer</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="alert alert-success">
                                            <h6 class="alert-heading">Approuver ce retrait</h6>
                                            <p class="mb-3">
                                                Le retrait sera marqué comme approuvé. 
                                                Assurez-vous d'avoir effectué le paiement avant d'approuver.
                                            </p>
                                            <form action="{{ route('admin.withdrawals.approve', $withdrawal->id) }}" 
                                                  method="POST"
                                                  onsubmit="return confirm('Êtes-vous sûr d\'avoir effectué le paiement et de vouloir approuver ce retrait ?')">
                                                @csrf
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fas fa-check"></i> Approuver le retrait
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="alert alert-danger">
                                            <h6 class="alert-heading">Rejeter ce retrait</h6>
                                            <p class="mb-3">
                                                Le montant sera remboursé à l'utilisateur. 
                                                Vous devez fournir un motif de rejet.
                                            </p>
                                            <button type="button" 
                                                    class="btn btn-danger" 
                                                    data-toggle="modal" 
                                                    data-target="#rejectModal">
                                                <i class="fas fa-times"></i> Rejeter le retrait
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Historique des retraits de l'utilisateur -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-history"></i> Historique des retraits de cet utilisateur</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $userWithdrawals = \App\Models\Withdrawal::where('user_id', $withdrawal->user_id)
                                    ->where('id', '!=', $withdrawal->id)
                                    ->orderBy('created_at', 'desc')
                                    ->limit(5)
                                    ->get();
                            @endphp

                            @if($userWithdrawals->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Montant</th>
                                                <th>Méthode</th>
                                                <th>Statut</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($userWithdrawals as $w)
                                                <tr>
                                                    <td>#{{ $w->id }}</td>
                                                    <td>{{ number_format($w->amount, 2, ',', ' ') }} $</td>
                                                    <td>
                                                        @if($w->payment_method === 'bank')
                                                            <span class="badge badge-info">Banque</span>
                                                        @else
                                                            <span class="badge badge-warning">Mobile</span>
                                                        @endif
                                                    </td>
                                                    <td>{!! $w->status_badge !!}</td>
                                                    <td>{{ $w->created_at->format('d/m/Y') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="mb-0 text-muted">Aucun autre retrait pour cet utilisateur.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('admin.withdrawals.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour à la liste
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal de rejet -->
@if($withdrawal->canBeProcessed())
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.withdrawals.reject', $withdrawal->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="rejectModalLabel">Rejeter le retrait #{{ $withdrawal->id }}</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        Le montant de <strong>{{ number_format($withdrawal->amount, 2, ',', ' ') }} $</strong> 
                        sera remboursé sur le solde de l'utilisateur <strong>{{ $withdrawal->user->full_name }}</strong>.
                    </div>
                    
                    <div class="form-group">
                        <label for="rejection_reason">
                            Motif du rejet <span class="text-danger">*</span>
                        </label>
                        <textarea name="rejection_reason" 
                                  id="rejection_reason" 
                                  class="form-control @error('rejection_reason') is-invalid @enderror" 
                                  rows="5" 
                                  placeholder="Expliquez clairement pourquoi vous rejetez cette demande. L'utilisateur recevra ce message."
                                  required>{{ old('rejection_reason') }}</textarea>
                        @error('rejection_reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Minimum 10 caractères. Soyez clair et professionnel.</small>
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
@endif
@endsection