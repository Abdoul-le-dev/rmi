<!-- Zone de création de post -->
<div class="bg-white shadow-sm border border-gray-200 p-6 mb-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                Bienvenue Abdoulaye 👋 ? Que pensez-vous aujourd'hui ?
            </h2>
            <p class="text-xs text-gray-500 mt-1">Advertising ...</p>
        </div>

        <!-- Actions rapides -->
        <div class="flex items-center gap-2">
            <button type="button" onclick="toggleDrafts()" class="relative p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Brouillons">
                <i class="fas fa-file-alt text-gray-600"></i>
                <span id="draft-count" class="post-badge hidden">0</span>
            </button>

            <button type="button" onclick="toggleTemplates()" class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Templates">
                <i class="fas fa-bookmark text-gray-600"></i>
            </button>

            <button type="button" onclick="togglePreview()" class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Aperçu">
                <i class="fas fa-eye text-gray-600"></i>
            </button>
        </div>
    </div>

    <!-- Dropdown Brouillons -->
    <div id="drafts-dropdown" class="post-dropdown hidden mb-4 bg-gray-50 rounded-lg p-4 border border-gray-200">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold text-gray-900 text-sm">Brouillons sauvegardés</h3>
            <button onclick="clearAllDrafts()" class="text-xs text-red-600 hover:text-red-700 transition-colors">
                <i class="fas fa-trash mr-1"></i>Tout supprimer
            </button>
        </div>
        <div id="drafts-list" class="space-y-2 max-h-48 overflow-y-auto post-scrollbar"></div>
    </div>

    <!-- Dropdown Templates -->
    <div id="templates-dropdown" class="post-dropdown hidden mb-4 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-lg p-4 border border-indigo-200">
        <h3 class="font-semibold text-gray-900 text-sm mb-3">Templates de trading</h3>
        <div class="grid grid-cols-2 gap-2">
            <button type="button" onclick="useTemplate('analysis')" class="template-card p-3 bg-white rounded-lg hover:bg-indigo-50 transition-all text-left border border-indigo-100">
                <div class="flex items-center gap-2 mb-1">
                    <i class="fas fa-chart-line text-indigo-600 text-sm"></i>
                    <span class="font-semibold text-sm">Analyse technique</span>
                </div>
                <p class="text-xs text-gray-600">Structure pour une analyse de marché</p>
            </button>

            <button type="button" onclick="useTemplate('trade')" class="template-card p-3 bg-white rounded-lg hover:bg-green-50 transition-all text-left border border-green-100">
                <div class="flex items-center gap-2 mb-1">
                    <i class="fas fa-exchange-alt text-green-600 text-sm"></i>
                    <span class="font-semibold text-sm">Rapport de trade</span>
                </div>
                <p class="text-xs text-gray-600">Partager un trade gagnant/perdant</p>
            </button>

            <button type="button" onclick="useTemplate('question')" class="template-card p-3 bg-white rounded-lg hover:bg-orange-50 transition-all text-left border border-orange-100">
                <div class="flex items-center gap-2 mb-1">
                    <i class="fas fa-question-circle text-orange-600 text-sm"></i>
                    <span class="font-semibold text-sm">Question</span>
                </div>
                <p class="text-xs text-gray-600">Poser une question à la communauté</p>
            </button>

            <button type="button" onclick="useTemplate('tip')" class="template-card p-3 bg-white rounded-lg hover:bg-yellow-50 transition-all text-left border border-yellow-100">
                <div class="flex items-center gap-2 mb-1">
                    <i class="fas fa-lightbulb text-yellow-600 text-sm"></i>
                    <span class="font-semibold text-sm">Conseil/Astuce</span>
                </div>
                <p class="text-xs text-gray-600">Partager un conseil de trading</p>
            </button>
        </div>
    </div>

    <!-- Preview Panel -->
    <div id="preview-panel" class="post-dropdown hidden mb-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-200">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold text-gray-900 text-sm flex items-center gap-2">
                <i class="fas fa-eye text-indigo-600"></i>Aperçu de votre publication
            </h3>
            <button onclick="togglePreview()" class="text-gray-600 hover:text-gray-900 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="preview-content" class="bg-white rounded-lg p-4 border border-gray-200"></div>
    </div>

    <!-- Formulaire -->
    <form id="post-form" class="space-y-4">
        <div>
            <!-- Textarea -->
            <div class="relative">
                <textarea 
                    id="post-textarea"
                    placeholder="Partagez vos idées, stratégies de trading... (@ pour mentionner, # pour hashtag)"
                    class="w-full p-4 pr-16 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none transition-all duration-300"
                    rows="3"
                    oninput="handleTextInput()"
                    onkeydown="handleKeyPress(event)"></textarea>

                <!-- Compteur -->
                <div class="absolute bottom-3 right-3 flex items-center gap-2">
                    <span id="char-counter" class="text-xs text-gray-400 transition-all duration-300">0 / 5000</span>
                    <div class="w-8 h-8">
                        <svg class="post-progress-svg" width="32" height="32">
                            <circle cx="16" cy="16" r="14" stroke="#e5e7eb" stroke-width="2" fill="none"/>
                            <circle id="char-progress-circle" class="post-progress-circle" cx="16" cy="16" r="14" stroke="#4f46e5" stroke-width="2" fill="none"/>
                        </svg>
                    </div>
                </div>

                <!-- Suggestions mentions -->
                <div id="mention-suggestions" class="post-suggestions hidden absolute z-50 bg-white border border-gray-200 rounded-lg shadow-xl mt-1 max-h-48 overflow-y-auto"></div>

                <!-- Suggestions hashtags -->
                <div id="hashtag-suggestions" class="post-suggestions hidden absolute z-50 bg-white border border-gray-200 rounded-lg shadow-xl mt-1 max-h-48 overflow-y-auto"></div>
            </div>

            <!-- Media Preview -->
            <div id="media-preview" class="post-media-grid hidden mt-3 grid grid-cols-3 gap-2"></div>

            <!-- Drop Zone -->
            <div id="drop-zone" class="post-dropzone hidden mt-3 border-2 border-dashed border-indigo-300 rounded-lg p-8 text-center bg-indigo-50 transition-all">
                <i class="fas fa-cloud-upload-alt post-dropzone-icon text-4xl text-indigo-400 mb-2"></i>
                <p class="text-sm text-indigo-600 font-semibold">Déposez vos fichiers ici</p>
                <p class="text-xs text-gray-500 mt-1">Images, vidéos ou GIFs</p>
            </div>

            <!-- Link Preview -->
            <div id="link-preview" class="post-link-preview hidden mt-3 border border-gray-200 rounded-lg overflow-hidden hover:border-indigo-300 transition-all">
                <div class="flex">
                    <img id="link-preview-image" class="w-32 h-32 object-cover post-link-img" src="" alt="">
                    <div class="flex-1 p-3">
                        <h4 id="link-preview-title" class="font-semibold text-sm text-gray-900 mb-1"></h4>
                        <p id="link-preview-description" class="text-xs text-gray-600 line-clamp-2 mb-2"></p>
                        <p id="link-preview-url" class="text-xs text-indigo-600"></p>
                    </div>
                    <button onclick="removeLinkPreview()" class="p-2 text-gray-400 hover:text-gray-600 transition-colors">
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
                    <div id="upload-progress-bar" class="post-upload-bar bg-gradient-to-r from-indigo-500 to-purple-500 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
            </div>

            <!-- Poll Container -->
            <div id="poll-container" class="post-dropdown hidden mt-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="font-semibold text-sm text-gray-900">Créer un sondage</h4>
                    <button type="button" onclick="removePoll()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <input type="text" id="poll-question" placeholder="Quelle est votre question ?" class="w-full p-2 border border-gray-300 rounded-lg text-sm mb-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                <div id="poll-options" class="space-y-2">
                    <input type="text" placeholder="Option 1" class="w-full p-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                    <input type="text" placeholder="Option 2" class="w-full p-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                </div>
                <button type="button" onclick="addPollOption()" class="mt-2 text-xs text-indigo-600 hover:text-indigo-700 font-semibold transition-colors">
                    <i class="fas fa-plus mr-1"></i>Ajouter une option
                </button>
            </div>

            <!-- Schedule Container -->
            <div id="schedule-container" class="post-dropdown post-schedule hidden mt-3 p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg border border-purple-200">
                <div class="flex items-center justify-between mb-3 relative z-10">
                    <h4 class="font-semibold text-sm text-gray-900 flex items-center gap-2">
                        <i class="fas fa-clock text-purple-600"></i>Planifier la publication
                    </h4>
                    <button type="button" onclick="removeSchedule()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="grid grid-cols-2 gap-3 relative z-10">
                    <input type="date" id="schedule-date" class="p-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all">
                    <input type="time" id="schedule-time" class="p-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all">
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
                    <button type="button" onclick="toggleEmojiPicker()" class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Emoji">
                        <i class="far fa-smile text-gray-600 text-lg"></i>
                    </button>

                    <div id="emoji-picker" class="post-emoji-picker hidden absolute z-50 bg-white border border-gray-200 rounded-lg shadow-xl p-3 mt-2 w-80">
                        <input type="text" id="emoji-search" placeholder="Rechercher..." class="w-full p-2 border border-gray-300 rounded-lg text-sm mb-3 focus:outline-none focus:ring-2 focus:ring-indigo-500" oninput="searchEmojis()">
                        
                        <div class="flex gap-2 mb-3 overflow-x-auto">
                            <button type="button" onclick="filterEmojis('recent')" class="emoji-cat px-3 py-1 text-xs rounded-full bg-gray-100 hover:bg-indigo-100 transition-colors whitespace-nowrap">
                                <i class="fas fa-clock"></i>
                            </button>
                            <button type="button" onclick="filterEmojis('smileys')" class="emoji-cat px-3 py-1 text-xs rounded-full bg-gray-100 hover:bg-indigo-100 transition-colors whitespace-nowrap">
                                😀
                            </button>
                            <button type="button" onclick="filterEmojis('trading')" class="emoji-cat emoji-cat-active px-3 py-1 text-xs rounded-full bg-indigo-100 text-indigo-700 transition-colors whitespace-nowrap">
                                📈
                            </button>
                            <button type="button" onclick="filterEmojis('symbols')" class="emoji-cat px-3 py-1 text-xs rounded-full bg-gray-100 hover:bg-indigo-100 transition-colors whitespace-nowrap">
                                ⚡
                            </button>
                        </div>

                        <div id="emoji-grid" class="grid grid-cols-8 gap-2 max-h-64 overflow-y-auto post-scrollbar">
                            <button type="button" onclick="insertEmoji('📈')" class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all" title="Hausse">📈</button>
                            <button type="button" onclick="insertEmoji('📉')" class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all" title="Baisse">📉</button>
                            <button type="button" onclick="insertEmoji('💰')" class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all" title="Argent">💰</button>
                            <button type="button" onclick="insertEmoji('💎')" class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all" title="Diamant">💎</button>
                            <button type="button" onclick="insertEmoji('🚀')" class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all" title="Fusée">🚀</button>
                            <button type="button" onclick="insertEmoji('💪')" class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all" title="Force">💪</button>
                            <button type="button" onclick="insertEmoji('🔥')" class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all" title="Feu">🔥</button>
                            <button type="button" onclick="insertEmoji('⚡')" class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all" title="Éclair">⚡</button>
                            <button type="button" onclick="insertEmoji('🎯')" class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all" title="Cible">🎯</button>
                            <button type="button" onclick="insertEmoji('💡')" class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all" title="Idée">💡</button>
                            <button type="button" onclick="insertEmoji('✨')" class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all" title="Étoiles">✨</button>
                            <button type="button" onclick="insertEmoji('🎉')" class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all" title="Fête">🎉</button>
                            <button type="button" onclick="insertEmoji('👍')" class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all" title="Pouce">👍</button>
                            <button type="button" onclick="insertEmoji('❤️')" class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all" title="Coeur">❤️</button>
                            <button type="button" onclick="insertEmoji('😀')" class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all" title="Sourire">😀</button>
                            <button type="button" onclick="insertEmoji('😊')" class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all" title="Heureux">😊</button>
                            <button type="button" onclick="insertEmoji('🤔')" class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all" title="Réfléchir">🤔</button>
                            <button type="button" onclick="insertEmoji('😎')" class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all" title="Cool">😎</button>
                            <button type="button" onclick="insertEmoji('🤑')" class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all" title="Argent">🤑</button>
                            <button type="button" onclick="insertEmoji('😤')" class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all" title="Déterminé">😤</button>
                            <button type="button" onclick="insertEmoji('📊')" class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all" title="Graphique">📊</button>
                            <button type="button" onclick="insertEmoji('💼')" class="emoji-btn text-2xl p-1 rounded hover:bg-gray-100 transition-all" title="Business">💼</button>
                        </div>

                        <div id="recent-emojis" class="mt-3 pt-3 border-t border-gray-200">
                            <p class="text-xs text-gray-500 mb-2">Récemment utilisés</p>
                            <div class="flex gap-2"></div>
                        </div>
                    </div>
                </div>

                <!-- Upload Image -->
                <button type="button" onclick="document.getElementById('image-upload').click()" class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Image">
                    <i class="far fa-image text-gray-600 text-lg"></i>
                </button>
                <input type="file" id="image-upload" accept="image/*" multiple class="hidden" onchange="handleImageUpload(event)">

                <!-- Upload Video -->
                <button type="button" onclick="document.getElementById('video-upload').click()" class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Vidéo">
                    <i class="fas fa-video text-gray-600 text-lg"></i>
                </button>
                <input type="file" id="video-upload" accept="video/*" class="hidden" onchange="handleVideoUpload(event)">

                <!-- Poll -->
                <button type="button" onclick="togglePoll()" class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Sondage">
                    <i class="fas fa-poll text-gray-600 text-lg"></i>
                </button>

                <!-- Schedule -->
                <button type="button" onclick="toggleSchedule()" class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Planifier">
                    <i class="fas fa-clock text-gray-600 text-lg"></i>
                </button>

                <!-- Divider -->
                <div class="h-6 w-px bg-gray-300"></div>

                <!-- Formatting -->
                <button type="button" onclick="formatText('bold')" class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Gras">
                    <i class="fas fa-bold text-gray-600"></i>
                </button>
                <button type="button" onclick="formatText('link')" class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Lien">
                    <i class="fas fa-link text-gray-600"></i>
                </button>
            </div>

            <!-- Send Button -->
            <button type="button" id="send-button" onclick="sendMessage()" class="post-send-btn relative px-6 py-2 bg-indigo-600 text-white rounded-lg font-semibold text-sm hover:bg-indigo-700 transition-all overflow-hidden group">
                <span class="relative z-10 flex items-center justify-center transition-all duration-300 group-hover:scale-110">
                    <i id="send-icon" class="fas fa-paper-plane mr-2 transition-all duration-300"></i>
                    <span id="send-text">Envoyer</span>
                </span>
                <div class="post-send-gradient absolute inset-0 bg-gradient-to-r from-indigo-700 via-purple-600 to-indigo-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div id="send-particles" class="post-particles absolute inset-0 pointer-events-none opacity-0">
                    <div class="post-particle"></div>
                    <div class="post-particle"></div>
                    <div class="post-particle"></div>
                    <div class="post-particle"></div>
                    <div class="post-particle"></div>
                </div>
                <div id="confetti-container" class="post-confetti-container absolute inset-0 pointer-events-none opacity-0"></div>
            </button>
        </div>
    </form>

    <!-- Typing Indicator -->
    <div id="typing-indicator" class="post-typing hidden flex items-center gap-2 text-sm text-gray-600 mt-4">
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