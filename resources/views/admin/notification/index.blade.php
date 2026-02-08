@extends('admin.layouts.app')

@push('styles_top')
    <!-- Font Awesome 6.5.1 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .info-icon {
            cursor: help;
            color: #6c757d;
            font-size: 13px;
            margin-left: 5px;
            transition: color 0.2s;
        }

        .info-icon:hover {
            color: #495057;
        }

        .custom-tooltip {
            position: fixed;
            width: 300px;
            background-color: #2c3e50;
            color: #fff;
            text-align: left;
            border-radius: 8px;
            padding: 12px 15px;
            z-index: 99999;
            font-size: 13px;
            line-height: 1.5;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            display: none;
            pointer-events: none;
        }

        .custom-tooltip::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -6px;
            border-width: 6px;
            border-style: solid;
            border-color: #2c3e50 transparent transparent transparent;
        }

        .custom-tooltip.show {
            display: block;
        }

        .nav-tabs .nav-link {
            display: inline-flex;
            align-items: center;
        }

        .email-input-area {
            min-height: 120px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Gestion des Notifications</h3>
                    </div>
                    <div class="card-body">
                        <!-- Tabs Navigation -->
                        <ul class="nav nav-tabs" id="notificationTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="mail-tab" data-toggle="tab" href="#mail" role="tab" aria-controls="mail" aria-selected="true">
                                    <i class="fas fa-envelope me-2"></i> Mail
                                    <i class="fas fa-info-circle info-icon" 
                                       data-tooltip="Envoyez des emails personnalisés à vos utilisateurs ou à des destinataires externes. Vous pouvez soit envoyer à tous les utilisateurs enregistrés, soit importer une liste d'emails personnalisée via fichier Excel ou saisie manuelle."
                                       onclick="event.preventDefault();"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="sms-tab" data-toggle="tab" href="#sms" role="tab" aria-controls="sms" aria-selected="false">
                                    <i class="fas fa-sms me-2"></i> SMS
                                    <i class="fas fa-info-circle info-icon" 
                                       data-tooltip="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus in justo fermentum tincidunt, at dignissim erat facilisis. Curabitur euismod, nisl vel convallis pretium, nunc metus bibendum sapien, nec luctus lorem magna nec sapien. Integer at dui nec sapien pulvinar feugiat, sit amet tincidunt urna viverra."
                                       onclick="event.preventDefault();"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="telegram-tab" data-toggle="tab" href="#telegram" role="tab" aria-controls="telegram" aria-selected="false">
                                    <i class="fab fa-telegram me-2"></i> Telegram
                                    <i class="fas fa-info-circle info-icon" 
                                       data-tooltip="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus in justo fermentum tincidunt, at dignissim erat facilisis. Curabitur euismod, nisl vel convallis pretium, nunc metus bibendum sapien, nec luctus lorem magna nec sapien. Integer at dui nec sapien pulvinar feugiat, sit amet tincidunt urna viverra."
                                       onclick="event.preventDefault();"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="whatsapp-tab" data-toggle="tab" href="#whatsapp" role="tab" aria-controls="whatsapp" aria-selected="false">
                                    <i class="fab fa-whatsapp me-2"></i> WhatsApp
                                    <i class="fas fa-info-circle info-icon" 
                                       data-tooltip="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus in justo fermentum tincidunt, at dignissim erat facilisis. Curabitur euismod, nisl vel convallis pretium, nunc metus bibendum sapien, nec luctus lorem magna nec sapien. Integer at dui nec sapien pulvinar feugiat, sit amet tincidunt urna viverra."
                                       onclick="event.preventDefault();"></i>
                                </a>
                            </li>
                             <li class="nav-item">
                                <a class="nav-link" id="mobile-tab" data-toggle="tab" href="#mobile" role="tab" aria-controls="whatsapp" aria-selected="false">
                                   <i class="fas fa-mobile-alt me-2"></i> App Mobile
                                    <i class="fas fa-info-circle info-icon" 
                                       data-tooltip="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus in justo fermentum tincidunt, at dignissim erat facilisis. Curabitur euismod, nisl vel convallis pretium, nunc metus bibendum sapien, nec luctus lorem magna nec sapien. Integer at dui nec sapien pulvinar feugiat, sit amet tincidunt urna viverra."
                                       onclick="event.preventDefault();"></i>
                                </a>
                            </li>
                           
                        </ul>

                        <!-- Tabs Content -->
                        <div class="tab-content mt-4" id="notificationTabsContent">
                            <!-- Mail Tab -->
                            <div class="tab-pane fade show active" id="mail" role="tabpanel" aria-labelledby="mail-tab">
                                <form id="mailForm" action="{{ route('send.mail') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Erreurs de validation :</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
                                    
                                    <!-- Checkbox Envoyer aux utilisateurs -->
                                    <div class="form-check mb-4">
                                        <input class="form-check-input" type="checkbox" id="sendToUsers" name="send_to_users">
                                        <label class="form-check-label" for="sendToUsers">
                                            Envoyer à tous les utilisateurs enregistrés
                                        </label>
                                    </div>

                                    <!-- Zone emails personnalisés (cachée par défaut) -->
                                    <div id="customEmailsSection" class="mb-4">
                                        <label for="customEmails" class="form-label">Emails destinataires</label>
                                        <textarea class="form-control email-input-area" id="customEmails" name="custom_emails" 
                                            rows="4" placeholder="Entrez les emails séparés par des virgules (exemple: email1@example.com, email2@example.com)"></textarea>
                                        
                                        <div class="mt-3">
                                            <label for="excelFile" class="form-label">Ou importer un fichier Excel</label>
                                            <input class="form-control" type="file" id="excelFile" name="excel_file" accept=".xlsx,.xls,.csv">
                                            <small class="form-text text-muted">
                                                Formats acceptés: .xlsx, .xls, .csv
                                            </small>
                                        </div>
                                    </div>

                                    <!-- Subject -->
                                    <div class="mb-4">
                                        <label for="emailSubject" class="form-label">Sujet du mail <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="emailSubject" name="subject" required 
                                            placeholder="Entrez le sujet de votre email">
                                    </div>

                                    <!-- Content -->
                                    <div class="mb-4">
                                        <label for="emailContent" class="form-label">Contenu du mail <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="emailContent" name="content" rows="8" required 
                                            placeholder="Rédigez le contenu de votre email..."></textarea>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <button type="reset" class="btn btn-secondary me-2">
                                            <i class="fas fa-redo me-1"></i> Réinitialiser
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-paper-plane me-1"></i> Envoyer
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- SMS Tab -->
                            <div class="tab-pane fade" id="sms" role="tabpanel" aria-labelledby="sms-tab">
                                <div class="card">
                                    <div class="card-body text-center py-5">
                                        <i class="fas fa-sms fa-4x text-muted mb-3"></i>
                                        <h5 class="text-muted">Indisponible pour le moment</h5>
                                        <p class="text-muted">Cette fonctionnalité sera bientôt disponible.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Telegram Tab -->
                            <div class="tab-pane fade" id="telegram" role="tabpanel" aria-labelledby="telegram-tab">
                                <div class="card">
                                    <div class="card-body text-center py-5">
                                        <i class="fab fa-telegram fa-4x text-muted mb-3"></i>
                                        <h5 class="text-muted">Indisponible pour le moment</h5>
                                        <p class="text-muted">Cette fonctionnalité sera bientôt disponible.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- WhatsApp Tab -->
                            <div class="tab-pane fade" id="whatsapp" role="tabpanel" aria-labelledby="whatsapp-tab">
                                <div class="card">
                                    <div class="card-body text-center py-5">
                                        <i class="fab fa-whatsapp fa-4x text-muted mb-3"></i>
                                        <h5 class="text-muted">Indisponible pour le moment</h5>
                                        <p class="text-muted">Cette fonctionnalité sera bientôt disponible.</p>
                                    </div>
                                </div>
                            </div>
                              <!-- App Mobile Tab -->

                              <div class="tab-pane fade" id="mobile" role="tabpanel" aria-labelledby="mobile-tab">
                                <div class="card">
                                    <div class="card-body text-center py-5">
                                        <i class="fas fa-mobile-alt fa-4x text-muted mb-3"></i>
                                        <h5 class="text-muted">Indisponible pour le moment</h5>
                                        <p class="text-muted">Cette fonctionnalité sera bientôt disponible.</p>
                                    </div>
                                </div>
                            </div>

                          
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tooltip dynamique (hors des tabs) -->
    <div id="customTooltip" class="custom-tooltip"></div>
@endsection

@push('scripts_bottom')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sendToUsersCheckbox = document.getElementById('sendToUsers');
            const customEmailsSection = document.getElementById('customEmailsSection');
            const customEmailsTextarea = document.getElementById('customEmails');
            const excelFileInput = document.getElementById('excelFile');

            // Fonction pour afficher/masquer la section emails personnalisés
            function toggleCustomEmailsSection() {
                if (sendToUsersCheckbox.checked) {
                    customEmailsSection.style.display = 'none';
                    customEmailsTextarea.required = false;
                } else {
                    customEmailsSection.style.display = 'block';
                    customEmailsTextarea.required = true;
                }
            }

            // Initial state
            toggleCustomEmailsSection();

            // Event listener sur le checkbox
            sendToUsersCheckbox.addEventListener('change', toggleCustomEmailsSection);

            // Validation du formulaire
            document.getElementById('mailForm').addEventListener('submit', function(e) {
                e.preventDefault();

                // Vérifier qu'au moins une source d'emails est fournie
                if (!sendToUsersCheckbox.checked) {
                    const hasCustomEmails = customEmailsTextarea.value.trim() !== '';
                    const hasExcelFile = excelFileInput.files.length > 0;

                    if (!hasCustomEmails && !hasExcelFile) {
                        alert('Veuillez fournir des emails (manuellement ou via fichier Excel) ou cocher "Envoyer à tous les utilisateurs"');
                        return;
                    }
                }

                // Soumission du formulaire (vous pouvez ajouter votre logique AJAX ici)
                alert('Formulaire prêt à être envoyé!');
                // this.submit(); // Décommentez pour soumettre réellement
            });

            // ========== GESTION DES TOOLTIPS ==========
            const tooltip = document.getElementById('customTooltip');
            const infoIcons = document.querySelectorAll('.info-icon');

            infoIcons.forEach(icon => {
                // Afficher le tooltip au survol
                icon.addEventListener('mouseenter', function(e) {
                    const tooltipText = this.getAttribute('data-tooltip');
                    const rect = this.getBoundingClientRect();
                    
                    // Définir le contenu du tooltip
                    tooltip.textContent = tooltipText;
                    tooltip.classList.add('show');
                    
                    // Calculer la position
                    const tooltipHeight = tooltip.offsetHeight;
                    const tooltipWidth = tooltip.offsetWidth;
                    
                    let top = rect.top - tooltipHeight - 10;
                    let left = rect.left + (rect.width / 2) - (tooltipWidth / 2);
                    
                    // Ajuster si le tooltip dépasse à gauche
                    if (left < 10) {
                        left = 10;
                    }
                    
                    // Ajuster si le tooltip dépasse à droite
                    if (left + tooltipWidth > window.innerWidth - 10) {
                        left = window.innerWidth - tooltipWidth - 10;
                    }
                    
                    // Ajuster si le tooltip dépasse en haut
                    if (top < 10) {
                        top = rect.bottom + 10; // Afficher en bas de l'icône
                    }
                    
                    tooltip.style.top = top + 'px';
                    tooltip.style.left = left + 'px';
                });

                // Masquer le tooltip quand on quitte l'icône
                icon.addEventListener('mouseleave', function() {
                    tooltip.classList.remove('show');
                });
            });

            // Masquer le tooltip lors du scroll
            window.addEventListener('scroll', function() {
                tooltip.classList.remove('show');
            });
        });



        // Dans votre fichier blade ou JS
document.getElementById('excelFile').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('excel_file', file);

    fetch('/admin_d_fiacre/new-notifications/validate-excel', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.valid) {
            alert(`${data.count} email(s) valide(s) détecté(s) dans le fichier`);
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
    });
});
    </script>

    
@endpush