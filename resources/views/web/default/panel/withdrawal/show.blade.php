@extends(getTemplate() .'.panel.layouts.panel_layout')

@push('styles_top')
       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
  
@endpush
@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('user.withdrawals.index') }}">Mes Retraits</a></li>
                    <li class="breadcrumb-item active">Détails #{{ $withdrawal->id }}</li>
                </ol>
            </nav>

            <h2 class="mb-4">Détails de la demande de retrait #{{ $withdrawal->id }}</h2>

            <!-- Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <!-- Informations principales -->
                <div class="col-md-6">
                    <div class="mb-4 card">
                        <div class="card-header">
                            <h5 class="mb-0">Informations générales</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Référence :</th>
                                    <td>#{{ $withdrawal->id }}</td>
                                </tr>
                                <tr>
                                    <th>Montant :</th>
                                    <td><strong class="text-primary">{{ number_format($withdrawal->amount, 2, ',', ' ') }} $</strong></td>
                                </tr>
                                <tr>
                                    <th>Méthode :</th>
                                    <td>
                                        @if($withdrawal->payment_method === 'bank')
                                            <span class="badge bg-info">Virement bancaire</span>
                                        @else
                                            <span class="badge bg-warning">Mobile Money</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Statut :</th>
                                    <td>{!! $withdrawal->status_badge !!}</td>
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
                                @endif
                            </table>

                            @if($withdrawal->canBeCancelled())
                                <form action="{{ route('user.withdrawals.cancel', $withdrawal->id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir annuler ce retrait ?')">
                                    @csrf
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-times"></i> Annuler cette demande
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Informations de paiement -->
                <div class="col-md-6">
                    <div class="mb-4 card">
                        <div class="card-header">
                            <h5 class="mb-0">Informations de paiement</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                @foreach($withdrawal->payment_details as $label => $value)
                                    @if($value)
                                        <tr>
                                            <th width="40%">{{ $label }} :</th>
                                            <td>{{ $value }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </table>
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

                <!-- Informations complémentaires -->
                @if($withdrawal->status === 'approved')
                    <div class="col-md-12">
                        <div class="alert alert-success text-dark">
                            <i class="fas fa-check-circle"></i> 
                            <strong>Retrait approuvé !</strong> 
                            Votre demande a été traitée et le paiement est en cours. 
                            Vous devriez recevoir votre argent dans les prochains jours ouvrables.
                        </div>
                    </div>
                @endif

                @if($withdrawal->status === 'pending')
                    <div class="col-md-12">
                        <div class="alert alert-warning">
                            <i class="fas fa-clock"></i> 
                            <strong>En attente de traitement</strong> 
                            Votre demande est en cours de vérification par notre équipe. 
                            Vous serez notifié une fois qu'elle sera traitée.
                        </div>
                    </div>
                @endif

                @if($withdrawal->status === 'cancelled')
                    <div class="col-md-12">
                        <div class="alert alert-secondary text-white">
                            <i class="fas fa-times-circle"></i> 
                            <strong>Demande annulée</strong> 
                            Vous avez annulé cette demande. Le montant a été remboursé sur votre solde.
                        </div>
                    </div>
                @endif
            </div>

            <div class="mt-3">
                <a href="{{ route('user.withdrawals.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour à la liste
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
