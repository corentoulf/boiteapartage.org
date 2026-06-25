$('.cta-circle-type').on('click', function(e){
    e.preventDefault();
    $('.cta-circle-type').removeClass('btn-dark').addClass('btn-outline-dark')
    $(this).removeClass('btn-outline-dark').addClass('btn-dark')
    let circleType = $(this).data('circle-type');
    let ctaText = "Créer une boîte pour "
    switch (circleType) {
        case 'residence':
            ctaText += 'ma résidence';
            break;
        case 'entreprise':
            ctaText += 'mon entreprise';
            break;
        case 'association':
            ctaText += 'mon association';
            break;
        case 'quartier':
            ctaText += 'mon quartier';
            break;
        default:
            break;
    }
    $('#cta-create-circle-target').html(ctaText+ '<i class="bi bi-arrow-right ms-2"></i>');
});
