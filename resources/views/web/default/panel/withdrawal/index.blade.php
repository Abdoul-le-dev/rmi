@extends(getTemplate() . '.panel.layouts.panel_layout')

@push('styles_top')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mb-4">Mes Retraits</h2>

                <!-- Messages de succès/erreur -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        
                        <ul class="mt-2 mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                       <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <!-- Carte du solde -->
                <div class="mb-4 row">
                    <div class="col-md-6">
                        <div class="text-white card bg-primary">
                            <div class="card-body">
                                <h5 class="card-title">Solde Disponible</h5>
                                <h2 class="mb-0">{{ number_format($user->balance, 2, ',', ' ') }} $</h2>
                                <p class="mt-2 mb-0">
                                    <small>Solde disponible pour retrait</small>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Actions</h5>
                                <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal"
                                    data-target="#withdrawalModal" {{ $user->balance <= 0 ? 'disabled' : '' }}>
                                    <i class="fas fa-money-bill-wave" style="margin-right: 8px;"></i> Effectuer un retrait
                                </button>
                                @if ($user->balance <= 0)
                                    <small class="mt-2 text-muted d-block">
                                        Solde insuffisant pour effectuer un retrait
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Liste des retraits -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Historique des retraits</h5>
                    </div>
                    <div class="card-body">
                        @if ($withdrawals->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Montant</th>
                                            <th>Méthode</th>
                                            <th>Statut</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($withdrawals as $withdrawal)
                                            <tr>
                                                <td>#{{ $withdrawal->id }}</td>
                                                <td>
                                                    <strong>{{ number_format($withdrawal->amount, 2, ',', ' ') }} $</strong>
                                                </td>
                                                <td>
                                                    @if ($withdrawal->payment_method === 'bank')
                                                        <span class="badge badge-info text-dark">Virement bancaire</span>
                                                    @else
                                                        <span class="badge badge-warning">Mobile Money</span>
                                                    @endif
                                                </td>
                                                <td>{!! $withdrawal->status_badge !!}</td>
                                                <td>{{ $withdrawal->created_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <a href="{{ route('user.withdrawals.show', $withdrawal->id) }}"
                                                        class="btn btn-sm btn-info text-dark">
                                                        <i class="fas fa-eye"></i> Détails
                                                    </a>

                                                    @if ($withdrawal->canBeCancelled())
                                                        <form
                                                            action="{{ route('user.withdrawals.cancel', $withdrawal->id) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Êtes-vous sûr de vouloir annuler ce retrait ?')">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-danger">
                                                                <i class="fas fa-times"></i> Annuler
                                                            </button>
                                                        </form>
                                                    @endif
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
                                <p class="text-muted">Aucune demande de retrait pour le moment</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de demande de retrait -->
    <div class="modal fade" id="withdrawalModal" tabindex="-1" role="dialog" aria-labelledby="withdrawalModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('user.withdrawals.store') }}" method="POST" id="withdrawalForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="withdrawalModalLabel">
                            <i class="fas fa-money-bill-wave"></i> Nouvelle demande de retrait
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Montant -->
                        <div class="form-group">
                            <label for="amount">Montant à retirer <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount"
                                name="amount" step="0.01" min="1" max="{{ $user->balance }}"
                                placeholder="Entrez le montant" required>
                            <small class="form-text text-muted">Solde disponible :
                                {{ number_format($user->balance, 2, ',', ' ') }} $</small>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Méthode de paiement -->
                        <div class="form-group">
                            <label>Méthode de paiement <span class="text-danger">*</span></label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" name="payment_method"
                                            id="bank" value="bank" required>
                                        <label class="custom-control-label" for="bank">
                                            <i class="fas fa-university"></i> Virement bancaire
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" name="payment_method"
                                            id="mobile_money" value="mobile_money" required>
                                        <label class="custom-control-label" for="mobile_money">
                                            <i class="fas fa-mobile-alt"></i> Mobile Money
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informations bancaires -->
                        <div id="bankFields" style="display: none;">
                            <hr>
                            <h6 class="mb-3">Informations bancaires</h6>

                            <div class="form-group">
                                <label for="bank_name">Nom de la banque</label>
                                <input type="text" class="form-control" id="bank_name" name="bank_name">
                            </div>

                            <div class="form-group">
                                <label for="account_holder_name">Nom du titulaire</label>
                                <input type="text" class="form-control" id="account_holder_name"
                                    name="account_holder_name">
                            </div>

                            <div class="form-group">
                                <label for="account_number">Numéro de compte</label>
                                <input type="text" class="form-control" id="account_number" name="account_number">
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="iban">IBAN (optionnel)</label>
                                        <input type="text" class="form-control" id="iban" name="iban">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="swift_code">Code SWIFT (optionnel)</label>
                                        <input type="text" class="form-control" id="swift_code" name="swift_code">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informations Mobile Money -->
                        <div id="mobileFields" style="display: none;">
                            <hr>
                            <h6 class="mb-3">Informations Mobile Money</h6>

                            <div class="form-group">
                                <label for="mobile_operator">Opérateur</label>
                                <select class="form-control" id="mobile_operator" name="mobile_operator">
                                    <option value="">Choisir un opérateur</option>
                                    <option value="MTN">MTN Mobile Money</option>
                                    <option value="Moov">Moov Money</option>
                                    <option value="Orange">Orange Money</option>
                                    <option value="Wave">Wave</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="mobile_number">Numéro de téléphone</label>
                                <input type="text" class="form-control" id="mobile_number" name="mobile_number"
                                    placeholder="+229 XX XX XX XX">
                            </div>

                            <div class="form-group">
                                <label for="mobile_account_name">Nom du compte</label>
                                <input type="text" class="form-control" id="mobile_account_name"
                                    name="mobile_account_name">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check"></i> Soumettre la demande
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts_bottom')
        <script>
            $(document).ready(function() {
                const bankRadio = $('#bank');
                const mobileRadio = $('#mobile_money');
                const bankFields = $('#bankFields');
                const mobileFields = $('#mobileFields');

                // Toggle des champs selon la méthode choisie
                function togglePaymentFields() {
                    if (bankRadio.is(':checked')) {
                        bankFields.show();
                        mobileFields.hide();

                        // Rendre les champs bancaires requis
                        $('#bankFields input[type="text"]').each(function() {
                            if (!$(this).attr('id').includes('iban') && !$(this).attr('id').includes('swift')) {
                                $(this).prop('required', true);
                            }
                        });

                        // Retirer required des champs mobile
                        $('#mobileFields input, #mobileFields select').prop('required', false);
                    } else if (mobileRadio.is(':checked')) {
                        bankFields.hide();
                        mobileFields.show();

                        // Rendre les champs mobile requis
                        $('#mobileFields input, #mobileFields select').prop('required', true);

                        // Retirer required des champs bancaires
                        $('#bankFields input').prop('required', false);
                    }
                }

                bankRadio.on('change', togglePaymentFields);
                mobileRadio.on('change', togglePaymentFields);

                // Reset form when modal closes
                $('#withdrawalModal').on('hidden.bs.modal', function() {
                    $('#withdrawalForm')[0].reset();
                    bankFields.hide();
                    mobileFields.hide();
                });
            });
        </script>
    @endpush
@endsection
