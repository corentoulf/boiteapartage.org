/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */

import $ from 'jquery';
window.$ = $;
import './vendor/bootstrap-icons/font/bootstrap-icons.min.css';

//a priori useless
// import './vendor/@popperjs/core/core.index.js';
// import './vendor/bootstrap/bootstrap.index.js';
// import 'bootstrap';


//handle Bootstrap Tooltips
import { Tooltip } from 'bootstrap'
const bootstrapTooltips = $('[data-bs-toggle="tooltip"]').toArray();
bootstrapTooltips.forEach(el => new Tooltip(el))

//handle Bootstrap Popovers
import { Popover } from 'bootstrap'
const bootstrapPopovers = $('[data-bs-toggle="popover"]').toArray();
bootstrapPopovers.forEach(function(el){
    const popoverId = el.attributes['data-bs-content-id'];

    if(popoverId){
        const contentEl=$(`#${popoverId.value}`).html();
        console.log(contentEl)
        new Popover(el,{
            content: contentEl,
            html: true
        })
    } else {
        new Popover(el,{
            html: false
        })
    }
})
//dismiss alerts (flash_messages) automatically when not manually dismissable
setTimeout(() => {
    $('.alert-success').fadeOut(500, function(){
        this.remove()
    })
}, "5000");

//handle search object request from homepage
$("#homeSearchForm").on('submit', function(e){
    e.preventDefault();
    var targetPath = $('#targetPathInput').val();
    var circleChoice = $('#circleChoiceSelect').val();
    var searchTerms = $('#searchTermsInput').val();
    if(circleChoice == "all"){
        targetPath = encodeURI(targetPath + '?q=' + searchTerms) //add search terms and encode
    }
    else {
        let uriArr = targetPath.split('/')
        uriArr.pop() //remove "/all"
        uriArr.push(circleChoice) //add selected circle ID
        targetPath = encodeURI(uriArr.join('/') + '?q=' + searchTerms) //add search terms and encode
    }
    window.location.replace(targetPath);
})

//modal short id sharing system
const shareCircleModal = document.getElementById('shareCircleModal')
if (shareCircleModal) {
    shareCircleModal.addEventListener('show.bs.modal', event => {
    // Button that triggered the modal
    const button = event.relatedTarget
    // Extract info from data-bs-* attributes
    const circleName = button.getAttribute('data-bs-circle-name')
    const circleShortId = button.getAttribute('data-bs-circle-short-id')
    const circleJoinUri = button.getAttribute('data-bs-circle-uri')
    // If necessary, you could initiate an Ajax request here
    // and then do the updating in a callback.

    // Update the modal's content.
    const modalTitle = shareCircleModal.querySelector('.modal-title')
    const modalBodyInput = shareCircleModal.querySelector('.modal-body')

    modalTitle.textContent = `Inviter des personnes à rejoindre "${circleName}"`
    modalBodyInput.innerHTML = `
        <p>Pour inviter des personnes à rejoindre le cercle, envoyez-leur le lien de partage suivant :</p>
        <p class="fw-bold text-center">${circleJoinUri}<button class="btn btn-outline-success btn-sm ms-3" id="copyCircleJoinUri" data-circle-uri="${circleJoinUri}"><i class="bi bi-copy me-2"></i>Copier le lien</button></p>
        
    `;
  })
}

//share btn copy to clipboard
$('body').on('click', '#copyCircleJoinUri', function(e){
    e.preventDefault();
    let text = $(this).data('circle-uri')
    copyToClipboard(text)
})

async function copyToClipboard(text) {
    await window.navigator.clipboard.writeText(text)
    addFlash('success', 'Lien copié avec succès');
}

function addFlash(label, message){
    $('#flashMessageContainer').html(
        `
            <div class="col mt-3 me-2 alert d-flex justify-content-between alert-${label} ${label == 'success' ? '' : 'alert-dismissable fade show'}" role="alert" style="max-width:30%">
                <p class="m-0 me-2">${message}</p>
                ${label == 'success' ? '' : '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'}
            </div>
        `
    )
    setTimeout(() => {
        $('.alert-success').fadeOut(500, function(){
            this.remove()
        })
    }, "5000");
}