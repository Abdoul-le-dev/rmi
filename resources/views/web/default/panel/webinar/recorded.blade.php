@extends(getTemplate() .'.panel.layouts.panel_layout')

@push('styles_top')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endpush

@section('content')
<div class="container my-5">
    <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
        <h2 class="section-title">{{ trans('panel.recorded_videos') }}</h2>
    </div>
    <div class="row mt-30" id="videoList">
        <!-- Video cards will be dynamically generated here -->
    </div>
</div>

<!-- Modal for playing video -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="videoModalLabel">Video</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe id="videoIframe" class="embed-responsive-item" src="" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const accessToken = "{{ env('VIMEO_ACCESS_TOKEN') }}"; // Vimeo Access Token
    const vimeoUserId = "{{ env('VIMEO_USER_ID') }}"; // Vimeo User ID
    const folderId = 23672880

    async function fetchVimeoVideos() {
        try {
            const response = await fetch(`https://api.vimeo.com/users/${vimeoUserId}/projects/${folderId}/videos`, {
                headers: {
                    'Authorization': `Bearer ${accessToken}`
                }
            });
            const data = await response.json();
            return data.data;
        } catch (error) {
            console.error('Error fetching Vimeo videos:', error);
            return [];
        }
    }

    function openVideoModal(videoId) {
        // Set the video URL in the iframe using the embeddable link format
        document.getElementById('videoIframe').src = `https://player.vimeo.com/video/${videoId}?dnt=1&title=0&byline=0&portrait=0&badge=0&loop=0&autoplay=1`;
        $('#videoModal').modal('show');
    }

    function generateVideoCards(videos) {
        const videoListDiv = document.getElementById('videoList');
        videoListDiv.innerHTML = '';

        if (videos.length === 0) {
            videoListDiv.innerHTML = '<div class="text-center text-muted fw-bolder fs-6">No videos available</div>';
        } else {
            videos.forEach(video => {
                const videoCard = document.createElement('div');
                videoCard.className = 'col-12 mb-4';

                videoCard.innerHTML = `
                    <div class="col-12">
                        <div class="webinar-card webinar-list d-flex">
                            <div class="image-box">
                                <img src="${video.pictures.sizes[2].link}" class="img-cover video-thumbnail cursor-pointer" onclick="openVideoModal('${video.uri.split('/')[2]}')">
                            </div>
                            <div class="webinar-card-body w-100 d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h3 class="webinar-title font-weight-bold font-16 text-dark-blue cursor-pointer" onclick="openVideoModal('${video.uri.split('/')[2]}')">
                                        ${video.name}
                                    </h3>
                                </div>

                                <div class="webinar-price-box mt-15">
                                    <span class="real">Recorded Video</span>
                                </div>

                                <div class="d-flex align-items-center justify-content-between flex-wrap mt-auto">
                                    <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                        <span class="stat-title">Category:</span>
                                        <span class="stat-value">${video.tags && video.tags[0] ? video.tags[0].name : 'General'}</span>
                                    </div>
                                    <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                        <span class="stat-title">Duration:</span>
                                        <span class="stat-value">${formatDuration(video.duration)} Hrs</span>
                                    </div>
                                    <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                        <span class="stat-title">Purchase Date:</span>
                                        <span class="stat-value">4 Nov 2024</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                videoListDiv.appendChild(videoCard);
            });
        }
    }

    function formatDuration(seconds) {
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = seconds % 60;
        return `${minutes}m ${remainingSeconds}s`;
    }

    document.addEventListener('DOMContentLoaded', async () => {
        const videos = await fetchVimeoVideos();
        generateVideoCards(videos);
    });

    // Clear iframe source when the modal is closed
    $('#videoModal').on('hidden.bs.modal', function () {
        document.getElementById('videoIframe').src = '';
    });
</script>

@endsection

@section('js')
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/js/bootstrap.min.js"></script>
@endsection
