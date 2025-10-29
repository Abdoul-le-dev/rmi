<?php
    header('Cross-Origin-Opener-Policy: same-origin');
    header('Cross-Origin-Embedder-Policy: require-corp');
?>

<title>Zoom Meeting</title>

<link type="text/css" rel="stylesheet" href="https://source.zoom.us/3.8.0/css/bootstrap.css" />
<link type="text/css" rel="stylesheet" href="https://source.zoom.us/3.8.0/css/react-select.css" />

<script src="https://source.zoom.us/3.8.0/lib/vendor/react.min.js"></script>
<script src="https://source.zoom.us/3.8.0/lib/vendor/react-dom.min.js"></script>
<script src="https://source.zoom.us/3.8.0/lib/vendor/redux.min.js"></script>
<script src="https://source.zoom.us/3.8.0/lib/vendor/redux-thunk.min.js"></script>
<script src="https://source.zoom.us/3.8.0/lib/vendor/lodash.min.js"></script>
<script src="https://source.zoom.us/zoom-meeting-3.8.0.min.js"></script>


<style>
    #meetingSDKElement {
        width: 100% !important;
        height: 100vh !important;
    }
</style>

<div id="meetingSDKElement"></div>


<script>
    async function delay(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    document.addEventListener("DOMContentLoaded", function() {
        ZoomMtg.preLoadWasm();
        ZoomMtg.prepareWebSDK();

        async function initMeeting() {
            console.log("Initializing Zoom Meeting...");
            const meetingNumber = "<?php echo e($meetingNumber); ?>";
            const userName = "<?php echo e($username); ?>";
            const userEmail = 'dossoufico@gmail.com';
            const passWord = "<?php echo e($password); ?>";
            //0 for participant and 1 for Host
            const role = "<?php echo e($role); ?>"; // Host role

            const signatureResponse = await fetch(
                `/panel/sessions/generate-signature?meetingNumber=${meetingNumber}&role=${role}`, {
                    method: 'GET',
                    headers: {
                        'Cache-Control': 'no-store'
                    }
                });
            const signatureData = await signatureResponse.json();
            if (!signatureData.signature) {
                console.error("Signature is missing or invalid");
                return;
            }
            const signature = signatureData.signature;
            console.log('signature', signature)
            // Add a delay before initializing Zoom
            await delay(500); // 500ms delay

            ZoomMtg.init({
                debug: true,
                leaveUrl: "/panel",
                isSupportAV: true,
                success: function() {
                    ZoomMtg.join({
                        meetingNumber: meetingNumber,
                        userName: userName,
                        signature: signature,
                        sdkKey: "<?php echo e(env('ZOOM_SDK_KEY')); ?>",
                        userEmail: userEmail,
                        passWord: passWord,
                        success: function() {
                            console.log("Successfully started the meeting.");
                        },
                        error: function(error) {
                            console.error("Error starting meeting:", error);
                        }
                    });
                },
                error: function(error) {
                    console.error("Error initializing meeting:", error);
                }
            });
        }

        initMeeting()
    });
</script>
<?php /**PATH D:\RMI Class- AWS\resources\views/web/default/course/zoom.blade.php ENDPATH**/ ?>