import 'jquery.qrcode';
import {copyToClipboard} from './function.js'
import {Modal} from 'bootstrap';

import { jsPDF } from "jspdf";

//import custom fonts
import './fonts/Lexend-Black.js'
import './fonts/Lexend-Bold.js'
import './fonts/Lexend-ExtraBold.js'
import './fonts/Lexend-ExtraLight.js'
import './fonts/Lexend-Light.js'
import './fonts/Lexend-Medium.js'
import './fonts/Lexend-Regular.js'
import './fonts/Lexend-SemiBold.js'
import './fonts/Lexend-Thin.js'
import './fonts/Caveat-Bold.js'


// Generate A4 PDF leaflet
const doc = new jsPDF();
//get logo
var logoImg = new Image()
logoImg.src = $('#logoImgSrc').data('url');
//get globe img
var globeImg = new Image()
globeImg.src = $('#globeImgSrc').data('url');
//get mouse img
var mouseImg = new Image()
mouseImg.src = $('#mouseImgSrc').data('url');
//get prise-en-main img
var priseEnMainImg = new Image()
priseEnMainImg.src = $('#priseEnMainImgSrc').data('url');
//fake qr code
$('#qr').qrcode({width: 256,height: 256,text: `https://boiteapartage.org/rejoindre-QBDFR4`});


//dark blue : .setTextColor(43,68,89);
//dark red : .setTextColor(219,98,84);
doc.setFont('Lexend-Black').setFontSize(25).setTextColor(43,68,89);
doc.text("UNE BOÎTE À PARTAGE SE LANCE DANS NOTRE RÉSIDENCE", 105, 25, {
    'align': 'center',
    'maxWidth' : 160
});
doc.setFont('Lexend-SemiBold').setFontSize(13).setTextColor(43,68,89);
doc.text("Référence de la boîte à partage : ", 10, 54, {
    // 'align': 'left',
});
doc.setFont('Lexend-Black').setFontSize(13).setTextColor(219,98,84);
doc.text("QBN3N3", 87.5, 54.2, {
    // 'align': 'left',
});

doc.setFont('Lexend-Regular').setFontSize(13).setTextColor(43,68,89);
doc.text([
        "Chères voisines, chers voisins,",
        "",
        "J’ai le plaisir de vous informer que j’ai créé une boîte à partage pour la Résidence du Square sur www.boiteapartage.org.",
        "",
        "Cette plateforme nous permet de créer un catalogue en ligne des objets disponibles pour emprunt au sein de la résidence.",
        "Chacun alimente le catalogue avec ses objets (outils de bricolage, matériel de cuisine, livres, jeux de société, etc..) et seules les personnes ayant rejoint notre boîte à partage pourront la consulter et faire une demande de contact.",
        "Nous avons tous des appareils, des rallonges électriques, des moules à gâteau et tout un tas d’autres objets qui peuvent servir lorsque nous ne les utilisons pas, alors partageons-les !",
        "Que ce soit pour se dépanner, s’inspirer, découvrir de nouvelles lectures ou expérimenter de nouvelles activités, cette initiative a pour ambition de faire circuler nos biens, de faciliter l’entraide et de réduire notre empreinte sur l’environnement."
    ], 10, 70, {
        'maxWidth' : 190,
        'align': 'justify',
});

doc.setFont('Lexend-SemiBold').setFontSize(13).setTextColor(43,68,89);
doc.text("Pour rejoindre la boîte à partage de la Résidence du Square : ", 10, 161, {
    // 'align': 'left',
});

doc.setFont('Lexend-Black').setFontSize(13).setTextColor(43,68,89);
doc.text("Scannez le QR Code ci-dessous :", 15, 175, {
    // 'align': 'left',
});
//Get qrcode data
var qrData = $('#qr canvas')[0].toDataURL("image/png");
doc.addImage(qrData, 'png', 37, 181, 30, 30);
doc.text("Ou rendez-vous sur :", 125, 175, {
    // 'align': 'left',
});
doc.setDrawColor(43,68,89)
doc.setLineWidth(0.5)
doc.rect(98, 189, 98.5, 9.5)
doc.addImage(globeImg, 'PNG', 101, 192, 4, 4)
doc.setFont('Lexend-Medium').setFontSize(10.9).setTextColor(43,68,89)
doc.text("www.boiteapartage.org/", 107, 195, {
    // 'align': 'left',
});
doc.setFont('Lexend-Black').setFontSize(11.3).setTextColor(219,98,84)
doc.text("rejoindre-QBN3N3", 154.5, 195, {
    // 'align': 'left',
});
doc.addImage(mouseImg, 'PNG', 188, 196, 3.8, 5.2)

doc.addImage(priseEnMainImg, 'PNG', 20, 220, 1704/10, 352/10)
//signature
doc.setFont('Caveat-Bold').setFontSize(23).setTextColor(43,68,89)
doc.text("Corentin Thiercelin", 192, 272, {
    'align': 'right',
});
//footer
doc.addImage(logoImg, 'JPEG', 10, 278, 8, 8)
// extralight 3mm : Ce contenu a été édité sur 
doc.setFont('Lexend-ExtraLight').setFontSize(8.5).setTextColor(43,68,89)
doc.text("Ce contenu a été édité sur ", 21, 283);
// regular 3mm : www.boiteapartage.org
doc.setFont('Lexend-Regular').setFontSize(8.5).setTextColor(43,68,89)
doc.text("www.boiteapartage.org", 57.8, 283);



const blob = doc.output("blob");
window.open(URL.createObjectURL(blob), "_blank");

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
        // $('#qrcode').qrcode({width: 256,height: 256,text: `${circleJoinUri}`});
        // const bootstrapTooltips = $('[data-bs-toggle="tooltip"]').toArray();
        // bootstrapTooltips.forEach(el => new Tooltip(el, {
        //     container: 'body'
        // }))

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
