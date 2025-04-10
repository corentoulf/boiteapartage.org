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


//Define flyer variable used in flyer
var flyerVar = {
    'circleName': null,
    'circleJoinUri': null,
    'circleShortId': null,
    'circleType': null,
    'ownerFirstName': null,
    'ownerlastName': null,
}
//generate "flyer-edition" modal and open it
$('.btn-generate-flyer').on('click', function(e){
    // Extract info from data-* attributes
    flyerVar.circleName = $(this).data('circle-name')
    flyerVar.circleJoinUri = $(this).data('circle-uri')
    flyerVar.circleShortId = $(this).data('circle-short-id')
    flyerVar.circleType = $(this).data('circle-type')
    flyerVar.ownerFirstName = $(this).data('owner-first-name')
    flyerVar.ownerlastName = $(this).data('owner-last-name')
    const targetModal = $(this).data('bs-target')
    var $targetModal = $(targetModal) 
    // $('#shareCircleModal').find('.modal-title')
    let modalTitle = `Générer un flyer pour la boîte "${flyerVar.circleName}"`;
    let modalContent = `
        <p>En téléchargeant ce flyer vous pourrez partager et imprimer un document qui explique comment fonctionne et comment rejoindre la boîte à partage que vous avez créé.</p>
        <p>Vous pourrez afficher ou distribuer ce document aux personnes concernées par la boîte à partage.</p>
        <div class="text-center d-none" id="qrcode-container">
            <div id="flyerQrcode"></div>
        </div>
    `;
    $targetModal.find('.modal-title').text(modalTitle);
    $targetModal.find('.modal-body').html(modalContent);
    //create qrcode and add to modal
    $('#flyerQrcode').qrcode({width: 256,height: 256,text: `${flyerVar.circleJoinUri}`});
    var flyer = generate(flyerVar)
    // <object class="flyerPreview mt-3" data="" type="application/pdf" width="100%" height="500px">
    // </object>
    // const blob = flyer.output("bloburl", 'flyer-boite-a-partage-'+flyerVar.circleShortId);
    // $('.flyerPreview').attr('data', blob)
    $('.btn-download-flyer').attr('href', flyer.output('bloburl'));
    $('.btn-download-flyer').attr('download', 'flyer-boite-a-partage-'+flyerVar.circleShortId);
    // $('.btn-download-flyer').on('click', function(e){
    //     if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent))
    //     {
    //         var flyerBlobUrl = flyer.output('bloburl', );
    //         // var file = new Blob([response.data], { type: 'application/pdf' });
    //         // var fileURL = URL.createObjectURL(file);

    //         // create <a> tag dinamically
    //         var fileLink = document.createElement('a');
    //         fileLink.href = flyerBlobUrl;

    //         // it forces the name of the downloaded file
    //         fileLink.download = 'pdf_name';

    //         // triggers the click event
    //         fileLink.click();


    //         window.open(flyerBlobUrl);
    //     }
    //     else
    //     {
    //         flyer.save('flyer-boite-a-partage-'+flyerVar.circleShortId)
    //     }
    // })
    const myModal = new Modal(targetModal, {}).show()

})

