<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>VIP RMI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @include('vip/composants/css')
   

</head>

<body class="bg-gray-50 text-gray-900 antialiased">

    <!-- ========================================
         HEADER PRINCIPAL
    ======================================== -->

    @include('vip/composants/header')

    <!-- ========================================
         LAYOUT PRINCIPAL (3 COLONNES)
    ======================================== -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- ========================================
                 SIDEBAR GAUCHE - NAVIGATION
            ======================================== -->
            @include('vip/composants/sidebar_gauche')

            <!-- ========================================
                 COLONNE CENTRALE - FEED
            ======================================== -->

            <main class="lg:col-span-6 main" id="menu_menu">

                <!-- Zone de création de post -->
                <div class="bg-white shadow-sm border border-gray-200 p-6 mb-6">
                    <!-- Header -->

                    <div class="mb-4">
                        <!-- Ligne du haut -->
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-bold text-gray-900">
                                Bienvenue Abdoulaye 👋
                            </h3>

                            <!-- Actions rapides -->
                            <div class="flex items-center gap-2">
                                <button onclick="toggleDrafts()"  class="hidden md:block p-2 rounded-lg hover:bg-gray-100">
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
                    <form id="post-form" onsubmit="sendMessage(event); return false;" class="space-y-4" method="POST"  enctype="multipart/form-data">
                       @csrf
                        <div>
                            <!-- Textarea -->
                            <div class="relative">
                                <textarea id="post-textarea" name="contents"
                                    placeholder="Partagez vos idées, vos perfomances ... (@ pour mentionner, # pour hashtag)"
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
                                <div class="flex">                                 <img id="link-preview-image" class="w-32 h-32 object-cover post-link-img" src=""
                                        alt="">
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
                                <input type="text" id="poll-question" name="poll[question]" placeholder="Quelle est votre question ?"
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
                                        class="sm:hidden md:hiddden lg:block  p-2 rounded-lg hover:bg-gray-100 transition-colors"
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
                                <input type="file" id="image-upload" accept="image/*" name="media[]" multiple class="hidden"
                                    onchange="handleImageUpload(event)">

                                <!-- Upload Video -->
                                <button type="button" onclick="document.getElementById('video-upload').click()"
                                    class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Vidéo">
                                    <i class="fas fa-video text-gray-600 text-lg"></i>
                                </button>
                                <input type="file" id="video-upload" accept="video/*" class="hidden" name="media[]" multiple
                                    onchange="handleVideoUpload(event)">

                                <!-- Poll -->
                                <button type="button" onclick="togglePoll()"
                                    class="hidden md:block p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Sondage">
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
                <div class="space-y-4">
                    

                    <!-- Post 1 -->
                    <article class="bg-white rounded-xl shadow-sm post-card p-6 fade-in">
                        <!-- Header du post -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <img src="https://i.pravatar.cc/150?img=33" alt="Junior TOSSA"
                                    class="w-12 h-12 rounded-full object-cover">
                                <div>
                                    <h4 class="font-semibold text-gray-900">Junior TOSSA</h4>
                                    <p class="text-xs text-gray-500">Modérateur</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span class="text-xs text-gray-400">il y a 1 min</span>
                                <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                                    <i class="fas fa-ellipsis-h text-gray-400"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Contenu du post -->
                        <div class="mb-4">
                            <p class="text-gray-700 leading-relaxed mb-2">
                                🚀👍<br>
                                Le marché ne dort jamais... mais ton plan de trading, oui : il doit être clair et
                                discipliné.
                            </p>
                            <p class="text-indigo-600 font-semibold">
                                Trade avec la tête, pas avec le stress !
                            </p>
                        </div>

                        <!-- Interactions -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <div class="flex items-center space-x-6">
                                <button
                                    class="flex items-center space-x-2 text-gray-500 hover:text-indigo-600 transition-colors">
                                    <i class="far fa-comment"></i>
                                    <span class="text-xs font-medium">47.5k</span>
                                </button>
                                <button
                                    class="flex items-center space-x-2 text-gray-500 hover:text-red-500 transition-colors">
                                    <i class="far fa-heart"></i>
                                    <span class="text-xs font-medium">650.3k</span>
                                </button>
                            </div>
                            <div class="flex -space-x-2">
                                <img src="https://i.pravatar.cc/150?img=1"
                                    class="w-8 h-8 rounded-full border-2 border-white" alt="">
                                <img src="https://i.pravatar.cc/150?img=2"
                                    class="w-8 h-8 rounded-full border-2 border-white" alt="">
                                <img src="https://i.pravatar.cc/150?img=3"
                                    class="w-8 h-8 rounded-full border-2 border-white" alt="">
                                <img src="https://i.pravatar.cc/150?img=4"
                                    class="w-8 h-8 rounded-full border-2 border-white" alt="">
                            </div>
                        </div>
                    </article>

                    <!-- Post 2 avec image -->
                    <article class="bg-white rounded-xl shadow-sm post-card p-6 fade-in">
                        <!-- Header du post -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <img src="https://i.pravatar.cc/150?img=45" alt="Zeynab HOUNGBE"
                                    class="w-12 h-12 rounded-full object-cover">
                                <div>
                                    <h4 class="font-semibold text-gray-900">Zeynab HOUNGBE</h4>
                                    <p class="text-xs text-gray-500">Coach</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span class="text-xs text-gray-400">il y a 2 heures</span>
                                <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                                    <i class="fas fa-ellipsis-h text-gray-400"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Contenu du post -->
                        <div class="mb-4">
                            <p class="text-gray-700 leading-relaxed mb-4">
                                🚀🔥<br>
                                Un bon trade, c'est 80% de patience et 20% d'action.<br>
                                Attends le bon signal, pas le frisson.
                            </p>

                            <!-- Image du post -->
                            <div class="rounded-lg overflow-hidden">
                                <img src="https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=800"
                                    alt="Bitcoin"
                                    class="w-full h-64 object-cover hover:scale-105 transition-transform duration-300">
                            </div>
                        </div>

                        <!-- Interactions -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <div class="flex items-center space-x-6">
                                <button
                                    class="flex items-center space-x-2 text-gray-500 hover:text-indigo-600 transition-colors">
                                    <i class="far fa-comment"></i>
                                    <span class="text-xs font-medium">234</span>
                                </button>
                                <button
                                    class="flex items-center space-x-2 text-gray-500 hover:text-red-500 transition-colors">
                                    <i class="far fa-heart"></i>
                                    <span class="text-xs font-medium">5.2k</span>
                                </button>
                            </div>
                            <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                                <i class="fas fa-share text-gray-500"></i>
                            </button>
                        </div>
                    </article>

                    <!-- Post 3 -->
                    <article class="bg-white rounded-xl shadow-sm post-card p-6 fade-in">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <img src="https://i.pravatar.cc/150?img=68" alt="Sarah MARTIN"
                                    class="w-12 h-12 rounded-full object-cover">
                                <div>
                                    <h4 class="font-semibold text-gray-900">Sarah MARTIN</h4>
                                    <p class="text-xs text-gray-500">Trader Pro</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span class="text-xs text-gray-400">il y a 5 heures</span>
                                <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                                    <i class="fas fa-ellipsis-h text-gray-400"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <p class="text-gray-700 leading-relaxed">
                                📊 Analyse du jour : Le marché montre des signes de consolidation.
                                C'est le moment parfait pour affiner votre stratégie et attendre les bonnes
                                opportunités.
                                <span class="text-indigo-600 font-semibold">#Trading #Strategy #Patience</span>
                            </p>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <div class="flex items-center space-x-6">
                                <button
                                    class="flex items-center space-x-2 text-gray-500 hover:text-indigo-600 transition-colors">
                                    <i class="far fa-comment"></i>
                                    <span class="text-xs font-medium">89</span>
                                </button>
                                <button
                                    class="flex items-center space-x-2 text-gray-500 hover:text-red-500 transition-colors">
                                    <i class="far fa-heart"></i>
                                    <span class="text-xs font-medium">1.8k</span>
                                </button>
                            </div>
                            <div class="flex -space-x-2">
                                <img src="https://i.pravatar.cc/150?img=5"
                                    class="w-8 h-8 rounded-full border-2 border-white" alt="">
                                <img src="https://i.pravatar.cc/150?img=6"
                                    class="w-8 h-8 rounded-full border-2 border-white" alt="">
                                <img src="https://i.pravatar.cc/150?img=7"
                                    class="w-8 h-8 rounded-full border-2 border-white" alt="">
                            </div>
                        </div>
                    </article>
                </div>

                <!-- Bouton charger plus -->
                <div class="mt-6 text-center">
                    <button
                        class="px-6 py-3 bg-white border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition-all">
                        Charger plus de posts
                    </button>
                </div>
            </main>

            <!-- ========================================
                 SIDEBAR DROITE - SUGGESTIONS
            ======================================== -->
            
            @include('vip/composants/sidebar_droite')

        </div>
       
    </div>
    </div>

    <!-- ========================================
         MENU MOBILE (Overlay)
    ======================================== -->
    <div id="mobile-menu" class="fixed inset-0 z-50 hidden">
        <!-- Overlay -->
        <div class="mobile-menu-overlay absolute inset-0" id="mobile-menu-overlay"></div>

        <!-- Menu sidebar -->
        <div class="slide-in absolute left-0 top-0 bottom-0 w-80 bg-white shadow-2xl overflow-y-auto">
            <!-- Header -->
            <div class="gradient-bg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold">Menu</h3>
                    <button id="close-mobile-menu" class="p-2 hover:bg-white/20 rounded-lg transition-colors">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
                <div class="flex items-center space-x-3">
                    <img src="https://i.pravatar.cc/150?img=12" alt="Profil"
                        class="w-16 h-16 rounded-full border-3 border-white">
                    <div>
                        <p class="font-bold">CHOUTI Abdoulaye</p>
                        <p class="text-xs text-indigo-100">Modérateur</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="p-4">

                <a href="#"
                    class="group relative flex items-center space-x-3 px-4 py-3 rounded-lg bg-indigo-50 text-indigo-600 mb-2 overflow-hidden transition-all duration-300 hover:bg-indigo-600 hover:text-white hover:shadow-xl hover:scale-105">
                    <!-- Particules animées en arrière-plan -->
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="absolute w-2 h-2 bg-white rounded-full animate-particle-1"></div>
                        <div class="absolute w-1.5 h-1.5 bg-yellow-300 rounded-full animate-particle-2"></div>
                        <div class="absolute w-1 h-1 bg-pink-300 rounded-full animate-particle-3"></div>
                        <div class="absolute w-2 h-2 bg-cyan-300 rounded-full animate-particle-4"></div>
                        <div class="absolute w-1.5 h-1.5 bg-white rounded-full animate-particle-5"></div>
                    </div>

                    <!-- Vague énergétique -->
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent opacity-0 group-hover:opacity-30 group-hover:animate-wave">
                    </div>

                    <!-- Icône avec animation -->
                    <i
                        class="fas fa-home text-base w-4 relative z-10 transition-all duration-300 group-hover:rotate-12 group-hover:scale-125"></i>

                    <!-- Texte avec animation -->
                    <p class="font-bold relative z-10 transition-all duration-300 group-hover:tracking-wider">
                        Communautés</p>

                    <!-- Lueur pulsante -->
                    <div
                        class="absolute inset-0 rounded-lg bg-indigo-400 opacity-0 group-hover:opacity-20 group-hover:animate-pulse-glow">
                    </div>
                </a>
                <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg bg-indigo-50 text-indigo-600 mb-2">
                    <i class="fas fa-home text-base w-4"></i>
                    <p class="font-bold">Communautés</p>
                </a>
                <a href="#"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 mb-2">
                    <i class="fas fa-book text-base w-6"></i>
                    <span class="font-medium">Classrooms</span>
                </a>
                <a href="#"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 mb-2">
                    <i class="fas fa-calendar-alt text-base w-6"></i>
                    <span class="font-medium">Événements</span>
                </a>
                <a href="#"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 mb-2">
                    <i class="fas fa-map-marker-alt text-base w-6"></i>
                    <span class="font-medium">Localisations des Membres</span>
                </a>
                <a href="#"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 mb-2">
                    <i class="fas fa-users text-base w-6"></i>
                    <span class="font-medium">Vos Sessions</span>
                </a>
                <a href="#"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 mb-2">
                    <i class="fas fa-cog text-base w-6"></i>
                    <span class="font-medium">Paramètres</span>
                </a>
                <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-question-circle text-base w-6"></i>
                    <span class="font-medium">Centre d'aide</span>
                </a>
            </nav>

            <!-- Stats -->
            <div class="mx-4 my-6 p-4 bg-gray-50 rounded-lg">
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div>
                        <p class="font-bold text-base">500k</p>
                        <p class="text-xs text-gray-500">Posts</p>
                    </div>
                    <div class="border-x border-gray-300">
                        <p class="font-bold text-base">23.5M</p>
                        <p class="text-xs text-gray-500">Followers</p>
                    </div>
                    <div>
                        <p class="font-bold text-base">50</p>
                        <p class="text-xs text-gray-500">Following</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

     
     @include('vip/composants/js')
</body>

</html>