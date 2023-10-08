let cont = 0

function adicionarCampo() {
    cont++

    if (cont == 1) {
        $('#select-category2').removeClass('disabled')
        $('select[name="select-category2"]').attr('required', true);
    }
    if (cont == 2) {
        $('#select-category3').removeClass('disabled')
        $('select[name="select-category3"]').attr('required', true);
        
        $('.txt').addClass('disabled')
        $('.img-add').addClass('disabled')
    }
}