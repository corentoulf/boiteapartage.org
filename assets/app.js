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
    // If necessary, you could initiate an Ajax request here
    // and then do the updating in a callback.

    // Update the modal's content.
    const modalTitle = shareCircleModal.querySelector('.modal-title')
    const modalBodyInput = shareCircleModal.querySelector('.modal-body')

    modalTitle.textContent = `Partage du cercle "${circleName}"`
    modalBodyInput.textContent = `Lien de partage du cercle"${circleShortId}"`
  })
}