import axios from 'axios';
import _ from 'lodash';
import { Modal } from 'bootstrap';
import {Html5Qrcode} from "html5-qrcode"; //https://github.com/mebjas/html5-qrcode

/*
    INIT
*/

//docs : https://scanapp.org/html5-qrcode-docs/docs/intro
const html5QrCode = new Html5Qrcode(/* element id */ "reader");
/*
    FUNCTIONS
*/

function searchBookOnApi(mode, terms){
    var totalItems;
    var results = [];
    // var endpoints = [];
    // let endpoints = [
    //     'https://api.github.com/users/ejirocodes',
    //     'https://api.github.com/users/ejirocodes/repos',
    //     'https://api.github.com/users/ejirocodes/followers',
    //     'https://api.github.com/users/ejirocodes/following'
    //   ];
      
    //   axios.all(endpoints.map((endpoint) => axios.get(endpoint))).then(
    //     (data) => console.log(data),
    //   );
    //filter false search
    if(typeof(terms) !== undefined && terms !== "" && terms !== null){
        //detect ISBN regex terms.match(/\d{10}$|^\d{13}$/)
        if(mode == 'isbn'){
$            //search ISBN then terms
            axios.get('https://www.googleapis.com/books/v1/volumes?maxResults=5&orderBy=relevance&q=isbn:'+encodeURI(terms))
                .then(function (responseIsbn) {
                    if(responseIsbn.data.totalItems > 0){
                        totalItems = responseIsbn.data.totalItems;
                        results = _.uniqBy(responseIsbn.data.items, 'id');
                    }
                    displayResults(mode, results, totalItems);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .finally(function () {
                    // always executed
                });
        } 
        else {
            //search terms only
            axios.get('https://www.googleapis.com/books/v1/volumes?maxResults=20&orderBy=relevance&q='+encodeURI(terms))
                .then(function (responseTerms) {
                    // handle success
                    if(responseTerms.data.totalItems > 0){
                        totalItems = responseTerms.data.totalItems
                        results = _.uniqBy(responseTerms.data.items, 'id')
                    }
                    displayResults(mode, results, totalItems);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .finally(function () {
                    // always executed
                });
        }
    }
}

function displayResults(mode, results, totalItems){
    switch (mode) {
        case 'isbn':
            if(results.length > 0){
                // $('#reader').addClass('d-none');
                $('#scanResultContainer').show();
                $('#scanNoResultContainer').hide();
                let book = results[0]; //we consider only first result
                let title = book.volumeInfo.title;
                let author = book.volumeInfo.authors ? book.volumeInfo.authors.join(', ') : '';
                let imgLink = book.volumeInfo.imageLinks ? book.volumeInfo.imageLinks.thumbnail : encodeURI('https://placehold.co/70x100/transparent/FFFFF?text=Aucune\nimage\ndisponible&font=source-sans-pro')
                let refLink = book.selfLink || null;
                $('#scanResultContainer').empty();
                let $bookResult = `
                <a href="#" data-bs-dismiss="modal" class="link-dark link-underline link-offset-2 link-underline-opacity-25 link-underline-opacity-75-hover"><i class="me-2 bi bi-arrow-counterclockwise"></i>Recommencer</a>
                <div class="row d-flex justify-content-center mt-3">
                    <div class="col-8 col-sm-6 col-md-4 col-lg-2">
                        <div class="card" style="">
                            <img src="${imgLink}" class="card-img-top p-4" alt="book cover preview">
                            <div class="card-body border-top">
                                <h5 class="card-title">${title}</h5>
                                <p class="card-text">${author}</p>
                                <div class="d-grid">
                                    <a 
                                        id="selectScanResultBook" 
                                        href="#" 
                                        class="select-book-from-scan btn btn-primary self-align-right" 
                                        data-title="${title}" 
                                        data-author="${author}" 
                                        data-img-link="${imgLink}"
                                        data-ref-url="${refLink}" 
                                    >Sélectionner</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                `;
                $('#scanResultContainer').html($bookResult)
                // $('#scanResult').empty().append(title+' de ' + author);
                // $('#scanResultContainer').removeClass('d-none');
                // $('#wrongScanResult').removeClass('d-none');
            }  
            else {
                $('#scanResultContainer').hide();
                $('#scanNoResultContainer').show();
                $('#scanResultContainer').empty();
                let $bookResult = `
                <a href="#" data-bs-dismiss="modal" class="link-dark link-underline link-offset-2 link-underline-opacity-25 link-underline-opacity-75-hover"><i class="me-2 bi bi-arrow-counterclockwise"></i>Recommencer</a>
                <div class="row d-flex justify-content-center mt-3">
                    <div class="col">
                        <div class="fs-4">
                            Aucun résultat
                        </div>
                        <div class="mt-1">
                            Veuillez recommencer ou ajouter le livre à la main.
                        </div>
                    </div>
                </div>
                `;
                $('#scanNoResultContainer').html($bookResult)
            }
            break;
    
        default:
            $("#searchBookResults").removeClass('d-none')
            openAccordionResult();
            clearResults();
            displayLoader();
            // template de card
            var $resultCard = `
            <div class="row d-flex justify-content-center">
                <div class="col-8 col-md-6">
                    <div class="card card-book d-grid gap-2 mb-2" >
                        <div class="d-flex flex-row align-items-center">
                            <div class="col-3 col-md-1">
                                <img src="" class="img-responsive p-1" style="height:80px;" alt="book cover preview">
                            </div>
                            <div class="col-7 col-md-10 card-body text-left py-2">
                                <h5 class="card-title fs-6 fw-light"></h5>
                                <p class="card-text fs-6 fw-medium"></p>
                            </div>
                            <div class="col-2 col-md-1 form-check">
                                <input class="border-dark-subtle form-check-input" type="radio" data-title="" data-author="" data-ref-url="" data-img-link="" name="bookSelectionRadio" id="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            `;
            //clear previous results
            clearResults();
            removeLoader();
            if(results.length > 0){  
                _.forEach(results, function(book) {
                    let $card = $($resultCard).clone();
                    let title = book.volumeInfo.title;
                    let author = book.volumeInfo.authors ? book.volumeInfo.authors.join(', ') : '';
                    let imgLink = book.volumeInfo.imageLinks ? book.volumeInfo.imageLinks.thumbnail : encodeURI('https://placehold.co/70x100/transparent/FFFFF?text=Aucune\nimage\ndisponible&font=source-sans-pro')
                    let refLink = book.selfLink || null;
                    $card.find('.card-title').text(title)
                    $card.find('.card-text').text(author);
                    $card.find('.img-responsive').attr('src', imgLink)
                    $("#searchBookResultsList").append($card)
                    $card.find('input[name="bookSelectionRadio"]')
                        .data('title', book.volumeInfo.title ? book.volumeInfo.title : '')
                        .data('author', book.volumeInfo.authors ? book.volumeInfo.authors.join(', ') : '')
                        .data('img-link', imgLink)
                        .data('ref-url', refLink)
                });
            }
            else {
                $("#searchBookResultsList").append(`Aucun résultat`)
                $('#bookFormContainer').removeClass('d-none');
            }
            break;
    }
}

function clearResults(){
    $("#searchBookResultsList").empty()
}
function displayLoader(){
    $("#searchBookResultsList").append('Recherche...')
}
function removeLoader(){
    $("#searchBookResultsList").empty()
}


/*
    EVENTS DETECTION
*/

//scan barcode when requested
var barcodeScannerModal = Modal.getOrCreateInstance($('#barcodeScannerModal'));
$("#barcodeScannerModal").on('shown.bs.modal', function(e){
    //hide results and set title
    $('#barcodeScannerModalLabel').text("Visez le code-barres")
    $('#scanResultContainer').empty().hide()
    $('#scanNoResultContainer').hide()
    // This method will trigger user permissions
    Html5Qrcode.getCameras().then(devices => {
        /**
         * devices would be an array of objects of type:
         * { id: "id", label: "label" }
         */
        if (devices && devices.length) {
            // var cameraId = devices[0].id;
            html5QrCode.start(
            { facingMode: "environment" }, 
            {
                fps: 10,    // Optional, frame per seconds for qr code scanning
                qrbox: { width: 250, height: 250 }  // Optional, if you want bounded box UI
            },
            (decodedText, decodedResult) => {
                // do something when code is read
                console.log(decodedText, decodedResult);

                html5QrCode.stop().then((ignore) => {
                    // QR Code scanning is stopped.
                    // $("#searchBookByTermsInput").val(decodedText).trigger('submit') //set search to detected text
                    searchBookOnApi('isbn', decodedText) //search scanned isbn 
                    $('#barcodeScannerModalLabel').text("Résultats")
                    // barcodeScannerModal.hide(); //close modal
                }).catch((err) => {
                // Stop failed, handle it.
                });
            },
            (errorMessage) => {
                // parse error, ignore it.
                // console.log(errorMessage)
            })
            .catch((err) => {
            // Start failed, handle it.
            });
        }
    }).catch(err => {
        // handle err
    });
})
//ensure camera stops when the modal is closed manually 
$("#barcodeScannerModal").on('hidden.bs.modal', function(e){
    html5QrCode.stop().then((ignore) => {}).catch((err) => {});
})
$('body').on('click', '.select-book-from-scan', function(e){
    // e.preventDdefault();
    //set form inputs value from selected item in search results
    $('input[name="item_book_form[property_1]"]').val($(this).data('title'));
    $('input[name="item_book_form[property_2]"]').val($(this).data('author'));
    $('input[name="item_book_form[property_3]"]').val($(this).data('img-link'));
    $('input[name="item_book_form[property_4]"]').val($(this).data('ref-url'));
    //disable modification of inputs in form
    $('input[name="item_book_form[property_1]"]').prop("readonly", true);
    $('input[name="item_book_form[property_2]"]').prop("readonly", true);
    $('#infoAndScanContainer').hide()
    $('#restartCompleteForm').show();
    $('#bookFormTitle').text('Vérifiez les informations');
    barcodeScannerModal.hide();

})
// //search book on form submit
// $("#searchBookForm").on('submit', function(e){
//     e.preventDefault();
//     searchBookOnApi($("#searchBookByTermsInput").val());
//     resetSelectedBookFromResult();
// })

// //fill form when a book is slected in search results
// $('body').on('click', '.card-book', function(e){
//     let $selectedBookPreview = $(this).clone();
//     var $input = $(this).find('input')
//     $input.prop('checked', true)
//     $('.card-book').removeClass('active');
//     $(this).addClass('active');
//     // closeAccordionResult()
//     //set form inputs value from selected item in search results
//     $('input[name="item_book_form[property_1]"]').val($input.data('title'));
//     $('input[name="item_book_form[property_2]"]').val($input.data('author'));
//     $('input[name="item_book_form[property_3]"]').val($input.data('img-link'));
//     $('input[name="item_book_form[property_4]"]').val($input.data('ref-url'));
//     $('#item_book_form_submitFromSearchBookResult').prop('disabled', false);
//     //display selected result
//     $selectedBookPreview.removeClass('card-book').addClass('mt-2')
//     $selectedBookPreview.find('div.form-check').remove();
//     $('#selectedResult').find('.card').remove();
//     $('#selectedResult').append($selectedBookPreview);
//     $('#selectedResult').removeClass('d-none');
//     //d
//     // $('#bookFormContainer').removeClass('d-none');
//     closeAccordionResult();
// })

$('#restartCompleteForm').on('click', function(e){
    e.preventDefault();
    $('#infoAndScanContainer').show()
    $('#restartCompleteForm').hide();
    $('#bookFormTitle').text('Ajouter un livre manuellement');
    $('input[name="item_book_form[property_1]"]').val('');
    $('input[name="item_book_form[property_2]"]').val('');
    $('input[name="item_book_form[property_3]"]').val('');
    $('input[name="item_book_form[property_4]"]').val('');
    //disable modification of inputs in form
    $('input[name="item_book_form[property_1]"]').prop("readonly", false);
    $('input[name="item_book_form[property_2]"]').prop("readonly", false);
})
// $('body').on('click', '.cant-find-book-btn', function(e){
//     $('#selectedResultInForm').empty();
//     $('input[name="item_book_form[property_1]"]').val('');
//     $('input[name="item_book_form[property_2]"]').val('');
//     $('input[name="item_book_form[property_3]"]').val('');
//     $('input[name="item_book_form[property_4]"]').val('');
//     $('#bookFormContainer').removeClass('d-none');
//     resetSelectedBookFromResult();
//     closeAccordionResult();
// })
// //reset masked properties if any manual property changeschange  any of manual property editable
// //item_book_form_property_1

// function openAccordionResult(){
//     $('#accordionHeaderBtn').removeClass('collapsed').attr('aria-expanded', true);
//     $('#accordionBody').addClass('show')
// }
// function closeAccordionResult(){
//     $('#accordionHeaderBtn').addClass('collapsed').attr('aria-expanded', false)
//     $('#accordionBody').removeClass('show')
// }

// function resetSelectedBookFromResult(){
//     $('#selectedResult').find('div.card').remove();
//     $('#selectedResult').addClass('d-none');
// }