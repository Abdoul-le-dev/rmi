<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>VIP RMI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @include('vip.composants.css')


</head>

<body class="bg-gray-50 text-gray-900 antialiased">

    <!-- ========================================
         HEADER PRINCIPAL
    ======================================== -->

    @include('vip.composants.header')

    <!-- ========================================
         LAYOUT PRINCIPAL (3 COLONNES)
    ======================================== -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- ========================================
                 SIDEBAR GAUCHE - NAVIGATION
            ======================================== -->
            @include('vip.composants.sidebar_gauche')

            <!-- ========================================
                 COLONNE CENTRALE - FEED
            ======================================== -->

            <main class="lg:col-span-6 main" id="menu_menu">

                <!-- Zone de création de post -->
                <div class="bg-white shadow-sm border border-gray-200 p-6 mb-6 max-w-2xl  mx-auto space-y-4">
                    <!-- Header -->

                    <div class="mb-4">
                        <!-- Ligne du haut -->
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm sm:text-base md:text-lg font-bold text-gray-900">Bienvenue {{ $userData['user_name'] }} 👋</h3>

                            <!-- Actions rapides -->
                            <div class="flex items-center gap-2">
                                <button onclick="toggleDrafts()"
                                    class="hidden md:block p-2 rounded-lg hover:bg-gray-100">
                                    <i class="fas fa-file-alt text-gray-600"></i>
                                    <span id="draft-count" class="post-badge hidden">0</span>
                                </button>
                                <button onclick="toggleTemplates()" class="p-2 rounded-lg hover:bg-gray-100">
                                    <i class="fas fa-bookmark text-gray-600"></i>
                                </button>
                                <button onclick="togglePreview()" class="p-2 rounded-lg hover:bg-gray-100">
                                    <i class="fas fa-eye text-gray-600"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Ligne du bas -->
                        <h3 class="text-sm font-medium text-gray-900 mt-1 mb-3">
                            À quoi pensez-vous aujourd’hui ?
                        </h3>
                        <p class="text-xs text-gray-500">
                            Advertising ...
                        </p>
                    </div>


                    <!-- Dropdown Brouillons -->
                    <div id="drafts-dropdown"
                        class="post-dropdown hidden mb-4 bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="font-semibold text-gray-900 text-sm">Brouillons sauvegardés</h3>
                            <button onclick="clearAllDrafts()"
                                class="text-xs text-red-600 hover:text-red-700 transition-colors">
                                <i class="fas fa-trash mr-1"></i>Tout supprimer
                            </button>
                        </div>
                        <div id="drafts-list" class="space-y-2 max-h-48 overflow-y-auto post-scrollbar"></div>
                    </div>

                    <!-- Dropdown Templates -->
                    <div id="templates-dropdown"
                        class="post-dropdown hidden mb-4 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-lg p-4 border border-indigo-200">
                        <h3 class="font-semibold text-gray-900 text-sm mb-3">Templates de trading</h3>
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" onclick="useTemplate('analysis')"
                                class="template-card p-3 bg-white rounded-lg hover:bg-indigo-50 transition-all text-left border border-indigo-100">
                                <div class="flex items-center gap-2 mb-1">
                                    <i class="fas fa-chart-line text-indigo-600 text-sm"></i>
                                    <span class="font-semibold text-sm">Analyse technique</span>
                                </div>
                                <p class="text-xs text-gray-600">Structure pour une analyse de marché</p>
                            </button>

                            <button type="button" onclick="useTemplate('trade')"
                                class="template-card p-3 bg-white rounded-lg hover:bg-green-50 transition-all text-left border border-green-100">
                                <div class="flex items-center gap-2 mb-1">
                                    <i class="fas fa-exchange-alt text-green-600 text-sm"></i>
                                    <span class="font-semibold text-sm">Rapport de trade</span>
                                </div>
                                <p class="text-xs text-gray-600">Partager un trade gagnant/perdant</p>
                            </button>

                            <button type="button" onclick="useTemplate('question')"
                                class="template-card p-3 bg-white rounded-lg hover:bg-orange-50 transition-all text-left border border-orange-100">
                                <div class="flex items-center gap-2 mb-1">
                                    <i class="fas fa-question-circle text-orange-600 text-sm"></i>
                                    <span class="font-semibold text-sm">Question</span>
                                </div>
                                <p class="text-xs text-gray-600">Poser une question à la communauté</p>
                            </button>

                            <button type="button" onclick="useTemplate('tip')"
                                class="template-card p-3 bg-white rounded-lg hover:bg-yellow-50 transition-all text-left border border-yellow-100">
                                <div class="flex items-center gap-2 mb-1">
                                    <i class="fas fa-lightbulb text-yellow-600 text-sm"></i>
                                    <span class="font-semibold text-sm">Conseil/Astuce</span>
                                </div>
                                <p class="text-xs text-gray-600">Partager un conseil de trading</p>
                            </button>
                        </div>
                    </div>

                    <!-- Preview Panel -->
                    <div id="preview-panel"
                        class="post-dropdown hidden mb-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-200">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="font-semibold text-gray-900 text-sm flex items-center gap-2">
                                <i class="fas fa-eye text-indigo-600"></i>Aperçu de votre publication
                            </h3>
                            <button onclick="togglePreview()"
                                class="text-gray-600 hover:text-gray-900 transition-colors">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div id="preview-content" class="bg-white rounded-lg p-4 border border-gray-200"></div>
                    </div>

                    <!-- Formulaire -->
                    <form id="post-form" onsubmit="sendMessage(event); return false;" class="space-y-4" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div>
                            <!-- Textarea -->
                            <div class="relative">
                                <textarea id="post-textarea" name="contents"
                                    placeholder="Partagez vos idées, vos perfomances ..."
                                    class="w-full p-4 pr-16 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none transition-all duration-300"
                                    rows="3" oninput="handleTextInput()" onkeydown="handleKeyPress(event)"></textarea>

                                <!-- Compteur -->
                                <div class="hidden md:block absolute bottom-3 right-3 flex items-center gap-2">
                                    <span id="char-counter" class="text-xs text-gray-400 transition-all duration-300">0
                                        / 5000</span>
                                    <div class="w-8 h-8">
                                        <svg class="post-progress-svg" width="32" height="32">
                                            <circle cx="16" cy="16" r="14" stroke="#e5e7eb" stroke-width="2"
                                                fill="none" />
                                            <circle id="char-progress-circle" class="post-progress-circle" cx="16"
                                                cy="16" r="14" stroke="#4f46e5" stroke-width="2" fill="none" />
                                        </svg>
                                    </div>
                                </div>

                                <!-- Suggestions mentions -->
                                <div id="mention-suggestions"
                                    class="post-suggestions hidden absolute z-50 bg-white border border-gray-200 rounded-lg shadow-xl mt-1 max-h-48 overflow-y-auto">
                                </div>

                                <!-- Suggestions hashtags -->
                                <div id="hashtag-suggestions"
                                    class="post-suggestions hidden absolute z-50 bg-white border border-gray-200 rounded-lg shadow-xl mt-1 max-h-48 overflow-y-auto">
                                </div>
                            </div>

                            <!-- Media Preview -->
                            <div id="media-preview" class="post-media-grid hidden mt-3 grid grid-cols-3 gap-2"></div>

                            <!-- Drop Zone -->
                            <div id="drop-zone"
                                class="post-dropzone hidden mt-3 border-2 border-dashed border-indigo-300 rounded-lg p-8 text-center bg-indigo-50 transition-all">
                                <i class="fas fa-cloud-upload-alt post-dropzone-icon text-4xl text-indigo-400 mb-2"></i>
                                <p class="text-sm text-indigo-600 font-semibold">Déposez vos fichiers ici</p>
                                <p class="text-xs text-gray-500 mt-1">Images, vidéos ou GIFs</p>
                            </div>

                            <!-- Link Preview -->
                            <div id="link-preview"
                                class="post-link-preview hidden mt-3 border border-gray-200 rounded-lg overflow-hidden hover:border-indigo-300 transition-all">
                                <div class="flex"> <img id="link-preview-image"
                                        class="w-32 h-32 object-cover post-link-img" src="" alt="">
                                    <div class="flex-1 p-3">
                                        <h4 id="link-preview-title" class="font-semibold text-sm text-gray-900 mb-1">
                                        </h4>
                                        <p id="link-preview-description"
                                            class="text-xs text-gray-600 line-clamp-2 mb-2"></p>
                                        <p id="link-preview-url" class="text-xs text-indigo-600"></p>
                                    </div>
                                    <button onclick="removeLinkPreview()"
                                        class="p-2 text-gray-400 hover:text-gray-600 transition-colors">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Upload Progress -->
                            <div id="upload-progress" class="post-upload-progress hidden mt-3">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs text-gray-600">Upload en cours...</span>
                                    <span id="upload-percentage" class="text-xs text-indigo-600 font-semibold">0%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                    <div id="upload-progress-bar"
                                        class="post-upload-bar bg-gradient-to-r from-indigo-500 to-purple-500 h-2 rounded-full transition-all duration-300"
                                        style="width: 0%"></div>
                                </div>
                            </div>

                            <!-- Poll Container -->
                            <div id="poll-container"
                                class="post-dropdown hidden mt-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-semibold text-sm text-gray-900">Créer un sondage</h4>
                                    <button type="button" onclick="removePoll()"
                                        class="text-gray-400 hover:text-gray-600 transition-colors">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <input type="text" id="poll-question" name="poll[question]"
                                    placeholder="Quelle est votre question ?"
                                    class="w-full p-2 border border-gray-300 rounded-lg text-sm mb-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                                <div id="poll-options" class="space-y-2">
                                    <input type="text" placeholder="Option 1" name="poll[option_1]"
                                        class="w-full p-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                                    <input type="text" placeholder="Option 2" name="poll[option_2]"
                                        class="w-full p-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                                </div>
                                <button type="button" onclick="addPollOption()"
                                    class="mt-2 text-xs text-indigo-600 hover:text-indigo-700 font-semibold transition-colors">
                                    <i class="fas fa-plus mr-1"></i>Ajouter une option
                                </button>
                            </div>

                            <!-- Schedule Container -->
                            <div id="schedule-container"
                                class="post-dropdown post-schedule hidden mt-3 p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg border border-purple-200">
                                <div class="flex items-center justify-between mb-3 relative z-10">
                                    <h4 class="font-semibold text-sm text-gray-900 flex items-center gap-2">
                                        <i class="fas fa-clock text-purple-600"></i>Planifier la publication
                                    </h4>
                                    <button type="button" onclick="removeSchedule()"
                                        class="text-gray-400 hover:text-gray-600 transition-colors">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="grid grid-cols-2 gap-3 relative z-10">
                                    <input type="date" id="schedule-date" name="scheduled_at_date"
                                        class="p-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all">
                                    <input type="time" id="schedule-time" name="scheduled_at_time"
                                        class="p-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all">
                                </div>
                                <p class="text-xs text-gray-600 mt-2 relative z-10">
                                    <i class="fas fa-info-circle text-purple-600 mr-1"></i>
                                    Votre post sera publié automatiquement à la date et l'heure choisies
                                </p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <!-- Emoji Picker -->
                                <div class="relative">
                                    <button type="button" onclick="toggleEmojiPicker()"
                                        class="hidden md:flex  p-2 rounded-lg hover:bg-gray-100 transition-colors"
                                        title="Emoji">
                                        <i class="far fa-smile text-gray-600 text-lg"></i>
                                    </button>

                                    <div id="emoji-picker"
                                        class="post-emoji-picker hidden absolute z-50 bg-white border border-gray-200 rounded-lg shadow-xl p-3 mt-2 w-80">
                                        <input type="text" id="emoji-search" placeholder="Rechercher..."
                                            class="w-full p-2 border border-gray-300 rounded-lg text-sm mb-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                            oninput="searchEmojis()">

                                        <div class="flex gap-2 mb-3 overflow-x-auto">
                                            <button type="button" onclick="filterEmojis('recent')"
                                                class="emoji-cat px-3 py-1 text-xs rounded-full bg-gray-100 hover:bg-indigo-100 transition-colors whitespace-nowrap">
                                                <i class="fas fa-clock"></i>
                                            </button>
                                            <button type="button" onclick="filterEmojis('smileys')"
                                                class="emoji-cat px-3 py-1 text-xs rounded-full bg-gray-100 hover:bg-indigo-100 transition-colors whitespace-nowrap">
                                                😀
                                            </button>
                                            <button type="button" onclick="filterEmojis('trading')"
                                                class="emoji-cat emoji-cat-active px-3 py-1 text-xs rounded-full bg-indigo-100 text-indigo-700 transition-colors whitespace-nowrap">
                                                📈
                                            </button>
                                            <button type="button" onclick="filterEmojis('symbols')"
                                                class="emoji-cat px-3 py-1 text-xs rounded-full bg-gray-100 hover:bg-indigo-100 transition-colors whitespace-nowrap">
                                                ⚡
                                            </button>
                                        </div>

                                        <div id="emoji-grid"
                                            class="grid grid-cols-8 gap-2 max-h-64 overflow-y-auto post-scrollbar">
                                            <button type="button" onclick="insertEmoji('📈')"
                                                class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all"
                                                title="Hausse">📈</button>
                                            <button type="button" onclick="insertEmoji('📉')"
                                                class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all"
                                                title="Baisse">📉</button>
                                            <button type="button" onclick="insertEmoji('💰')"
                                                class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all"
                                                title="Argent">💰</button>
                                            <button type="button" onclick="insertEmoji('💎')"
                                                class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all"
                                                title="Diamant">💎</button>
                                            <button type="button" onclick="insertEmoji('🚀')"
                                                class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all"
                                                title="Fusée">🚀</button>
                                            <button type="button" onclick="insertEmoji('💪')"
                                                class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all"
                                                title="Force">💪</button>
                                            <button type="button" onclick="insertEmoji('🔥')"
                                                class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all"
                                                title="Feu">🔥</button>
                                            <button type="button" onclick="insertEmoji('⚡')"
                                                class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all"
                                                title="Éclair">⚡</button>
                                            <button type="button" onclick="insertEmoji('🎯')"
                                                class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all"
                                                title="Cible">🎯</button>
                                            <button type="button" onclick="insertEmoji('💡')"
                                                class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all"
                                                title="Idée">💡</button>
                                            <button type="button" onclick="insertEmoji('✨')"
                                                class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all"
                                                title="Étoiles">✨</button>
                                            <button type="button" onclick="insertEmoji('🎉')"
                                                class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all"
                                                title="Fête">🎉</button>
                                            <button type="button" onclick="insertEmoji('👍')"
                                                class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all"
                                                title="Pouce">👍</button>
                                            <button type="button" onclick="insertEmoji('❤️')"
                                                class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all"
                                                title="Coeur">❤️</button>
                                            <button type="button" onclick="insertEmoji('😀')"
                                                class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all"
                                                title="Sourire">😀</button>
                                            <button type="button" onclick="insertEmoji('😊')"
                                                class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all"
                                                title="Heureux">😊</button>
                                            <button type="button" onclick="insertEmoji('🤔')"
                                                class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all"
                                                title="Réfléchir">🤔</button>
                                            <button type="button" onclick="insertEmoji('😎')"
                                                class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all"
                                                title="Cool">😎</button>
                                            <button type="button" onclick="insertEmoji('🤑')"
                                                class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all"
                                                title="Argent">🤑</button>
                                            <button type="button" onclick="insertEmoji('😤')"
                                                class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all"
                                                title="Déterminé">😤</button>
                                            <button type="button" onclick="insertEmoji('📊')"
                                                class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all"
                                                title="Graphique">📊</button>
                                            <button type="button" onclick="insertEmoji('💼')"
                                                class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all"
                                                title="Business">💼</button>
                                        </div>

                                        <div id="recent-emojis" class="mt-3 pt-3 border-t border-gray-200">
                                            <p class="text-xs text-gray-500 mb-2">Récemment utilisés</p>
                                            <div class="flex gap-2"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Upload Image -->
                                <button type="button" onclick="document.getElementById('image-upload').click()"
                                    class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Image">
                                    <i class="far fa-image text-gray-600 text-lg"></i>
                                </button>
                                <input type="file" id="image-upload" accept="image/*" name="media[]" multiple
                                    class="hidden" onchange="handleImageUpload(event)">

                                <!-- Upload Video -->
                                <button type="button" onclick="document.getElementById('video-upload').click()"
                                    class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Vidéo">
                                    <i class="fas fa-video text-gray-600 text-lg"></i>
                                </button>
                                <input type="file" id="video-upload" accept="video/*" class="hidden" name="media[]"
                                    multiple onchange="handleVideoUpload(event)">

                                <!-- Poll -->
                                <button type="button" onclick="togglePoll()"
                                    class="hidden md:block p-2 rounded-lg hover:bg-gray-100 transition-colors"
                                    title="Sondage">
                                    <i class="fas fa-poll text-gray-600 text-lg"></i>
                                </button>

                                <!-- Schedule -->
                                <button type="button" onclick="toggleSchedule()"
                                    class="hidden lg:block p-2 rounded-lg hover:bg-gray-100 transition-colors"
                                    title="Planifier">
                                    <i class="fas fa-clock text-gray-600 text-lg"></i>
                                </button>

                                <!-- Divider -->
                                <div class="hidden md:block  h-6 w-px bg-gray-300"></div>

                                <!-- Formatting -->
                                <button type="button" onclick="formatText('bold')"
                                    class="hidden md:block p-2 rounded-lg hover:bg-gray-100 transition-colors"
                                    title="Gras">
                                    <i class="fas fa-bold text-gray-600"></i>
                                </button>
                                <button type="button" onclick="formatText('link')"
                                    class="hidden md:block p-2 rounded-lg hover:bg-gray-100 transition-colors"
                                    title="Lien">
                                    <i class="fas fa-link text-gray-600"></i>
                                </button>
                            </div>

                            <!-- Send Button -->
                            <button type="submit" id="send-button"
                                class="post-send-btn relative px-6 py-2 bg-indigo-600 text-white rounded-lg font-semibold text-sm hover:bg-indigo-700 transition-all overflow-hidden group">
                                <span
                                    class="relative z-10 flex items-center justify-center transition-all duration-300 group-hover:scale-110">
                                    <i id="send-icon" class="fas fa-paper-plane mr-2 transition-all duration-300"></i>
                                    <span id="send-text">Envoyer</span>
                                </span>
                                <div
                                    class="post-send-gradient absolute inset-0 bg-gradient-to-r from-indigo-700 via-purple-600 to-indigo-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                </div>
                                <div id="send-particles"
                                    class="post-particles absolute inset-0 pointer-events-none opacity-0">
                                    <div class="post-particle"></div>
                                    <div class="post-particle"></div>
                                    <div class="post-particle"></div>
                                    <div class="post-particle"></div>
                                    <div class="post-particle"></div>
                                </div>
                                <div id="confetti-container"
                                    class="post-confetti-container absolute inset-0 pointer-events-none opacity-0">
                                </div>
                            </button>
                        </div>
                    </form>

                    <!-- Typing Indicator -->
                    <div id="typing-indicator"
                        class="post-typing hidden flex items-center gap-2 text-sm text-gray-600 mt-4">
                        <div class="flex gap-1">
                            <span class="post-typing-dot w-2 h-2 bg-indigo-600 rounded-full"></span>
                            <span class="post-typing-dot w-2 h-2 bg-indigo-600 rounded-full"></span>
                            <span class="post-typing-dot w-2 h-2 bg-indigo-600 rounded-full"></span>
                        </div>
                        <span>Préparation de votre publication...</span>
                    </div>
                </div>

                <!-- Toast Container -->
                <div id="toast-container" class="fixed top-4 right-4 z-50 flex flex-col gap-2"></div>



                <!-- Posts Feed -->



                <div class="max-w-2xl mx-auto space-y-4">

                   @foreach ($posts as $post)

                     <!-- POST TEXTE -->
                    @if ($post->type =='text')

                     <article class="post-card rounded-xl overflow-hidden" data-post-id="{{ $post->id  }}">
                        <div class="p-4 border-b border-gray-100">
                            <div class="flex items-start gap-3">
                                <div class="profile-avatar">
                                   <div class="w-10 h-10 rounded-full border-white shadow-lg mx-auto overflow-hidden flex items-center justify-center {{ Auth::user() && Auth::user()->avatar ? '' : 'bg-gradient-to-br from-purple-500 to-pink-500' }}">

                                        @if(Auth::user() && Auth::user()->avatar)
                                            <img src="{{ $post->user->getAvatar(48) }}" alt="Avatar" class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover"  
                                            onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($post->user->full_name) }}&background=random'">
                                           
                                        @else
                                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold">
                                               {{ strtoupper(substr($post->user->full_name ?? $post->user->full_name , 0, 2)) }}
                                            </div>
                                        @endif
                                    </div>

                                    <div class="profile-tooltip">
                                        <div class="text-xs text-gray-500 mb-1">Performance</div>
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-xs text-gray-600">Généré</span>
                                            <span class="text-sm font-semibold text-gray-900">${{ $post->montant }}</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-600">Plaque</span>

                                        @switch($post->plaque)
                                            @case('bronze')
                                                <span class="text-xs font-semibold bg-orange-100 text-orange-700 px-2 py-0.5 rounded">
                                                    Bronze
                                                </span>
                                                @break

                                            @case('silver')
                                                <span class="text-xs font-semibold bg-gray-200 text-gray-700 px-2 py-0.5 rounded">
                                                    Silver
                                                </span>
                                                @break

                                            @case('gold')
                                                <span class="text-xs font-semibold bg-amber-100 text-amber-700 px-2 py-0.5 rounded">
                                                    Gold
                                                </span>
                                                @break

                                            @case('diamond')
                                                <span class="text-xs font-semibold bg-cyan-100 text-cyan-700 px-2 py-0.5 rounded">
                                                    Diamond
                                                </span>
                                                @break

                                            @default
                                                <span class="text-xs font-semibold bg-gray-100 text-gray-600 px-2 py-0.5 rounded">
                                                    Aucune
                                                </span>
                                        @endswitch
                                    </div>

                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-0.5">
                                        <h3 class="font-semibold text-sm">{{ $post->user->full_name }}</h3>
                                        @if ($post->user->role_name != 'user')

                                        <span class="badge bg-indigo-50 text-indigo-600 rounded-full">Vérifiée</span>
                                            
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500">Il y a {{ \Carbon\Carbon::parse($post->created_at)->diffForHumans() }}</p>
                                </div>

                                @if ( $userData['user_id'] == $post->user->id)
                                    <div class="post-options">
                                        <button
                                            class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                            onclick="toggleOptions(this)">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z" />
                                            </svg>
                                        </button>
                                    

                                        <div class="options-menu">
                                            <div class="option-item" onclick="editPost(this,{{ $post->id }})">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Modifier
                                            </div>
                                            <div class="option-item danger" onclick="deletePost(this,{{ $post->id }})">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Supprimer
                                            </div>
                                        </div>
                                        
                                    
                                    </div>

                                @endif
                            </div>
                        </div>

                        <div class="p-4">
                            <p class="text-sm leading-relaxed text-gray-700">
                                {{  $post->content  }}
                            </p>
                        </div>

                        <div class="px-4 pb-4">
                            <div class="flex items-center gap-4 mb-3 text-xs text-gray-500 border-t pt-3">
                                <span><span class="font-semibold text-gray-700">{{ $post->likes_count }}</span> likes</span>
                                <span><span class="font-semibold text-gray-700">{{ $post->comments_count }}</span> commentaires</span>
                                <span><span class="font-semibold text-gray-700">{{ $post->shares_count }}</span> partages</span>
                            </div>

                            <div class="flex items-center gap-1 border-t pt-3">
                                <button
                                    class="action-btn flex-1 flex items-center justify-center gap-2 py-2 rounded-lg text-sm font-medium"
                                    onclick="handleLike(this,{{ $post->id }})">
                                    <svg class="heart-icon w-5 h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                    <span>J'aime</span>
                                </button>

                                <button
                                    class="action-btn flex-1 flex items-center justify-center gap-2 py-2 rounded-lg text-sm font-medium"
                                    onclick="toggleComments(this)">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                    <span>Commenter</span>
                                </button>

                                <button
                                    class="action-btn flex-1 flex items-center justify-center gap-2 py-2 rounded-lg text-sm font-medium"
                                    onclick="openShareModal(this,{{ $post->id }})">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                    </svg>
                                    <span>Partager</span>
                                </button>
                            </div>

                            <div class="comment-section mt-3">
                                <div class="space-y-2 mb-3">
                                    <!-- Ajouter 15+ commentaires ici pour tester la pagination -->
                                    @foreach ( $post->comments as $comments)
                                    <div class="comment-item flex gap-2 p-2.5 bg-gray-50 rounded-lg" data-comment-id="{{ $comments->id }}">
                                        <div class="w-7 h-7 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full flex-shrink-0"></div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-0.5">
                                                <span class="font-medium text-xs">{{ $comments->user->full_name }}</span>
                                                <span class="text-xs text-gray-400">Il y a  {{ \Carbon\Carbon::parse($comments->created_at)->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-xs text-gray-700 comment-text">"{{ $comments->content }}"</p>
                                        </div>
                                    </div>
                                    @endforeach
                                    
                                
                                </div>
                                <div class="flex gap-2">
                                    <input type="text"
                                        class="flex-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400"
                                        placeholder="Ajouter un commentaire..."
                                        onkeypress="handleCommentSubmit(event, this)">
                                    <button
                                        class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition-colors font-medium"
                                        onclick="addComment(this.previousElementSibling)">
                                        Publier
                                    </button>
                                </div>
                            </div>
                        </div>
                     </article>
                        
                    @endif

                    <!-- POST MÉDIA -->
                    @if ($post->type =='media')

                        <article class="post-card rounded-xl overflow-hidden" data-post-id="{{ $post->id  }}">
                            <div class="p-4 border-b border-gray-100">
                                <div class="flex items-start gap-3">
                                    <div class="profile-avatar">
                                        <div class="w-10 h-10 rounded-full border-white shadow-lg mx-auto overflow-hidden flex items-center justify-center {{ Auth::user() && Auth::user()->avatar ? '' : 'bg-gradient-to-br from-purple-500 to-pink-500' }}">

                                        @if(Auth::user() && Auth::user()->avatar)
                                            <img src="{{ $post->user->getAvatar(48) }}" alt="Avatar" class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover"  
                                            onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($post->user->full_name) }}&background=random'">
                                           
                                        @else
                                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold">
                                               {{ strtoupper(substr($post->user->full_name ?? $post->user->full_name , 0, 2)) }}
                                            </div>
                                        @endif
                                       </div>
                                        <div class="profile-tooltip">
                                            <div class="text-xs text-gray-500 mb-1">Performance</div>
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-xs text-gray-600">Généré</span>
                                                <span class="text-sm font-semibold text-gray-900">${{ $post->montant }}</span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                            <span class="text-xs text-gray-600">Plaque</span>

                                            @switch($post->plaque)
                                                @case('bronze')
                                                    <span class="text-xs font-semibold bg-orange-100 text-orange-700 px-2 py-0.5 rounded">
                                                        Bronze
                                                    </span>
                                                    @break

                                                @case('silver')
                                                    <span class="text-xs font-semibold bg-gray-200 text-gray-700 px-2 py-0.5 rounded">
                                                        Silver
                                                    </span>
                                                    @break

                                                @case('gold')
                                                    <span class="text-xs font-semibold bg-amber-100 text-amber-700 px-2 py-0.5 rounded">
                                                        Gold
                                                    </span>
                                                    @break

                                                @case('diamond')
                                                    <span class="text-xs font-semibold bg-cyan-100 text-cyan-700 px-2 py-0.5 rounded">
                                                        Diamond
                                                    </span>
                                                    @break

                                                @default
                                                    <span class="text-xs font-semibold bg-gray-100 text-gray-600 px-2 py-0.5 rounded">
                                                        Aucune
                                                    </span>
                                            @endswitch
                                        </div>

                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-0.5">
                                            <h3 class="font-semibold text-sm">{{ $post->user->full_name }}</h3>
                                            @if ($post->user->role_name != 'user')

                                                <span class="badge bg-indigo-50 text-indigo-600 rounded-full">Vérifiée</span>
                                                
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-500">Il y a {{ \Carbon\Carbon::parse($post->created_at)->diffForHumans() }}</p>
                                    </div>
                                    @if ( $userData['user_id'] == $post->user->id)
                                        <div class="post-options">
                                            <button
                                                class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                                onclick="toggleOptions(this)">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z" />
                                                </svg>
                                            </button>
                                        

                                            <div class="options-menu">
                                                <div class="option-item" onclick="editPost(this,{{ $post->id }})">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Modifier
                                                </div>
                                                <div class="option-item danger" onclick="deletePost(this,{{ $post->id }})">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Supprimer
                                                </div>
                                            </div>
                                            
                                        
                                        </div>

                                    @endif
                                </div>
                            </div>

                            <div class="p-4 pb-3">
                                <p class="text-sm leading-relaxed text-gray-700 mb-3">
                                    {{  $post->content  }}
                                </p>
                            </div>

                            <div class="px-4 pb-4">
                                                    
                                @php
                                    $mediaCount = $post->media->count();
                                    $gridClass = match($mediaCount) {
                                        1 => 'single',
                                        2 => 'double',
                                        3 => 'triple',
                                        default => 'quad'
                                    };
                                @endphp
                                
                                <div class="media-grid {{ $gridClass }} mb-3">
                                    @foreach($post->media as $index => $media)
                                        @if($media->type === 'video')
                                            <div class="aspect-square rounded-lg overflow-hidden bg-black relative group cursor-pointer"
                                                onclick="openVideoFullscreen(this)">
                                                <video class="w-full h-full object-cover" 
                                                    data-video-src="{{ $media->path ? \App\Helpers\S3Helper::getTemporaryUrl($media->path, 60)  : '' }}"
                                                    preload="metadata">
                                                    <source src="{{ $media->path ?  \App\Helpers\S3Helper::getTemporaryUrl($media->path, 60)  : '' }}" type="video/mp4">
                                                </video>
                                                <!-- Overlay play button -->
                                                <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30 group-hover:bg-opacity-40 transition-all">
                                                    <svg class="w-16 h-16 text-white opacity-80 group-hover:opacity-100 group-hover:scale-110 transition-all" 
                                                        fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M8 5v14l11-7z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        @else
                                            <div class="aspect-square rounded-lg overflow-hidden cursor-pointer"
                                                onclick="openImageFullscreen('{{ $media->path ? \App\Helpers\S3Helper::getTemporaryUrl($media->path, 60)  : '' }}')">
                                                <img src="{{$media->path ?  \App\Helpers\S3Helper::getTemporaryUrl($media->path, 60)  : '' }}" 
                                                    alt="Image {{ $index + 1 }}"
                                                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                            </div>
                                        @endif
                                    @endforeach
                                </div>

                                <div class="flex items-center gap-4 mb-3 text-xs text-gray-500 border-t pt-3">
                                    <span><span class="font-semibold text-gray-700">{{ $post->likes_count }}</span> likes</span>
                                    <span><span class="font-semibold text-gray-700">{{ $post->comments_count }}</span> commentaires</span>
                                    <span><span class="font-semibold text-gray-700">{{ $post->shares_count }}</span> partages</span>
                                </div>

                                <div class="flex items-center gap-1 border-t pt-3">
                                    <button class="action-btn flex-1 flex items-center justify-center gap-2 py-2 rounded-lg text-sm font-medium"
                                            onclick="handleLike(this, {{ $post->id }})">
                                        <svg class="heart-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                        <span>J'aime</span>
                                    </button>

                                    <button class="action-btn flex-1 flex items-center justify-center gap-2 py-2 rounded-lg text-sm font-medium"
                                            onclick="toggleComments(this)">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                        <span>Commenter</span>
                                    </button>

                                    <button class="action-btn flex-1 flex items-center justify-center gap-2 py-2 rounded-lg text-sm font-medium"
                                            onclick="openShareModal(this, {{ $post->id }})">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                        </svg>
                                        <span>Partager</span>
                                    </button>
                                </div>

                                <div class="comment-section mt-3">
                                    <div class="space-y-2 mb-3">
                                        <!-- Ajouter 15+ commentaires ici pour tester la pagination -->
                                        @foreach ( $post->comments as $comments)
                                        <div class="comment-item flex gap-2 p-2.5 bg-gray-50 rounded-lg" data-comment-id="{{ $comments->id }}">
                                            <div class="w-7 h-7 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full flex-shrink-0"></div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2 mb-0.5">
                                                    <span class="font-medium text-xs">{{ $comments->user->full_name }}</span>
                                                    <span class="text-xs text-gray-400">Il y a {{ \Carbon\Carbon::parse($comments->created_at)->diffForHumans() }}</span>
                                                </div>
                                                <p class="text-xs text-gray-700 comment-text">"{{ $comments->content }}"</p>
                                            </div>
                                        </div>
                                        @endforeach
                                        
                                    
                                    </div>
                                    <div class="flex gap-2">
                                        <input type="text"
                                            class="flex-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400"
                                            placeholder="Ajouter un commentaire..."
                                            onkeypress="handleCommentSubmit(event, this)">
                                        <button
                                            class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition-colors font-medium"
                                            onclick="addComment(this.previousElementSibling)">
                                            Publier
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </article>
                        
                    @endif
                    

                    <!-- POST SONDAGE -->
                    @if ($post->type =='sondage')

                    <article class="post-card rounded-xl overflow-hidden" data-post-id="{{ $post->id}}">
                        <div class="p-4 border-b border-gray-100">
                                <div class="flex items-start gap-3">
                                    <div class="profile-avatar">
                                       <div class="w-10 h-10 rounded-full border-white shadow-lg mx-auto overflow-hidden flex items-center justify-center {{ Auth::user() && Auth::user()->avatar ? '' : 'bg-gradient-to-br from-purple-500 to-pink-500' }}">

                                        @if(Auth::user() && Auth::user()->avatar)
                                            <img src="{{ $post->user->getAvatar(48) }}" alt="Avatar" class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover"  
                                            onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($post->user->full_name) }}&background=random'">
                                           
                                        @else
                                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold">
                                               {{ strtoupper(substr($post->user->full_name ?? $post->user->full_name , 0, 2)) }}
                                            </div>
                                        @endif
                                    </div>
                                        <div class="profile-tooltip">
                                            <div class="text-xs text-gray-500 mb-1">Performance</div>
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-xs text-gray-600">Généré</span>
                                                <span class="text-sm font-semibold text-gray-900">${{ $post->montant }}</span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                            <span class="text-xs text-gray-600">Plaque</span>

                                            @switch($post->plaque)
                                                @case('bronze')
                                                    <span class="text-xs font-semibold bg-orange-100 text-orange-700 px-2 py-0.5 rounded">
                                                        Bronze
                                                    </span>
                                                    @break

                                                @case('silver')
                                                    <span class="text-xs font-semibold bg-gray-200 text-gray-700 px-2 py-0.5 rounded">
                                                        Silver
                                                    </span>
                                                    @break

                                                @case('gold')
                                                    <span class="text-xs font-semibold bg-amber-100 text-amber-700 px-2 py-0.5 rounded">
                                                        Gold
                                                    </span>
                                                    @break

                                                @case('diamond')
                                                    <span class="text-xs font-semibold bg-cyan-100 text-cyan-700 px-2 py-0.5 rounded">
                                                        Diamond
                                                    </span>
                                                    @break

                                                @default
                                                    <span class="text-xs font-semibold bg-gray-100 text-gray-600 px-2 py-0.5 rounded">
                                                        Aucune
                                                    </span>
                                            @endswitch
                                        </div>

                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-0.5">
                                            <h3 class="font-semibold text-sm">{{ $post->user->full_name }}</h3>
                                            @if ($post->user->role_name != 'user')

                                                <span class="badge bg-indigo-50 text-indigo-600 rounded-full">Vérifiée</span>
                                                
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-500">Il y a {{ \Carbon\Carbon::parse($post->created_at)->diffForHumans() }}</p>
                                    </div>
                                    @if ( $userData['user_id'] == $post->user->id)
                                        <div class="post-options">
                                            <button
                                                class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                                onclick="toggleOptions(this)">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z" />
                                                </svg>
                                            </button>
                                        

                                            <div class="options-menu">
                                                <div class="option-item" onclick="editPost(this,{{ $post->id }})">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Modifier
                                                </div>
                                                <div class="option-item danger" onclick="deletePost(this,{{ $post->id }})">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Supprimer
                                                </div>
                                            </div>
                                            
                                        
                                        </div>

                                    @endif
                                </div>
                        </div>

                        <div class="p-4">
                                <div class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                    <span class="text-xs font-semibold text-indigo-600 uppercase tracking-wide">Sondage</span>
                                </div>

                                <h2 class="font-semibold text-base mb-2 text-gray-900">
                                    {{ $post->poll->question }}
                                </h2>
                                <p class="text-sm leading-relaxed text-gray-600 mb-4">
                                    {{ $post->content }}
                                </p>

                                {{-- Média optionnel pour le sondage --}}
                                @if($post->media && $post->media->count() > 0)
                                    <div class="mb-4 rounded-lg overflow-hidden">
                                        @php $media = $post->media->first(); @endphp
                                        
                                        @if($media->type === 'video')
                                            <div class="relative group cursor-pointer" onclick="openVideoFullscreen(this)">
                                                <video class="w-full max-h-64 object-cover rounded-lg" preload="metadata">
                                                    <source src="{{ $media->path ? \App\Helpers\S3Helper::getTemporaryUrl($media->path, 60)  : '' }}" type="video/mp4">
                                                </video>
                                                <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30 group-hover:bg-opacity-40 transition-all rounded-lg">
                                                    <svg class="w-16 h-16 text-white opacity-80 group-hover:opacity-100 transition-all" 
                                                        fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M8 5v14l11-7z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        @else
                                            <img src="{{$media->path ?  \App\Helpers\S3Helper::getTemporaryUrl($media->path, 60)  : '' }}" 
                                                alt="Image du sondage"
                                                class="w-full max-h-64 object-cover rounded-lg cursor-pointer hover:opacity-95 transition-opacity"
                                                onclick="openImageFullscreen('{{ $media->path ? \App\Helpers\S3Helper::getTemporaryUrl($media->path, 60)  : '' }}')">
                                        @endif
                                    </div>
                                @endif

                                {{-- Options du sondage --}}
                                <div class="space-y-2 mb-4">
                                    @php
                                        $totalVotes = $post->poll->options->sum('votes');
                                    @endphp
                                    
                                    @foreach($post->poll->options as $option)
                                        @php
                                            $percentage = $totalVotes > 0 ? round(($option->votes / $totalVotes) * 100) : 0;
                                        @endphp
                                        
                                        <div class="poll-option border border-gray-200 rounded-lg p-3 relative"
                                            onclick="votePoll({{ $post->poll->id }}, {{ $option->id }}, this)">
                                            <div class="poll-progress" style="width: {{ $percentage }}%"></div>
                                            <div class="relative z-10 flex items-center justify-between">
                                                <span class="text-sm font-medium">{{ $option->option }}</span>
                                                <span class="text-xs text-gray-500 poll-percentage">{{ $percentage }}%</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <p class="text-xs text-gray-500">
                                    <span class="font-semibold text-gray-700">{{ number_format($totalVotes) }}</span> votes
                                    @if($post->poll->ends_at)
                                        • Se termine {{ \Carbon\Carbon::parse($post->poll->ends_at)->diffForHumans() }}
                                    @endif
                                </p>
                        </div>

                        <div class="px-4 pb-4">
                            <div class="flex items-center gap-4 mb-3 text-xs text-gray-500 border-t pt-3">
                                <span><span class="font-semibold text-gray-700">{{ $post->likes_count }}</span> likes</span>
                                <span><span class="font-semibold text-gray-700">{{ $post->comments_count }}</span> commentaires</span>
                                <span><span class="font-semibold text-gray-700">{{ $post->shares_count }}</span> partages</span>
                            </div>

                            <div class="flex items-center gap-1 border-t pt-3">
                                <button class="action-btn flex-1 flex items-center justify-center gap-2 py-2 rounded-lg text-sm font-medium"
                                        onclick="handleLike(this, {{ $post->id }})">
                                    <svg class="heart-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                    <span>J'aime</span>
                                </button>

                                <button class="action-btn flex-1 flex items-center justify-center gap-2 py-2 rounded-lg text-sm font-medium"
                                        onclick="toggleComments(this)">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                    <span>Commenter</span>
                                </button>

                                <button class="action-btn flex-1 flex items-center justify-center gap-2 py-2 rounded-lg text-sm font-medium"
                                        onclick="openShareModal(this, {{ $post->id }})">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                    </svg>
                                    <span>Partager</span>
                                </button>
                            </div>

                            <div class="comment-section mt-3">
                                <div class="space-y-2 mb-3">
                                    @foreach($post->comments as $comment)
                                        <div class="comment-item flex gap-2 p-2.5 bg-gray-50 rounded-lg" data-comment-id="{{ $comment->id }}">
                                            <div class="w-7 h-7 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full flex-shrink-0"></div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2 mb-0.5">
                                                    <span class="font-medium text-xs">{{ $comment->user->full_name }}</span>
                                                    <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}</span>
                                                </div>
                                                <p class="text-xs text-gray-700 comment-text">{{ $comment->content }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="flex gap-2">
                                    <input type="text"
                                        class="flex-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400"
                                        placeholder="Ajouter un commentaire..."
                                        onkeypress="handleCommentSubmit(event, this)">
                                    <button class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition-colors font-medium"
                                        onclick="addComment(this.previousElementSibling)">
                                        Publier
                                    </button>
                                </div>
                            </div>
                        </div>
                    </article>
                        
                    @endif
                       
                   @endforeach
                </div>

                <!-- Modal de partage -->
                                         
                <div class="share-modal" id="shareModal">
                    <div class="share-modal-content">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-semibold text-base">Partager le post</h3>
                            <button onclick="closeShareModal()"
                                class="p-1 text-gray-400 hover:text-gray-600 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <p class="text-sm text-gray-600 mb-4">Copiez le lien ci-dessous pour partager ce post</p>
                        <div class="flex gap-2 flex-col sm:flex-row">
                            <input type="text" id="shareLink" readonly
                                class="flex-1 min-w-0 px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:border-indigo-400"
                                value="https://rmclass.com/post/123456">
                            <button onclick="copyShareLink()"
                                class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition-colors font-medium">
                                Copier
                            </button>
                        </div>
                        <div id="copySuccess" class="mt-2 text-xs text-green-600 opacity-0 transition-opacity">
                            ✓ Lien copié avec succès !
                        </div>
                    </div>
                </div>

                <!-- Modal d'édition -->
                <div class="edit-modal" id="editModal">
                    <div class="edit-modal-content" id="editModalContent">
                        <!-- Le contenu sera généré dynamiquement -->
                    </div>
                </div>
            </main>

            <!-- ========================================
                 SIDEBAR DROITE - SUGGESTIONS
            ======================================== -->

            @include('vip.composants.sidebar_droite')

        </div>

    </div>
    

    <!-- ========================================
         MENU MOBILE (Overlay)
    ======================================== -->
    @include('vip.composants.mobile')


    @include('vip.composants.js')
</body>

</html>