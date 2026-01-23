@extends(getTemplate() .'.panel.layouts.panel_layout')
@push('styles_top')
      <script src="https://cdn.tailwindcss.com"></script>
@endpush


@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Créer un Live Class</h1>

        <form action="{{ route('live-classes.store') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8">
            @csrf

            <!-- Titre -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                    Titre du Live Class *
                </label>
                <input 
                    type="text" 
                    name="title" 
                    id="title"
                    value="{{ old('title') }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('title') border-red-500 @enderror"
                    required
                >
                @error('title')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                    Description
                </label>
                <textarea 
                    name="description" 
                    id="description"
                    rows="4"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror"
                >{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date et heure -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="scheduled_at">
                    Date et heure du Live *
                </label>
                <input 
                    type="datetime-local" 
                    name="scheduled_at" 
                    id="scheduled_at"
                    value="{{ old('scheduled_at') }}"
                    min="{{ now()->format('Y-m-d\TH:i') }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('scheduled_at') border-red-500 @enderror"
                    required
                >
                @error('scheduled_at')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Durée -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="duration_minutes">
                    Durée (en minutes) *
                </label>
                <select 
                    name="duration_minutes" 
                    id="duration_minutes"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('duration_minutes') border-red-500 @enderror"
                    required
                >
                    <option value="30" {{ old('duration_minutes') == 30 ? 'selected' : '' }}>30 minutes</option>
                    <option value="45" {{ old('duration_minutes') == 45 ? 'selected' : '' }}>45 minutes</option>
                    <option value="60" {{ old('duration_minutes') == 60 ? 'selected' : '' }}>1 heure</option>
                    <option value="90" {{ old('duration_minutes') == 90 ? 'selected' : '' }}>1h30</option>
                    <option value="120" {{ old('duration_minutes') == 120 ? 'selected' : '' }}>2 heures</option>
                    <option value="180" {{ old('duration_minutes') == 180 ? 'selected' : '' }}>3 heures</option>
                </select>
                @error('duration_minutes')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nombre max de participants -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="max_participants">
                    Nombre maximum de participants (optionnel)
                </label>
                <input 
                    type="number" 
                    name="max_participants" 
                    id="max_participants"
                    value="{{ old('max_participants', 100) }}"
                    min="1"
                    max="1000"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('max_participants') border-red-500 @enderror"
                >
                @error('max_participants')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Type d'accès -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Type d'accès *
                </label>
                <div class="space-y-2">
                    <label class="flex items-start">
                        <input 
                            type="radio" 
                            name="is_public" 
                            value="0"
                            {{ old('is_public', '0') == '0' ? 'checked' : '' }}
                            class="mt-1 mr-2"
                        >
                        <div>
                            <div class="font-semibold">Privé - Apprenants inscrits uniquement</div>
                            <div class="text-sm text-gray-600">Seuls les apprenants inscrits sur la plateforme pourront rejoindre</div>
                        </div>
                    </label>
                    
                    <label class="flex items-start">
                        <input 
                            type="radio" 
                            name="is_public" 
                            value="1"
                            {{ old('is_public') == '1' ? 'checked' : '' }}
                            class="mt-1 mr-2"
                        >
                        <div>
                            <div class="font-semibold">Public - Avec lien de partage</div>
                            <div class="text-sm text-gray-600">Un lien de partage sera généré pour permettre à des invités externes de rejoindre</div>
                        </div>
                    </label>
                </div>
                @error('is_public')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Enregistrement automatique -->
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Enregistrement automatique *
                </label>
                <div class="space-y-2">
                    <label class="flex items-start">
                        <input 
                            type="radio" 
                            name="auto_record" 
                            value="1"
                            {{ old('auto_record', '1') == '1' ? 'checked' : '' }}
                            class="mt-1 mr-2"
                        >
                        <div>
                            <div class="font-semibold">Activer l'enregistrement automatique</div>
                            <div class="text-sm text-gray-600">Le live sera automatiquement enregistré et disponible après la session</div>
                        </div>
                    </label>
                    
                    <label class="flex items-start">
                        <input 
                            type="radio" 
                            name="auto_record" 
                            value="0"
                            {{ old('auto_record') == '0' ? 'checked' : '' }}
                            class="mt-1 mr-2"
                        >
                        <div>
                            <div class="font-semibold">Pas d'enregistrement</div>
                            <div class="text-sm text-gray-600">Le live ne sera pas enregistré</div>
                        </div>
                    </label>
                </div>
                @error('auto_record')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Boutons -->
            <div class="flex items-center justify-between">
                <a href="{{ route('live-classes.index') }}" class="text-gray-600 hover:text-gray-800">
                    Annuler
                </a>
                <button 
                    type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                >
                    Créer le Live Class
                </button>
            </div>
        </form>
    </div>
</div>
@endsection