import '../vendor/jquery_qrcode_js/jquery-qrcode.min.js';
import {copyToClipboard} from './function.js'
import {Modal} from 'bootstrap';

import { jsPDF } from "jspdf";

const logoSvg = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><defs><style>.cls-1{fill:#52749c;stroke:#456482;stroke-width:10px;}.cls-1,.cls-2{stroke-miterlimit:10;}.cls-2{fill:#ebb3a9;stroke:#fff;stroke-width:12.05px;}.cls-3{fill:#e86252;}</style></defs><title>Fichier 11</title><g id="Calque_2" data-name="Calque 2"><g id="Calque_7_-_copie" data-name="Calque 7 - copie"><circle class="cls-1" cx="100" cy="100" r="95"/><g id="part-pizza"><path class="cls-2" d="M62.09,145.22l91.24-45.35a.83.83,0,0,0,.16-1.38h0A140.37,140.37,0,0,0,91.07,46.12h0a.83.83,0,0,0-1.32.4L60.93,144.24A.83.83,0,0,0,62.09,145.22Z"/><circle class="cls-3" cx="101.13" cy="75.18" r="7.5"/><circle class="cls-3" cx="119.86" cy="93.54" r="5.55"/><circle class="cls-3" cx="93.63" cy="108.33" r="7.14"/></g></g></g></svg>`;
// Generate A4 PDF leaflet
const doc = new jsPDF();
console.log(doc.getFontList())
doc.addSvgAsImage(logoSvg, 120, 50, 30, 30)
doc.setFont('Helvetica', 'Bold')
doc.text("Hello world!", 10, 10);
// doc.save('test.pdf');

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
        let modalTitle = `Inviter des personnes à rejoindre la boîte "${circleName}"`;
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
