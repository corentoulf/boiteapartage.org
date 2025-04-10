//copy content to clipboard
async function copyToClipboard(text) {
    await window.navigator.clipboard.writeText(text)
    // addFlash('success', 'Lien copié avec succès');
}

//create flash message from JS file
function addFlash(label, message){
    $('#flashMessageContainer').html(
        `
            <div class="col mt-3 me-2 alert d-flex justify-content-between alert-${label} ${label == 'success' ? '' : 'alert-dismissable fade show'}" role="alert" style="max-width:30%">
                <p class="m-0 me-2">${message}</p>
                ${label == 'success' ? '' : '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'}
            </div>
        `
    )
    setTimeout(() => {
        $('.alert-success:not(.alert-static)').fadeOut(500, function(){
            this.remove()
        })
    }, "5000");
}
export { copyToClipboard, addFlash };