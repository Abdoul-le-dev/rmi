(() => {
    let playerInstance;

    /**
     * Construit l'URL finale CloudFront
     */
    function buildCloudFrontUrl(domain, path) {
        if (!domain || !path) return null;

        domain = domain.replace(/\/$/, "");
        path = path.replace(/^\//, "");

        return `https://${domain}/${path}`;
    }

    let player;
    let qualityLevels;

    /**
     * Générer le HTML du lecteur vidéo
     * @param {string} videoUrl - URL complète de la vidéo
     * @param {string} storage - Type de stockage (non utilisé pour HLS)
     * @param {number} height - Hauteur du lecteur
     * @param {string} playerId - ID unique du lecteur
     * @returns {object} - HTML et options du lecteur
     */
    window.makeVideoPlayerHtml = function (
        videoUrl,
        storage,
        height,
        playerId,
    ) {
        const html = `
            <video 
                id="${playerId}" 
                class="video-js vjs-default-skin vjs-big-play-centered vjs-16-9" 
                playsinline
                controls 
                preload="auto"
                oncontextmenu="return false;"
                controlsList="nodownload noplaybackrate"
                disablePictureInPicture
                crossorigin="use-credentials"
                width="100%" 
                height="${height}"
                data-setup='{}'>
            </video>
        `;

        // 🔥 HLS (.m3u8)

        if (videoUrl.endsWith(".m3u8")) {
            return {
                html,
                options: {
                    html5: {
                        vhs: {
                            overrideNative: true,
                            enableLowInitialPlaylist: true,
                            withCredentials: true,
                            smoothQualityChange: true,
                        },
                        nativeAudioTracks: false,
                        nativeVideoTracks: false,
                    },
                    controls: true,
                    fluid: true,
                    responsive: true,
                    preload: "auto",
                    playbackRates: [0.5, 0.75, 1, 1.25, 1.5, 2],
                    controlBar: {
                        children: [
                            "playToggle",
                            "volumePanel",
                            "currentTimeDisplay",
                            "timeDivider",
                            "durationDisplay",
                            "progressControl",
                            "playbackRateMenuButton",
                            "fullscreenToggle",
                        ],
                    },
                    sources: [
                        {
                            src: videoUrl,
                            type: "application/x-mpegURL",
                        },
                    ],
                },
            };
        }

        // 🎥 MP4 fallback
        return {
            html,
            options: {
                controls: true,
                fluid: true,
                preload: "auto",
                sources: [
                    {
                        src: videoUrl,
                        type: "video/mp4",
                    },
                ],
            },
        };
    };

    /**
     * Charge et joue une vidéo à partir de son file_id
     */
    window.handleVideoByFileId = function (fileId, container, callback) {
        closeVideoPlayer();

        const height = $(window).width() > 991 ? 426 : 264;

        $.post("/course/getFilePath", { file_id: fileId }, function (response) {
            // console.log("📦 Réponse brute backend:", response);
            if (!response || response.code !== 200) {
                return showAccessError();
            }

            let videoUrl = response.path;

            // 🔥 S3 / CloudFront → reconstruction URL
            if (response.cloudFrontDomain) {
                videoUrl = buildCloudFrontUrl(
                    response.cloudFrontDomain,
                    response.path,
                );
            }

            if (!videoUrl) {
                return showAccessError();
            }

            const playerId = "videoPlayer" + fileId;

            const playerData = makeVideoPlayerHtml(
                videoUrl,
                response.storage,
                height,
                playerId,
            );

            container.html(playerData.html);

            playerInstance = videojs(playerId, playerData.options);
            playerInstance.ready(function () {
                if (videoUrl.endsWith(".m3u8")) {
                    const player = this;
                    player.qualitySelectorHls();

                    const qualityLevels = player.qualityLevels();

                    // On écoute le changement de sélection dans le menu
                    qualityLevels.on("change", function () {
                        // On récupère l'index choisi par l'utilisateur dans l'interface
                        const newIndex = qualityLevels.selectedIndex;

                        console.log(
                            newIndex === -1
                                ? "Retour en mode Auto"
                                : "🎯 Tentative vers l'index : " + newIndex,
                        );

                        // TECHNIQUE DE SÉCURITÉ :
                        // Au lieu de faire une boucle for qui déclenche l'IndexSizeError,
                        // on utilise un setTimeout pour laisser Video.js finir son cycle actuel.
                        setTimeout(() => {
                            try {
                                for (let i = 0; i < qualityLevels.length; i++) {
                                    // Si -1 (Auto), on active tout. Sinon, on n'active que l'index choisi.
                                    qualityLevels[i].enabled =
                                        newIndex === -1 || i === newIndex;
                                }

                                // Si on a choisi une qualité spécifique, on force un petit saut
                                // pour vider le buffer proprement sans faire planter le moteur.
                                if (newIndex !== -1) {
                                    const currentTime = player.currentTime();
                                    player.currentTime(currentTime + 0.01);
                                }
                            } catch (e) {
                                console.warn(
                                    "Erreur de changement de qualité évitée :",
                                    e,
                                );
                            }
                        }, 100); // 100ms de délai pour stabiliser le moteur
                    });
                }
            });

            callback && callback();
        }).fail(function (xhr, status, error) {
            // console.error("❌ ERREUR AJAX");
            // console.error("Status HTTP :", xhr.status); // 500
            // console.error("Status text :", xhr.statusText);
            // console.error("Erreur JS :", error);

            // console.error("ResponseText brut :");
            // console.error(xhr.responseText);

            // si backend renvoie du JSON même en erreur
            try {
                // console.error("Response JSON :", JSON.parse(xhr.responseText));
            } catch (e) {
                console.warn("Response non JSON");
            }
        });
    };

    /**
     * Détruit le player proprement
     */
    window.closeVideoPlayer = function () {
        if (playerInstance) {
            playerInstance.dispose();
            playerInstance = null;
        }
    };

    /**
     * Pause la vidéo
     */
    window.pauseVideoPlayer = function () {
        if (playerInstance) {
            playerInstance.pause();
        }
    };

    /**
     * Toast erreur accès
     */
    function showAccessError() {
        $.toast({
            heading: notAccessToastTitleLang,
            text: notAccessToastMsgLang,
            bgColor: "#f63c3c",
            textColor: "white",
            hideAfter: 10000,
            position: "bottom-right",
            icon: "error",
        });
    }
})();
