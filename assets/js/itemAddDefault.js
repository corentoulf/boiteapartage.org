//Add photo
$('#openFileInputBtn').on('click', function(e){
    e.preventDefault();
    $('#item_default_form_imageFile').trigger('click');
});
$('#item_default_form_imageFile').on('change', function(e){
    $('#imgBookCover').attr('src', URL.createObjectURL(this.files[0]))
    $('#imgBookCover').show();
    $('#openFileInputBtn').hide();
    $('#retakePhotoBtn').show();
})

$('#retakePhotoBtn').on('click', function(e){
    e.preventDefault();
    // $('#imgBookCover').attr('src', '')
    // $('#imgBookCover').hide();
    // $('#openFileInputBtn').show();
    // $('#retakePhotoBtn').hide();
    $('#item_default_form_imageFile').val(null).trigger('click');
});