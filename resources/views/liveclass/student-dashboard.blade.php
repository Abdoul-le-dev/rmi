@extends(getTemplate() .'.panel.layouts.panel_layout')
@push('styles_top')
      <script src="https://cdn.tailwindcss.com"></script>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Mes Live Classes</h1>

    <!-- Lives en cours -->
    @if($liveClasses->isNotEmpty())
        <div class="mb-8">
            <h2 class="text-2xl font-bold mb-4 text-red-600">🔴 Lives en cours</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($liveClasses as $liveClass)
                    <div class="bg-white shadow-md rounded-lg p-6 border-2 border-red-500">
                        <div class="flex items-center mb-2">
                            <span class="w-3 h-3 bg-red-500 rounded-full mr-2 animate-pulse"></span>
                            <span class="text-red-600 font-bold">EN DIRECT</span>
                        </div>
                        
                        <h3 class="text-xl font-bold mb-2">{{ $liveClass->title }}</h3>
                        <p class="text-gray-600 text-sm mb-4">
                            Par {{ $liveClass->instructor->name }}
                        </p>

                        <form action="{{ route('live-classes.join', $liveClass) }}" method="POST">
                            @csrf
                            <button 
                                type="submit"
                                class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                            >
                                🎥 Rejoindre maintenant
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Lives à venir -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold mb-4">📅 Prochains Lives</h2>
        
        @if($upcomingClasses->isEmpty())
            <div class="bg-gray-50 rounded-lg p-8 text-center">
                <p class="text-gray-600">Vous n'êtes inscrit à aucun live à venir</p>
            </div>
        @else
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($upcomingClasses as $liveClass)
                    <div class="bg-white shadow-md rounded-lg p-6">
                        <h3 class="text-xl font-bold mb-2">{{ $liveClass->title }}</h3>
                        
                        <div class="text-sm text-gray-600 mb-4 space-y-1">
                            <p>👨‍🏫 {{ $liveClass->instructor->name }}</p>
                            <p>📅 {{ $liveClass->scheduled_at->format('d/m/Y à H:i') }}</p>
                            <p>⏱️ {{ $liveClass->duration_minutes }} minutes</p>
                        </div>

                        <div class="mb-4 p-3 bg-blue-50 rounded">
                            <p class="text-sm font-semibold text-blue-800">
                                {{ $liveClass->scheduled_at->diffForHumans() }}
                            </p>
                        </div>

                        <a 
                            href="{{ route('live-classes.show', $liveClass) }}"
                            class="block w-full text-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                        >
                            Voir les détails
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Lives passés -->
    @if($pastClasses->isNotEmpty())
        <div>
            <h2 class="text-2xl font-bold mb-4">📼 Lives passés</h2>
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Titre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Instructeur</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pastClasses as $liveClass)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="font-semibold">{{ $liveClass->title }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $liveClass->instructor->name }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $liveClass->ended_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <a 
                                        href="{{ route('live-classes.show', $liveClass) }}"
                                        class="text-blue-600 hover:text-blue-800 text-sm"
                                    >
                                        Voir les détails
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection