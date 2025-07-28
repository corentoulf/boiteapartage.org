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

// $('ul.list').on('change', function(e){
//     console.log(this)
// })


// MODALS
const deleteObjectModal = $('#deleteObjectModal')
deleteObjectModal.on('show.bs.modal', function(e){
    // Button that triggered the modal
    console.log(e.relatedTarget)
    const btn = e.relatedTarget;
    // Extract info from data-bs-* attributes
    const itemTitle = $(btn).attr('data-bs-item-title')
    const itemDeleteLink = $(btn).attr('data-bs-item-delete-link')
    console.log(itemTitle, itemDeleteLink)
    
    // Update modal content    
    $('#deleteObjectModal').find('.item-title').text(itemTitle);
    $('#deleteObjectModal').find('.item-delete-link').attr('href', itemDeleteLink);
})