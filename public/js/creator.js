$(document).ready(function() {
    $('.delete-icon').click(function() {
        var modal = $(this).closest('.card').next('.modal');
        abrirModal(modal);
    });

    function abrirModal(modal) {
        $(modal).modal('show');
    }
});
