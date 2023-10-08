// modais landing page (cadastro e login)
document.getElementById('abrir-modal-login').addEventListener('click', function(event) {
    event.preventDefault();
    $('#ModalLogin').modal('show');
    $('#Modal1').modal('hide');
});

document.getElementById('abrir-modal-cad').addEventListener('click', function(event) {
    event.preventDefault();
    $('#ModalCad').modal('show');
    $('#Modal1').modal('hide');
});

document.getElementById('voltarModalButton').addEventListener('click', function(event) {
    event.preventDefault();
    $('#ModalLogin').modal('hide');
    $('#Modal1').modal('show');
});

document.getElementById('voltarModalButton2').addEventListener('click', function(event) {
    event.preventDefault();
    $('#ModalCad').modal('hide');
    $('#Modal1').modal('show');
});

// modais referentes ao termo de criador de conteúdo
document.getElementById('concordar-termo').addEventListener('click', function(event) {
    event.preventDefault();
    $('#ModalTermoCriador').modal('hide');
    $('#termo-criador-input').prop('checked', true);
});

document.getElementById('recusar-termo').addEventListener('click', function(event) {
    event.preventDefault();
    $('#ModalTermoCriador').modal('hide');
    $('#termo-criador-input').prop('checked', false);
});

document.getElementById('abrir-modal-termo-criador').addEventListener('click', function(event) {
    event.preventDefault();
    $('#ModalTermoCriador').modal('show');
});

// modal de erro de autenticação
document.getElementById('authButton').addEventListener('click', function(event) {
    event.preventDefault();
    $('#ModalErro').modal('hide');
    $('#Modal1').modal('show');
});

// limpar inputs dos form de cadastro e login ao clicar nos botões
function limparForm(element) {
    var form = document.getElementById(element);
    form.reset();
}