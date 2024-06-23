import '../vendor/jquery_qrcode_js/jquery-qrcode.min.js';
import {copyToClipboard} from './function.js'
import {Modal} from 'bootstrap';

//modal short id sharing system
$('.btn-share-circle').on('click', function(e){
    // Extract info from data-bs-* attributes
    const circleName = $(this).data('circle-name')
    const circleJoinUri = $(this).data('circle-uri')
    
    if (navigator.share) { //share with device sharing system
        let data = {
            url:circleJoinUri,
            title:`Rejoint la boîte à partage "${circleName}"`,
            text:`Voici le lien pour rejoindre la boîte à partage "${circleName}"`
        
        }
        navigator.share(data)
        .then(() => console.log('Successful share'))
        .catch(error => console.log('Error sharing:', error));
    }
    else { //share with app sharing system
        const targetModal = $(this).data('bs-target')
        var $targetModal = $(targetModal) 
        // $('#shareCircleModal').find('.modal-title')
        let modalTitle = `Inviter des personnes à partager dans la boîte "${circleName}"`;
        let modalContent = `
            <p>Pour inviter des personnes à rejoindre cette boîte à partage, envoyez-leur le lien suivant :</p>
            <div class="alert alert-warning alert-static  d-flex justify-content-center" role="alert">
                <span class="fw-bold">${circleJoinUri}</span>
                <a href="#" class="text-success ms-3" id="copyCircleJoinUri" data-circle-uri="${circleJoinUri}" data-bs-toggle="tooltip" data-bs-title="Copier le lien"><i class="bi bi-copy me-2"></i>Copier</a>
            </div>
            <p>Où invitez-les à scanner le QR Code ci-dessous :</p>
            <div class="text-center" id="qrcode-container">
                <div id="qrcode"></div>
            </div>
        `;
        $targetModal.find('.modal-title').text(modalTitle);
        $targetModal.find('.modal-body').html(modalContent);
        //create qrcode and add to modal
        $('#qrcode').qrcode({width: 256,height: 256,text: `${circleJoinUri}`});
        const bootstrapTooltips = $('[data-bs-toggle="tooltip"]').toArray();
        bootstrapTooltips.forEach(el => new Tooltip(el, {
            container: 'body'
        }))

        //share btn copy to clipboard
        $('#copyCircleJoinUri').on('click', function(e){
            e.preventDefault();
            const tt = Tooltip.getOrCreateInstance('#copyCircleJoinUri')
            tt.setContent({ '.tooltip-inner': 'Copié !' })
            setTimeout(() => {
                tt.setContent({ '.tooltip-inner': 'Copier le lien' })
            }, "5000");
            let text = $(this).data('circle-uri')
            copyToClipboard(text) //TODO find a way to acess a function globally

        })
        const myModal = new Modal(targetModal, {}).show()
        // $targetModal.
    }

})
