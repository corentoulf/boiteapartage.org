/*
* Welcome to your app's main JavaScript file!
*
* This file will be included onto the page via the importmap() Twig function,
* which should already be in your base.html.twig.
*/

import $ from 'jquery';
// import jquery from 'jquery';;
window.$ = $;
window.jquery = $;
window.jQuery = $;


import './vendor/bootstrap-icons/font/bootstrap-icons.min.css';
//a priori useless
// import './vendor/@popperjs/core/core.index.js';
// import './vendor/bootstrap/bootstrap.index.js';
// import 'bootstrap';

//handle Bootstrap Tooltips
import { Tooltip } from 'bootstrap';
window.Tooltip = Tooltip;
const bootstrapTooltips = $('[data-bs-toggle="tooltip"]').toArray();
bootstrapTooltips.forEach(el => new Tooltip(el, {
    container: 'body'
}))


//handle Bootstrap Popovers
import { Popover } from 'bootstrap'
const bootstrapPopovers = $('[data-bs-toggle="popover"]').toArray();
bootstrapPopovers.forEach(function(el){
    const popoverId = el.attributes['data-bs-content-id'];

    if(popoverId){
        const contentEl=$(`#${popoverId.value}`).html();
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

//fix modal error (see https://github.com/twbs/bootstrap/issues/41005#issuecomment-2585390544)  
window.addEventListener('hide.bs.modal', () => {
    if (document.activeElement instanceof HTMLElement) {
        document.activeElement.blur();
    }
});

//dismiss alerts (flash_messages) automatically when not manually dismissable
setTimeout(() => {
    $('.alert-success:not(.alert-static)').fadeOut(500, function(){
        this.remove()
    })
}, "3000");

//PWA app concerns
// import './js/script.js';
// import './js/sw.js';


// usurpation
$('#usurpateForm').on('submit', function(e){
    e.preventDefault();
    let manualEmail = $(this).find('input[name="usurpateEmailInput"').val()
    let automaticEmail = $(this).find('select[name="usurpateEmailSelect"').val()
    if(manualEmail !== ""){
        location.replace('/app/accueil/?_switch_user='+manualEmail)
    } else {
        location.replace('/app/accueil/?_switch_user='+automaticEmail)
    }
})