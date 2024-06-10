/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */

import $ from 'jquery';
import jquery from 'jquery';
window.$ = $;
window.jquery = jquery;
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

//dismiss alerts (flash_messages) automatically when not manually dismissable
setTimeout(() => {
    $('.alert-success:not(.alert-static)').fadeOut(500, function(){
        this.remove()
    })
}, "5000");