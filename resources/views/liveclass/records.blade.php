@extends(getTemplate() .'.panel.layouts.panel_layout')
@push('styles_top')
      <script src="https://cdn.tailwindcss.com"></script>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        
        <!-- En-tête -->
        <div class="mb-6">
            <a href="{{ route('live-classes.show', $liveClass) }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
                ← Retour au live class
            </a>
            <h1 class="text-3xl font-bold">Enregistrements - {{ $liveClass->title }}</h1>
        </div>

        <!-- Liste des enregistrements -->
        @if($recordings->isEmpty())
            <div class="bg-white shadow-md rounded-lg p-12 text-center">
                <div class="text-6xl mb-4">🎥</div>
                <h2 class="text-2xl font-bold text-gray-700 mb-2">Aucun enregistrement disponible</h2>
                <p class="text-gray-600">
                    @if($liveClass->auto_record)
                        L'enregistrement est activé. Les vidéos apparaîtront ici après le live.
                    @else
                        L'enregistrement automatique n'est pas activé pour ce live class.
                    @endif
                </p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($recordings as $recording)
                    <div class="bg-white shadow-md rounded-lg overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <!-- Informations de l'enregistrement -->
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <h3 class="text-xl font-bold mr-3">{{ $recording->file_name }}</h3>
                                        
                                        @if($recording->status === 'completed')
                                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                                ✓ Disponible
                                            </span>
                                        @elseif($recording->status === 'processing')
                                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">
                                                ⏳ En cours...
                                            </span>
                                        @else
                                            <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">
                                                ✗ Échec
                                            </span>
                                        @endif
                                    </div>

                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm text-gray-600 mb-4">
                                        <div>
                                            <span class="font-semibold">Taille:</span>
                                            {{ $recording->file_size_human }}
                                        </div>
                                        <div>
                                            <span class="font-semibold">Durée:</span>
                                            {{ $recording->duration_human }}
                                        </div>
                                        <div>
                                            <span class="font-semibold">Créé le:</span>
                                            {{ $recording->created_at->format('d/m/Y H:i') }}
                                        </div>
                                        @if($recording->completed_at)
                                            <div>
                                                <span class="font-semibold">Complété le:</span>
                                                {{ $recording->completed_at->format('d/m/Y H:i') }}
                                            </div>
                                        @endif
                                    </div>

                                    @if($recording->status === 'completed')
                                        <!-- Lecteur vidéo intégré -->
                                        <div class="mb-4">
                                            <video 
                                                controls 
                                                class="w-full max-w-3xl rounded-lg shadow-lg"
                                                preload="metadata"
                                            >
                                                <source src="{{ $recording->stream_url }}" type="video/mp4">
                                                Votre navigateur ne supporte pas la lecture vidéo.
                                            </video>
                                        </div>

                                        <!-- Actions -->
                                        <div class="flex gap-3">
                                            <a 
                                                href="{{ $recording->download_url }}"
                                                class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white font-semibold rounded"
                                                download
                                            >
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                </svg>
                                                Télécharger
                                            </a>

                                            @can('update', $liveClass)
                                                <form 
                                                    action="{{ route('live-classes.recordings.destroy', [$liveClass, $recording]) }}" 
                                                    method="POST"
                                                    onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet enregistrement ?')"
                                                >
                                                    @csrf
                                                    @method('DELETE')
                                                    <button 
                                                        type="submit"
                                                        class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-700 text-white font-semibold rounded"
                                                    >
                                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                        Supprimer
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Informations sur l'enregistrement automatique -->
        @can('update', $liveClass)
            <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h3 class="font-bold text-lg mb-2">ℹ️ À propos de l'enregistrement automatique</h3>
                <ul class="text-sm text-gray-700 space-y-2">
                    <li>• Les enregistrements sont automatiquement sauvegardés dans <code class="bg-white px-2 py-1 rounded">/srv/recordings/</code></li>
                    <li>• Le traitement peut prendre quelques minutes après la fin du live</li>
                    <li>• Les enregistrements sont accessibles uniquement aux participants inscrits</li>
                    <li>• Vous pouvez télécharger ou supprimer les enregistrements à tout moment</li>
                </ul>
            </div>
        @endcan
    </div>
</div>
@endsection