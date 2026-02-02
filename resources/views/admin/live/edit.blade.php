@extends('admin.layouts.app')


@push('styles_top')
   
    <!-- Font Awesome 6.5.1 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
                <div>
                    <h2 class="mb-1">Modifier la session</h2>
                    <p class="text-muted mb-0">Modifiez les informations de la session en direct</p>
                </div>
                <a href="{{ route('sessions.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
            </div>

            <!-- Affichage des erreurs -->
            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h5 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Erreurs de validation</h5>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <form action="{{ route('sessions.update', $session->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Informations principales -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2 text-primary"></i>Informations principales</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- Titre -->
                            <div class="col-md-6">
                                <label for="title" class="form-label">
                                    Titre de la session <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('title') is-invalid @enderror" 
                                       id="title" 
                                       name="title" 
                                       value="{{ old('title', $session->title) }}" 
                                       required
                                       placeholder="Ex: Introduction au développement web">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Instructeur -->
                            <div class="col-md-6">
                                <label for="instructor_id" class="form-label">
                                    Instructeur <span class="text-danger">*</span>
                                </label>
                                <select class="form-control @error('instructor_id') is-invalid @enderror" 
                                        id="instructor_id" 
                                        name="instructor_id" 
                                        required>
                                    <option value="">Sélectionner un instructeur</option>
                                    @foreach($instructors as $instructor)
                                        <option value="{{ $instructor->id }}" 
                                                {{ old('instructor_id', $session->instructor_id) == $instructor->id ? 'selected' : '' }}>
                                            {{ $instructor->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('instructor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="col-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="4"
                                          placeholder="Décrivez le contenu de la session...">{{ old('description', $session->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Image de couverture -->
                            <div class="col-12">
                                <label for="live_cover" class="form-label">Image de couverture</label>
                                
                                @if($session->live_cover)
                                <div class="mb-3">
                                    <p class="text-muted mb-2 small">Image actuelle :</p>
                                    <img src="{{ Storage::disk('public')->url($session->live_cover)}}" 
                                         alt="Couverture actuelle" 
                                         class="img-thumbnail"
                                         style="max-width: 300px;">
                                </div>
                                @endif

                                <input type="file" 
                                       class="form-control @error('live_cover') is-invalid @enderror" 
                                       id="live_cover" 
                                       name="live_cover"
                                       accept="image/jpeg,image/png,image/jpg,image/gif">
                                <small class="form-text text-muted">
                                    Formats acceptés : JPEG, PNG, JPG, GIF. Taille max : 2 Mo
                                </small>
                                @error('live_cover')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Paramètres de planification -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="fas fa-calendar-alt me-2 text-primary"></i>Planification</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- Nom de la salle -->
                            <div class="col-md-6">
                                <label for="room_name" class="form-label">
                                    Nom de la salle <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('room_name') is-invalid @enderror" 
                                       id="room_name" 
                                       name="room_name" 
                                       value="{{ old('room_name', $session->room_name) }}" 
                                       required
                                       placeholder="Ex: Salle 101">
                                @error('room_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date et heure planifiées -->
                            <div class="col-md-6">
                                <label for="scheduled_at" class="form-label">
                                    Date et heure <span class="text-danger">*</span>
                                </label>
                                <input type="datetime-local" 
                                       class="form-control @error('scheduled_at') is-invalid @enderror" 
                                       id="scheduled_at" 
                                       name="scheduled_at" 
                                       value="{{ old('scheduled_at', $session->scheduled_at ? \Carbon\Carbon::parse($session->scheduled_at)->format('Y-m-d\TH:i') : '') }}" 
                                       required>
                                @error('scheduled_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Durée -->
                            <div class="col-md-6">
                                <label for="duration_minutes" class="form-label">
                                    Durée (minutes) <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control @error('duration_minutes') is-invalid @enderror" 
                                       id="duration_minutes" 
                                       name="duration_minutes" 
                                       value="{{ old('duration_minutes', $session->duration_minutes) }}" 
                                       required
                                       min="1"
                                       max="480"
                                       placeholder="Ex: 60">
                                <small class="form-text text-muted">Entre 1 et 480 minutes (8 heures max)</small>
                                @error('duration_minutes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nombre maximum de participants -->
                            <div class="col-md-6">
                                <label for="max_participants" class="form-label">
                                    Nombre maximum de participants
                                </label>
                                <input type="number" 
                                       class="form-control @error('max_participants') is-invalid @enderror" 
                                       id="max_participants" 
                                       name="max_participants" 
                                       value="{{ old('max_participants', $session->max_participants) }}"
                                       min="1"
                                       placeholder="Laisser vide pour illimité">
                                <small class="form-text text-muted">Laisser vide pour un nombre illimité</small>
                                @error('max_participants')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Paramètres avancés -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="fas fa-cog me-2 text-primary"></i>Paramètres avancés</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- Visibilité -->
                            <div class="col-md-12">
                                <label class="form-label">
                                    Visibilité <span class="text-danger">*</span>
                                </label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input @error('is_public') is-invalid @enderror" 
                                               type="radio" 
                                               name="is_public" 
                                               id="is_public_yes" 
                                               value="1" 
                                               {{ old('is_public', $session->is_public) == 1 ? 'checked' : '' }}
                                               required>
                                        <label class="form-check-label" for="is_public_yes">
                                            <i class="fas fa-globe text-success me-1"></i> Publique
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input @error('is_public') is-invalid @enderror" 
                                               type="radio" 
                                               name="is_public" 
                                               id="is_public_no" 
                                               value="0" 
                                               {{ old('is_public', $session->is_public) == 0 ? 'checked' : '' }}
                                               required>
                                        <label class="form-check-label" for="is_public_no">
                                            <i class="fas fa-lock text-warning me-1"></i> Privée
                                        </label>
                                    </div>
                                </div>
                                @error('is_public')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            
                        </div>
                    </div>
                </div>

                <!-- Informations sur le statut actuel -->
                <div class="card border-0 shadow-sm mb-4 bg-light">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <p class="mb-1 text-muted small">Statut </p>
                                 <select class="form-control @error('status') is-invalid @enderror" 
                                        id="status" 
                                        name="status" 
                                        required>
                                    <option value="">Sélectionner un statut</option>
                                    <option value="pending" @selected($session->status=='pending')>En attente</option>
                                    <option value="scheduled" @selected($session->status=='scheduled')>A venir</option>
                                    <option value="live" @selected($session->status=='live')>En direct</option>
                                    <option value="ended" @selected($session->status=='ended')>Terminée</option>
                                    <option value="cancelled" @selected($session->status=='cancelled')>Annulée</option>
                            
                                  
                                </select>
                            </div>
                            @if($session->started_at)
                            <div class="col-md-4">
                                <p class="mb-1 text-muted small">Démarrée le</p>
                                <p class="mb-0">{{ \Carbon\Carbon::parse($session->started_at)->format('d/m/Y à H:i') }}</p>
                            </div>
                            @endif
                            @if($session->ended_at)
                            <div class="col-md-4">
                                <p class="mb-1 text-muted small">Terminée le</p>
                                <p class="mb-0">{{ \Carbon\Carbon::parse($session->ended_at)->format('d/m/Y à H:i') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('sessions.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                            <div>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save me-2"></i>Valider
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts_bottom')
<script>
    // Prévisualisation de l'image
    document.getElementById('live_cover').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.createElement('img');
                preview.src = e.target.result;
                preview.className = 'img-thumbnail mt-2';
                preview.style.maxWidth = '300px';
                
                // Retirer l'ancienne prévisualisation si elle existe
                const oldPreview = document.querySelector('.preview-image');
                if (oldPreview) {
                    oldPreview.remove();
                }
                
                preview.className += ' preview-image';
                document.getElementById('live_cover').parentElement.appendChild(preview);
            };
            reader.readAsDataURL(file);
        }
    });

    // Validation côté client
    document.querySelector('form').addEventListener('submit', function(e) {
        const scheduledAt = new Date(document.getElementById('scheduled_at').value);
        const now = new Date();
        
        if (scheduledAt < now) {
            e.preventDefault();
            alert('La date de planification doit être dans le futur.');
            document.getElementById('scheduled_at').focus();
        }
    });
</script>

@endpush
@endsection
