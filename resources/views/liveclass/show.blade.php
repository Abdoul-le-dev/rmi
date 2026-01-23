@extends(getTemplate() .'.panel.layouts.panel_layout')
@push('styles_top')
      <script src="https://cdn.tailwindcss.com"></script>
@endpush



@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        
        <!-- Statut du Live -->
        <div class="mb-6">
            @if($liveClass->status === 'live')
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                    <span class="w-2 h-2 bg-red-500 rounded-full mr-2 animate-pulse"></span>
                    EN DIRECT
                </span>
            @elseif($liveClass->status === 'scheduled')
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                    📅 Programmé
                </span>
            @elseif($liveClass->status === 'ended')
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gray-100 text-gray-800">
                    ✓ Terminé
                </span>
            @endif
        </div>

        <!-- En-tête -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h1 class="text-3xl font-bold mb-2">{{ $liveClass->title }}</h1>
            
            <div class="flex items-center text-gray-600 mb-4">
                <span class="mr-4">
                    👨‍🏫 {{ $liveClass->instructor->name }}
                </span>
                <span class="mr-4">
                    📅 {{ $liveClass->scheduled_at->format('d/m/Y à H:i') }}
                </span>
                <span>
                    ⏱️ {{ $liveClass->duration_minutes }} minutes
                </span>
            </div>

            @if($liveClass->description)
                <p class="text-gray-700 mb-4">{{ $liveClass->description }}</p>
            @endif

            <!-- Inscrits -->
            <div class="flex items-center text-sm text-gray-600">
                <span>👥 {{ $enrolledCount }} inscrit(s)</span>
                @if($liveClass->max_participants)
                    <span class="ml-2">/ {{ $liveClass->max_participants }} max</span>
                @endif
            </div>
        </div>

        <!-- Actions selon le rôle -->
        @if(Auth::id() === $liveClass->instructor_id)
            <!-- Actions INSTRUCTEUR -->
            <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                <h2 class="text-xl font-bold mb-4">Actions Instructeur</h2>

                @if($liveClass->status === 'scheduled')
                    @if($liveClass->can_start)
                        <form action="{{ route('live-classes.start', $liveClass) }}" method="POST" class="inline">
                            @csrf
                            <button 
                                type="submit"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg text-lg"
                            >
                                🎥 Lancer le Live
                            </button>
                        </form>
                    @else
                        <p class="text-gray-600">
                            ⏰ Vous pourrez démarrer le live 15 minutes avant l'heure prévue
                            <br>
                            <span class="text-sm">
                                ({{ $liveClass->scheduled_at->subMinutes(15)->diffForHumans() }})
                            </span>
                        </p>
                    @endif
                @elseif($liveClass->status === 'live')
                    <div class="space-y-3">
                        <form action="{{ route('live-classes.start', $liveClass) }}" method="POST">
                            @csrf
                            <button 
                                type="submit"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg text-lg w-full"
                            >
                                🎥 Rejoindre le Live
                            </button>
                        </form>

                        <form action="{{ route('live-classes.end', $liveClass) }}" method="POST">
                            @csrf
                            <button 
                                type="submit"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg w-full"
                                onclick="return confirm('Êtes-vous sûr de vouloir terminer le live ?')"
                            >
                                ⏹️ Terminer le Live
                            </button>
                        </form>
                    </div>
                @endif

                <!-- Lien public -->
                @if($liveClass->is_public && $liveClass->public_url)
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <h3 class="font-bold mb-2">🔗 Lien de partage public</h3>
                        <div class="flex items-center">
                            <input 
                                type="text" 
                                value="{{ $liveClass->public_url }}" 
                                readonly
                                class="flex-1 p-2 border rounded-l bg-white"
                                id="publicUrl"
                            >
                            <button 
                                onclick="copyToClipboard()"
                                class="bg-blue-500 text-white px-4 py-2 rounded-r hover:bg-blue-600"
                            >
                                📋 Copier
                            </button>
                        </div>
                        <p class="text-sm text-gray-600 mt-2">
                            Partagez ce lien pour permettre à des invités externes de rejoindre le live
                        </p>
                    </div>
                @endif

                <!-- Enregistrements -->
                @if($liveClass->recordings()->exists())
                    <div class="mt-6">
                        <a 
                            href="{{ route('live-classes.recordings.index', $liveClass) }}"
                            class="inline-flex items-center px-4 py-2 bg-purple-500 hover:bg-purple-700 text-white font-bold rounded-lg"
                        >
                            🎥 Voir les enregistrements ({{ $liveClass->recordings()->count() }})
                        </a>
                    </div>
                @endif
            </div>

            <!-- Liste des inscrits -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-bold mb-4">Participants inscrits ({{ $enrolledCount }})</h2>
                
                @if($liveClass->enrollments->isEmpty())
                    <p class="text-gray-600">Aucun participant inscrit pour le moment.</p>
                @else
                    <ul class="divide-y">
                        @foreach($liveClass->enrollments as $enrollment)
                            <li class="py-3 flex items-center justify-between">
                                <div>
                                    <div class="font-semibold">{{ $enrollment->user->name }}</div>
                                    <div class="text-sm text-gray-600">{{ $enrollment->user->email }}</div>
                                </div>
                                <div class="text-sm text-gray-500">
                                    Inscrit le {{ $enrollment->enrolled_at->format('d/m/Y') }}
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

        @else
            <!-- Actions APPRENANT -->
            <div class="bg-white shadow-md rounded-lg p-6">
                @if($liveClass->status === 'live')
                    <form action="{{ route('live-classes.join', $liveClass) }}" method="POST">
                        @csrf
                        <button 
                            type="submit"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg text-lg w-full"
                        >
                            🎥 Rejoindre le Live
                        </button>
                    </form>
                @elseif($liveClass->status === 'scheduled')
                    @if($isEnrolled)
                        <div class="text-center">
                            <p class="text-green-600 font-semibold mb-4">✓ Vous êtes inscrit à ce live</p>
                            <p class="text-gray-600 text-sm">
                                Vous recevrez une notification quand le live démarrera
                            </p>
                            <form action="{{ route('live-classes.unenroll', $liveClass) }}" method="POST" class="mt-4">
                                @csrf
                                <button 
                                    type="submit"
                                    class="text-red-600 hover:text-red-800 text-sm"
                                    onclick="return confirm('Êtes-vous sûr de vouloir vous désinscrire ?')"
                                >
                                    Se désinscrire
                                </button>
                            </form>
                        </div>
                    @else
                        @if($liveClass->canEnroll())
                            <form action="{{ route('live-classes.enroll', $liveClass) }}" method="POST">
                                @csrf
                                <button 
                                    type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg text-lg w-full"
                                >
                                    S'inscrire au Live
                                </button>
                            </form>
                        @else
                            <p class="text-red-600 text-center">
                                Les inscriptions sont complètes ou fermées
                            </p>
                        @endif
                    @endif
                @elseif($liveClass->status === 'ended')
                    <p class="text-gray-600 text-center">Ce live est terminé</p>
                @endif
            </div>
        @endif
    </div>
</div>

<script>
function copyToClipboard() {
    const input = document.getElementById('publicUrl');
    input.select();
    document.execCommand('copy');
    alert('Lien copié dans le presse-papier !');
}
</script>
@endsection