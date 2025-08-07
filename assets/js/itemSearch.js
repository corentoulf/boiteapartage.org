import axios from 'axios';
import routes from './fos_routes.js';
import Router from '@toyokumo/fos-router';
Router.setRoutingData(routes);

/*
    LIST FILTERS
*/
import List from '../vendor/list.js/list.js.index.js';
var options = {
    valueNames: [ 
        { data: ['item-title'] },
        { data: ['item-category'] }
    ]
};

var itemList = new List('items', options);
// detect search input changes
$('#filterItemsInput').on('keyup', function() {
    var searchString = $(this).val();
    var searchCategory = $('#filterItemsCategorySelect').val();
    if(searchCategory !== '' && searchString !== ''){
        $('#filterItemsGlobalInput').val(searchCategory + ' ' + searchString).trigger('change')
    }
    else if(searchCategory == ''){
        $('#filterItemsGlobalInput').val(searchString).trigger('change')
    }
    if(searchString == ''){
        $('#filterItemsGlobalInput').val(searchCategory).trigger('change')
    }
});
// detect category selection changes
$('#filterItemsCategorySelect').on('change', function() {
    var searchCategory = $(this).val();
    var searchString = $('#filterItemsInput').val();
    if(searchCategory !== '' && searchString !== ''){
        $('#filterItemsGlobalInput').val(searchCategory + ' ' + searchString).trigger('change')
    }
    else if(searchCategory == ''){
        $('#filterItemsGlobalInput').val(searchString).trigger('change')
    }
    if(searchString == ''){
        $('#filterItemsGlobalInput').val(searchCategory).trigger('change')
    }
});

// filter on searc inputs change
$('#filterItemsGlobalInput').on('change', function() {
    var searchString = $(this).val();
    console.log('searchoing' + typeof(searchString))
    itemList.search(searchString);
    $('#itemFoundCount').text(itemList.matchingItems.length)
});

/*
    BOOKMARK
*/
$('body').on('click', '.btn-bookmark-item', function(e){
    e.preventDefault();
    let $btn = $(this)
    let itemId = $btn.attr('data-item-id')
    let url = Router.generate('app_item_bookmark', {
        'id':itemId
    })
    axios.post(url)
        .then(function (response) {
            if(response.status == 200){
                $btn
                    .removeClass('link-dark').removeClass('btn-bookmark-item')
                    .addClass('link-highlight').addClass('btn-unbookmark-item')
                $btn.find('i').removeClass('bi-heart').addClass('bi-heart-fill')
            }
        })
        .catch(function (error) {
            // handle error
            // console.log(error);
        })
        .finally(function () {
            // always executed
        });
})
/*
    UNBOOKMARK
*/
$('body').on('click', '.btn-unbookmark-item', function(e){
    e.preventDefault();
    let $btn = $(this)
    let itemId = $btn.attr('data-item-id')
    let url = Router.generate('app_item_unbookmark', {
        'id':itemId
    })
    axios.post(url)
        .then(function (response) {
            if(response.status == 200){
                $btn
                    .removeClass('link-highlight').removeClass('btn-unbookmark-item')
                    .addClass('link-dark').addClass('btn-bookmark-item')
                $btn.find('i').removeClass('bi-heart-fill').addClass('bi-heart')
            }
        })
        .catch(function (error) {
            // handle error
            // console.log(error);
        })
        .finally(function () {
            // always executed
        });
})