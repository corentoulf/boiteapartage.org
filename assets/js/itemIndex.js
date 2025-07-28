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
});

// $('ul.list').on('change', function(e){
//     console.log(this)
// })