//generate flyer
function generate(flyerVar){
    //dark blue : .setTextColor(43,68,89);
    //dark red : .setTextColor(219,98,84);
    const doc = new jsPDF()
    //define logo
    var logoImg = new Image()
    logoImg.src = $('#logoImgSrc').data('url');
    //define globe img
    var globeImg = new Image()
    globeImg.src = $('#globeImgSrc').data('url');
    //define mouse img
    var mouseImg = new Image()
    mouseImg.src = $('#mouseImgSrc').data('url');
    //define prise-en-main img
    var priseEnMainImg = new Image()
    priseEnMainImg.src = $('#priseEnMainImgSrc').data('url');

    //main title
    const circleTypeHeadTitle = {
        'building': 'DANS NOTRE RÉSIDENCE',
        'district': 'DANS NOTRE QUARTIER',
        'village': 'DANS NOTRE VILLAGE',
        'company': 'DANS NOTRE ENTREPRISE',
        'association': 'DANS NOTRE ASSOCIATION',
        'other': 'POUR NOUS'
    } 
    doc.setFont('Lexend-Black').setFontSize(25).setTextColor(43,68,89);
    doc.text("UNE BOÎTE À PARTAGE SE LANCE "+circleTypeHeadTitle[flyerVar.circleType], 105, 25, {
        'align': 'center',
        'maxWidth' : 160
    });
    //circle ref
    doc.setFont('Lexend-SemiBold').setFontSize(13).setTextColor(43,68,89);
    doc.text("Référence de la boîte à partage : ", 10, 54, {
        // 'align': 'left',
    });
    doc.setFont('Lexend-Black').setFontSize(13).setTextColor(219,98,84);
    doc.text(flyerVar.circleShortId, 87.5, 54.2);
    
    //main text
    const circleTypefirstText = {
        'building': 'notre résidence',
        'district': 'notre quartier',
        'village': 'notre village',
        'company': 'notre entreprise',
        'association': 'notre association',
        'other': 'nous'
    } 
    const circleTypetext = {
        'building': 'au sein de la résidence',
        'district': 'au sein de notre quartier',
        'village': 'au sein de notre village',
        'company': 'au sein de notre entreprise',
        'association': 'au sein de notre association',
        'other': 'entre nous'
    }
    const circleTypeInit = {
        'building': 'Chères voisines, chers voisins,',
        'district': 'Chères voisines, chers voisins,',
        'village': 'Chères voisines, chers voisins,',
        'company': 'Chères collègues, chers collègues,',
        'association': 'Chères membres, chers membres,',
        'other': ''
    } 
    doc.setFont('Lexend-Regular').setFontSize(13).setTextColor(43,68,89);
    doc.text([
            circleTypeInit[flyerVar.circleType],
            "",
            "J’ai le plaisir de vous informer que j’ai créé une boîte à partage pour " + circleTypefirstText[flyerVar.circleType] + " sur www.boiteapartage.org.",
            "",
            "Cette plateforme nous permet de créer un catalogue en ligne des objets disponibles pour emprunt "+circleTypetext[flyerVar.circleType]+".",
            "Chacun alimente le catalogue avec ses objets (outils de bricolage, matériel de cuisine, livres, jeux de société, etc..) et seules les personnes ayant rejoint notre boîte à partage pourront la consulter et faire une demande de contact.",
            "Nous avons tous des appareils, des rallonges électriques, des moules à gâteau et tout un tas d’autres objets qui peuvent servir lorsque nous ne les utilisons pas, alors partageons-les !",
            "Que ce soit pour se dépanner, s’inspirer, découvrir de nouvelles lectures ou expérimenter de nouvelles activités, cette initiative a pour ambition de faire circuler nos biens, de faciliter l’entraide et de réduire notre empreinte sur l’environnement."
        ], 10, 70, {
            'maxWidth' : 190,
            'align': 'justify',
    });
    doc.setFont('Lexend-SemiBold').setFontSize(13).setTextColor(43,68,89);
    doc.text("Pour rejoindre la boîte à partage \""+flyerVar.circleName+"\" : ", 10, 161);
    //Scan QR
    doc.setFont('Lexend-Black').setFontSize(13).setTextColor(43,68,89);
    doc.text("Scannez le QR Code ci-dessous :", 15, 175);
    //Get qrcode data
    var qrData = $('#flyerQrcode canvas')[0].toDataURL("image/png");
    doc.addImage(qrData, 'png', 37, 181, 30, 30);
    //visit website
    doc.text("Ou rendez-vous sur :", 120, 175);
    doc.setDrawColor(43,68,89)
    doc.setLineWidth(0.5)
    doc.rect(98, 189, 98.5, 9.5)
    doc.addImage(globeImg, 'PNG', 101, 192, 4, 4)
    doc.setFont('Lexend-Medium').setFontSize(10.9).setTextColor(43,68,89)
    doc.text("www.boiteapartage.org/", 107, 195);
    doc.setFont('Lexend-Black').setFontSize(11.3).setTextColor(219,98,84)
    doc.text("rejoindre-"+flyerVar.circleShortId, 154.5, 195);
    doc.addImage(mouseImg, 'PNG', 188, 196, 3.8, 5.2)
    //explanation image
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
    return doc;
}







// doc.save('test.pdf');