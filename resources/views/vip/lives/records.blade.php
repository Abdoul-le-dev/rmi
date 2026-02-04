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

    <style>
        .video-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 16px;
            margin-top: 30px;
        }

        .video-card {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 2px solid transparent;
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        }

        .video-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 16px rgba(120, 59, 214, 0.25);
            border-color: #783BD6;
        }

        .video-thumbnail-wrapper {
            position: relative;
            padding-top: 56.25%;
            /* 16:9 Aspect Ratio */
            overflow: hidden;
            cursor: pointer;
            background: #000;
        }

        .video-thumbnail-wrapper img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .video-card:hover .video-thumbnail-wrapper img {
            transform: scale(1.05);
        }

        .video-thumbnail-wrapper::after {
            content: '\f04b';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 40px;
            color: white;
            opacity: 0.8;
            transition: opacity 0.3s ease, transform 0.3s ease;
            pointer-events: none;
        }

        .video-thumbnail-wrapper:hover::after {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1.1);
        }

        .video-card-content {
            padding: 12px;
        }

        .video-title {
            font-size: 14px;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 8px;
            cursor: pointer;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 36px;
            line-height: 1.3;
        }

        .video-title:hover {
            color: #783BD6;
        }

        .video-badge {
            display: inline-block;
            background: #783BD6;
            color: white;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 11px;
            margin-bottom: 10px;
            font-weight: 500;
        }

        .video-badge.local {
            background: #10B981;
        }

        .video-info {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .video-info-item {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
        }

        .info-label {
            color: #666;
            font-weight: 500;
        }

        .info-value {
            color: #1a1a1a;
            font-weight: 600;
        }

        .no-videos-message {
            grid-column: 1/-1;
            text-align: center;
            padding: 40px;
            color: #999;
            font-size: 18px;
            font-weight: 600;
        }

        /* Pagination Styles */
        .pagination-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 40px;
            gap: 10px;
        }

        .pagination-info {
            color: #666;
            font-size: 14px;
            margin-right: 20px;
        }

        .pagination-controls {
            display: flex;
            gap: 5px;
        }

        .pagination-btn {
            padding: 8px 16px;
            border: 1px solid #ddd;
            background: white;
            color: #333;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .pagination-btn:hover:not(:disabled) {
            background: #783BD6;
            color: white;
            border-color: #783BD6;
        }

        .pagination-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .pagination-btn.active {
            background: #783BD6;
            color: white;
            border-color: #783BD6;
        }

        .page-size-selector {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-left: 20px;
        }

        .page-size-selector select {
            padding: 6px 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
        }

        /* Video Player Styles */
        .video-player-container {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #000;
            z-index: 10000;
            animation: fadeIn 0.3s ease;
        }

        .video-player-container.active {
            display: flex;
            flex-direction: column;
        }

        .video-player-header {
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.8), transparent);
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            z-index: 10;
            transition: opacity 0.3s ease;
        }

        .video-player-container.hide-controls .video-player-header {
            opacity: 0;
            pointer-events: none;
        }

        .back-button {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .back-button:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateX(-3px);
        }

        .video-player-title {
            color: white;
            font-size: 18px;
            font-weight: 600;
            margin: 0;
            flex: 1;
        }

        .video-player-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 80px 20px 20px;
        }

        .video-player-iframe {
            width: 100%;
            height: 100%;
            max-width: 1920px;
            max-height: 1080px;
            border: none;
            border-radius: 8px;
        }

        /* Hide Tidio chat when video player is active */
        body.video-playing #tidio-chat,
        body.video-playing #tidio-chat-iframe,
        body.video-playing [id^="tidio"],
        body.video-playing .tidio-chat-iframe,
        body.video-playing #launcher,
        body.video-playing .crisp-client,
        body.video-playing [class*="chat-widget"],
        body.video-playing [id*="chat-widget"] {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            pointer-events: none !important;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Responsive Styles */
        @media (max-width: 1200px) {
            .video-grid {
                grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            }

            .video-player-wrapper {
                padding: 70px 15px 15px;
            }
        }

        @media (max-width: 768px) {
            .video-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 12px;
            }

            .video-thumbnail-wrapper::after {
                font-size: 32px;
            }

            .pagination-wrapper {
                flex-direction: column;
                gap: 15px;
            }

            .pagination-info,
            .page-size-selector {
                margin: 0;
            }

            .video-player-header {
                padding: 15px;
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .back-button {
                padding: 8px 16px;
                font-size: 13px;
            }

            .video-player-title {
                font-size: 14px;
                line-height: 1.4;
            }

            .video-player-wrapper {
                padding: 100px 10px 10px;
            }

            .video-player-iframe {
                border-radius: 0;
            }
        }

        @media (max-width: 576px) {
            .video-grid {
                grid-template-columns: 1fr;
            }

            .pagination-controls {
                flex-wrap: wrap;
                justify-content: center;
            }

            .video-player-header {
                padding: 12px;
            }

            .back-button {
                width: 100%;
                justify-content: center;
            }

            .video-player-wrapper {
                padding: 90px 0 0;
            }
        }

        /* Hide body overflow when video player is active */
        body.video-playing {
            overflow: hidden;
        }

        /* List view container */
        .videos-list-container {
            transition: opacity 0.3s ease;
        }

        .videos-list-container.hidden {
            display: none;
        }
    </style>


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

            <main class="lg:col-span-9 main" id="menu_menu">

                <div class="container my-5">
                    <!-- Videos List Container -->
                    <div class="videos-list-container" id="videosListContainer">
                        <div
                            class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
                            <h2 class="section-title">Replay Live Class</h2>
                        </div>

                        <div class="video-grid" id="videoList">
                            <!-- Video cards will be dynamically generated here -->
                            <div class="no-videos-message">Chargement en cours...</div>
                        </div>

                        <!-- Pagination -->
                        <div class="pagination-wrapper" id="paginationWrapper" style="display: none;">
                            <div class="pagination-info" id="paginationInfo"></div>
                            <div class="pagination-controls" id="paginationControls"></div>
                            <div class="page-size-selector">
                                <label for="pageSize">Par page:</label>
                                <select id="pageSize">
                                    <option value="6">6</option>

                                    <option value="12" selected>12</option>
                                    <option value="18">18</option>
                                    <option value="24">24</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Video Player Container -->
                    <div class="video-player-container" id="videoPlayerContainer">
                        <div class="video-player-header">
                            <button class="back-button" onclick="closeVideoPlayer()">
                                <i class="fas fa-arrow-left"></i>
                                Retour aux vidéos
                            </button>
                            <h3 class="video-player-title" id="currentVideoTitle"></h3>
                        </div>
                        <div class="video-player-wrapper">
                            <iframe id="videoPlayerIframe" class="video-player-iframe" src="" allowfullscreen
                                allow="autoplay; fullscreen; picture-in-picture"></iframe>
                            <video id="videoPlayerNative" class="video-player-iframe" controls
                                style="display: none;"></video>
                        </div>
                    </div>
                </div>

            </main>

            <!-- ========================================
                 SIDEBAR DROITE - SUGGESTIONS
            ======================================== -->

            {{-- @include('vip/composants/sidebar_droite') --}}

        </div>

    </div>


    <!-- ========================================
         MENU MOBILE (Overlay)
    ======================================== -->
    @include('vip/composants/mobile')


    @include('vip/composants/js')

    <script>
        const accessToken = "{{ env('VIMEO_ACCESS_TOKEN') }}";
        const vimeoUserId = "{{ env('VIMEO_USER_ID') }}";
        const folderId = 23672880;

        let allVideos = [];
        let currentPage = 1;
        let pageSize = 9;
        let controlsTimeout = null;
        let mouseMoveHandler = null;

        // Récupérer les vidéos Vimeo
        async function fetchVimeoVideos() {
            try {
                const response = await fetch(`https://api.vimeo.com/users/${vimeoUserId}/projects/${folderId}/videos`, {
                    headers: {
                        'Authorization': `Bearer ${accessToken}`
                    }
                });
                const data = await response.json();

                // Normaliser les vidéos Vimeo
                return data.data.map(video => ({
                    id: video.uri.split('/')[2],
                    title: video.name,
                    thumbnail: '/images/video-placeholder.jpg', //video.pictures.sizes[2].link,
                    duration: video.duration,
                    created_at: video.created_time,
                    source: 'vimeo',
                    category: video.tags && video.tags[0] ? video.tags[0].name : 'General',
                    url: `https://player.vimeo.com/video/${video.uri.split('/')[2]}`
                }));
            } catch (error) {
                console.error('Error fetching Vimeo videos:', error);
                return [];
            }
        }

        // Récupérer les vidéos locales de la base de données
        async function fetchLocalVideos() {
            try {
                const response = await fetch('{{ route('recordings.list') }}', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();

                // Normaliser les vidéos locales
                return data.map(video => ({
                    id: video.id,
                    title: video.liveclass.title || video.file_name,
                    thumbnail: '/images/video-placeholder.jpg', // Image par défaut
                    duration: video.duration_seconds || 0,
                    created_at: video.completed_at || video.created_at,
                    source: 'local',
                    category: 'Live Class',
                    url: video.url,
                    file_size: video.file_size,
                    status: video.status
                }));
            } catch (error) {
                console.error('Error fetching local videos:', error);
                return [];
            }
        }

        // Combiner toutes les vidéos
        async function fetchAllVideos() {
            const [vimeoVideos, localVideos] = await Promise.all([
                fetchVimeoVideos(),
                fetchLocalVideos()
            ]);

            // Combiner et trier par date (plus récent en premier)
            const combined = [...vimeoVideos, ...localVideos];
            combined.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

            return combined;
        }

        function showControls() {
            const playerContainer = document.getElementById('videoPlayerContainer');
            if (!playerContainer.classList.contains('active')) return;

            playerContainer.classList.remove('hide-controls');

            if (controlsTimeout) {
                clearTimeout(controlsTimeout);
            }

            controlsTimeout = setTimeout(() => {
                playerContainer.classList.add('hide-controls');
            }, 3000);
        }

        function openVideoPlayer(video) {
            const playerContainer = document.getElementById('videoPlayerContainer');
            const listContainer = document.getElementById('videosListContainer');
            const iframe = document.getElementById('videoPlayerIframe');
            const nativePlayer = document.getElementById('videoPlayerNative');
            const titleElement = document.getElementById('currentVideoTitle');

            titleElement.textContent = video.title;

            if (video.source === 'vimeo') {
                // Utiliser iframe pour Vimeo
                iframe.style.display = 'block';
                nativePlayer.style.display = 'none';
                iframe.src = `${video.url}?dnt=1&title=0&byline=0&portrait=0&badge=0&loop=0&autoplay=1`;
            } else {
                // Utiliser video natif pour les vidéos locales
                iframe.style.display = 'none';
                nativePlayer.style.display = 'block';
                nativePlayer.src = video.url;
                nativePlayer.play();
            }

            // Show player, hide list
            listContainer.classList.add('hidden');
            playerContainer.classList.add('active');
            document.body.classList.add('video-playing');

            // Setup mouse move handler
            if (mouseMoveHandler) {
                playerContainer.removeEventListener('mousemove', mouseMoveHandler);
            }

            mouseMoveHandler = showControls;
            playerContainer.addEventListener('mousemove', mouseMoveHandler);

            // Auto-hide controls after 3 seconds
            controlsTimeout = setTimeout(() => {
                playerContainer.classList.add('hide-controls');
            }, 3000);
        }

        function closeVideoPlayer() {
            const playerContainer = document.getElementById('videoPlayerContainer');
            const listContainer = document.getElementById('videosListContainer');
            const iframe = document.getElementById('videoPlayerIframe');
            const nativePlayer = document.getElementById('videoPlayerNative');

            // Remove event listener
            if (mouseMoveHandler) {
                playerContainer.removeEventListener('mousemove', mouseMoveHandler);
                mouseMoveHandler = null;
            }

            // Clear timeout
            if (controlsTimeout) {
                clearTimeout(controlsTimeout);
                controlsTimeout = null;
            }

            // Stop videos
            iframe.src = '';
            nativePlayer.pause();
            nativePlayer.src = '';

            // Hide player, show list
            playerContainer.classList.remove('active', 'hide-controls');
            listContainer.classList.remove('hidden');
            document.body.classList.remove('video-playing');
        }

        function getPaginatedVideos(videos, page, size) {
            const startIndex = (page - 1) * size;
            const endIndex = startIndex + size;
            return videos.slice(startIndex, endIndex);
        }

        function generateVideoCards(videos) {
            const videoListDiv = document.getElementById('videoList');
            videoListDiv.innerHTML = '';

            if (videos.length === 0) {
                videoListDiv.innerHTML = '<div class="no-videos-message">Aucune vidéo disponible</div>';
            } else {
                videos.forEach(video => {
                    const videoCard = document.createElement('div');
                    videoCard.className = 'video-card';
                    const uploadDate = video.created_at ? formatDate(video.created_at) : 'N/A';
                    const badgeClass = video.source === 'local' ? 'video-badge' : 'video-badge';
                    const badgeText = video.source === 'local' ? 'Recorded Video' : 'Recorded Video';

                    videoCard.innerHTML = `
                    <div class="video-thumbnail-wrapper" onclick='openVideoPlayer(${JSON.stringify(video)})'>
                        <img src="${video.thumbnail}" alt="${video.title}">
                    </div>
                    <div class="video-card-content">
                        <span class="${badgeClass}">${badgeText}</span>
                        <h3 class="video-title" onclick='openVideoPlayer(${JSON.stringify(video)})'>
                            ${video.title}
                        </h3>
                        <div class="video-info">
                            <div class="video-info-item">
                                <span class="info-label">Catégorie:</span>
                                <span class="info-value">${video.category}</span>
                            </div>
                            <div class="video-info-item">
                                <span class="info-label">Durée:</span>
                                <span class="info-value">${formatDuration(video.duration)}</span>
                            </div>
                            <div class="video-info-item">
                                <span class="info-label">Date:</span>
                                <span class="info-value">${uploadDate}</span>
                            </div>
                        </div>
                    </div>
                `;
                    videoListDiv.appendChild(videoCard);
                });
            }

            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function generatePagination() {
            const totalPages = Math.ceil(allVideos.length / pageSize);
            const paginationWrapper = document.getElementById('paginationWrapper');
            const paginationInfo = document.getElementById('paginationInfo');
            const paginationControls = document.getElementById('paginationControls');

            if (totalPages <= 1) {
                paginationWrapper.style.display = 'none';
                return;
            }

            paginationWrapper.style.display = 'flex';

            const startItem = (currentPage - 1) * pageSize + 1;
            const endItem = Math.min(currentPage * pageSize, allVideos.length);
            paginationInfo.textContent = `Affichage ${startItem}-${endItem} sur ${allVideos.length}`;

            paginationControls.innerHTML = '';

            // Previous button
            const prevBtn = document.createElement('button');
            prevBtn.className = 'pagination-btn';
            prevBtn.innerHTML = '<i class="fas fa-chevron-left"></i>';
            prevBtn.disabled = currentPage === 1;
            prevBtn.onclick = () => goToPage(currentPage - 1);
            paginationControls.appendChild(prevBtn);

            // Logique de pagination améliorée
            const maxVisiblePages = 3; // Nombre de pages visibles au centre
            let pagesToShow = [];

            if (totalPages <= 5) {
                // Si 5 pages ou moins, afficher toutes les pages
                for (let i = 1; i <= totalPages; i++) {
                    pagesToShow.push(i);
                }
            } else {
                // Toujours afficher la première page
                pagesToShow.push(1);

                // Déterminer les pages du milieu à afficher
                let startMiddle, endMiddle;

                if (currentPage <= 3) {
                    // Si on est au début (pages 1, 2, 3)
                    startMiddle = 2;
                    endMiddle = 3;
                } else if (currentPage >= totalPages - 2) {
                    // Si on est à la fin
                    startMiddle = totalPages - 2;
                    endMiddle = totalPages - 1;
                } else {
                    // Si on est au milieu
                    startMiddle = currentPage - 1;
                    endMiddle = currentPage + 1;
                }

                // Ajouter les points de suspension si nécessaire (début)
                if (startMiddle > 2) {
                    pagesToShow.push('...');
                }

                // Ajouter les pages du milieu
                for (let i = startMiddle; i <= endMiddle; i++) {
                    if (i > 1 && i < totalPages) {
                        pagesToShow.push(i);
                    }
                }

                // Ajouter les points de suspension si nécessaire (fin)
                if (endMiddle < totalPages - 1) {
                    pagesToShow.push('...');
                }

                // Toujours afficher la dernière page
                if (totalPages > 1) {
                    pagesToShow.push(totalPages);
                }
            }

            // Générer les boutons
            pagesToShow.forEach(page => {
                if (page === '...') {
                    const dots = document.createElement('span');
                    dots.textContent = '...';
                    dots.style.padding = '8px';
                    dots.style.color = '#666';
                    paginationControls.appendChild(dots);
                } else {
                    const pageBtn = document.createElement('button');
                    pageBtn.className = 'pagination-btn' + (page === currentPage ? ' active' : '');
                    pageBtn.textContent = page;
                    pageBtn.onclick = () => goToPage(page);
                    paginationControls.appendChild(pageBtn);
                }
            });

            // Next button
            const nextBtn = document.createElement('button');
            nextBtn.className = 'pagination-btn';
            nextBtn.innerHTML = '<i class="fas fa-chevron-right"></i>';
            nextBtn.disabled = currentPage === totalPages;
            nextBtn.onclick = () => goToPage(currentPage + 1);
            paginationControls.appendChild(nextBtn);
        }



        function goToPage(page) {
            currentPage = page;
            const paginatedVideos = getPaginatedVideos(allVideos, currentPage, pageSize);
            generateVideoCards(paginatedVideos);
            generatePagination();
        }

        function updatePageSize(newSize) {
            pageSize = parseInt(newSize);
            currentPage = 1;
            const paginatedVideos = getPaginatedVideos(allVideos, currentPage, pageSize);
            generateVideoCards(paginatedVideos);
            generatePagination();
        }

        function formatDuration(seconds) {
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const remainingSeconds = seconds % 60;

            if (hours > 0) {
                return `${hours}h ${minutes}m`;
            }
            return `${minutes}m ${remainingSeconds}s`;
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            const options = {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            };
            return date.toLocaleDateString('fr-FR', options);
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            const playerContainer = document.getElementById('videoPlayerContainer');

            if (e.key === 'Escape' && playerContainer.classList.contains('active')) {
                closeVideoPlayer();
            }
        });

        // Initialisation
        document.addEventListener('DOMContentLoaded', async () => {
            // Charger toutes les vidéos
            allVideos = await fetchAllVideos();

            // Afficher les vidéos
            const paginatedVideos = getPaginatedVideos(allVideos, currentPage, pageSize);
            generateVideoCards(paginatedVideos);
            generatePagination();

            // Event listener pour le changement de taille de page
            document.getElementById('pageSize').addEventListener('change', (e) => {
                updatePageSize(e.target.value);
            });
        });
    </script>
</body>

</html>
