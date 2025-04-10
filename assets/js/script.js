import $ from 'jquery';
var swLocation = $('#swLocation').data('content');
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register(swLocation)
        .then(function () {
            console.log('Enregistrement reussi.')
        })
        .catch(function (e) {console.error(e)});
}




    let deferredPrompt;
    window.addEventListener('beforeinstallprompt', (e) => {
        deferredPrompt = e;
    });

    const $installApp = $('#installApp');
    $installApp.on('click', async () => {
        if (deferredPrompt !== null) {
            deferredPrompt.prompt();
            const { outcome } = await deferredPrompt.userChoice;
            if (outcome === 'accepted') {
                deferredPrompt = null;
            }
        }
    });
    // installApp.addEventListener('click', async () => {
    //     if (deferredPrompt !== null) {
    //         deferredPrompt.prompt();
    //         const { outcome } = await deferredPrompt.userChoice;
    //         if (outcome === 'accepted') {
    //             deferredPrompt = null;
    //         }
    //     }
    // });