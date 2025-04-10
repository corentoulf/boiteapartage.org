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

function searchBookOnApi(terms){
    var isIsbn = false;
    var endpoints = [];
    // let endpoints = [
    //     'https://api.github.com/users/ejirocodes',
    //     'https://api.github.com/users/ejirocodes/repos',
    //     'https://api.github.com/users/ejirocodes/followers',
    //     'https://api.github.com/users/ejirocodes/following'
    //   ];
      
    //   axios.all(endpoints.map((endpoint) => axios.get(endpoint))).then(
    //     (data) => console.log(data),
    //   );
    var results = [];
    var totalItems = 0
    //filter false search
    if(typeof(terms) !== undefined && terms !== "" && terms !== null){
        console.log('searching', terms);
        $("#searchBookResults").removeClass('d-none')
        openAccordionResult();
        clearResults();
        displayLoader();
        //detect ISBN
        if(terms.match(/\d{10}$|^\d{13}$/) !== null ){
            isIsbn = true;
            //search ISBN then terms
            axios.get('https://www.googleapis.com/books/v1/volumes?maxResults=20&orderBy=relevance&q=isbn:'+encodeURI(terms))
                .then(function (responseIsbn) {
                    //fetch isbn first result text
                    
                    // fetch isbn terms results
                    axios.get('https://www.googleapis.com/books/v1/volumes?maxResults=20&orderBy=relevance&q='+encodeURI(terms))
                        .then(function (responseTerms) {
                            // handle success
                            if(responseIsbn.data.totalItems > 0){
                                totalItems = totalItems + responseIsbn.data.totalItems
                                results = _.concat(results, responseIsbn.data.items)
                            }
                            //TODO : if only 1 result -> prefered result

                            if(responseTerms.data.totalItems > 0){
                                totalItems = totalItems + responseTerms.data.totalItems
                                results = _.concat(results, responseTerms.data.items)
                            }
                            //keep unique values in case of doublon
                            results = _.uniqBy(results, 'id')
                            displayResults(results, totalItems);
                        })
                        .catch(function (error) {
                            // handle error
                            console.log(error);
                        })
                        .finally(function () {
                            // always executed
                        });
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
                        totalItems = totalItems + responseTerms.data.totalItems
                        results = _.concat(results, responseTerms.data.items)
                    }
                    displayResults(results, totalItems);
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

function displayResults(results, totalItems){
    var $resultCard = `
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
                <input class="form-check-input" type="radio" data-title="" data-author="" data-ref-url="" data-img-link="" name="bookSelectionRadio" id="">
            </div>
        </div>
    </div>
    `;
    console.log('displaying results', totalItems, results)
    //clear previous results
    clearResults();
    removeLoader();
    if(results.length > 0){
        
        // $("#searchBookResultsList").append(`${totalItems > 100 ? '>100 résultats' : totalItems+' résultat(s)'}`)
        _.forEach(results, function(book) {
            let $card = $($resultCard).clone();
            let title = book.volumeInfo.title;
            let author = book.volumeInfo.authors ? book.volumeInfo.authors.join(', ') : '';
            let imgLink = book.volumeInfo.imageLinks ? book.volumeInfo.imageLinks.thumbnail : encodeURI('https://placehold.co/70x100/transparent/FFFFF?text=Aucune\nimage\ndisponible&font=source-sans-pro')
            let refLink = book.selfLink || null;


            $card.find('.card-title').text(title)
            $card.find('.card-text').text(author);
            $card.find('.img-responsive').attr('src', imgLink)
            console.log($card.find('input[name="bookSelectionRadio"]'))
            // `<li class="list-group-item" style="min-height:100px;"><img src="${}" class="img-thumbnail" style="height:100px;" alt="..."> ${book.volumeInfo.title} - Auteur(s) ${book.volumeInfo.authors ? book.volumeInfo.authors.join(', ') : ''}</li>`
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
                    $("#searchBookByTermsInput").val(decodedText).trigger('submit') //set search to detected text
                    barcodeScannerModal.hide(); //close modal
                    searchBookOnApi(decodedText) //search scanned isbn 
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
//search book on form submit
$("#searchBookForm").on('submit', function(e){
    e.preventDefault();
    searchBookOnApi($("#searchBookByTermsInput").val());
})

//fill form when a book is slected in search results
$('body').on('click', '.card-book', function(e){
    var $input = $(this).find('input')
    $input.prop('checked', true)
    $('.card-book').removeClass('active');
    $(this).addClass('active');
    // closeAccordionResult()
    //set form inputs value from selected item in search results
    $('input[name="item_book_form[property_1]"]').val($input.data('title'));
    $('input[name="item_book_form[property_2]"]').val($input.data('author'));
    $('input[name="item_book_form[property_3]"]').val($input.data('img-link'));
    $('input[name="item_book_form[property_4]"]').val($input.data('ref-url'));
    $('#item_book_form_submitFromSearchBookResult').prop('disabled', false)
})

//reset masked properties if any manual property changeschange  any of manual property editable
//item_book_form_property_1

function openAccordionResult(){
    $('#accordionHeaderBtn').removeClass('collapsed').attr('aria-expanded', true);
    $('#accordionBody').addClass('show')
}
function closeAccordionResult(){
    $('#accordionHeaderBtn').addClass('collapsed').attr('aria-expanded', false)
    $('#accordionBody').removeClass('show')
}