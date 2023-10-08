// div dropdown módulos e aulas
function toggleDivs(element) {
    element.classList.toggle('expandido');
    var divFilhas = element.nextElementSibling;
    divFilhas.classList.toggle('mostrar');
}

// dropdown compartilhar e denunciar curso
function dropdownMore(){
    document.getElementById("dropdown-options").classList.toggle("show");
}

// options do modal de denuncia
function verificarSelecao() {
    var select = document.getElementById("denuncia-option"); 
    var denunciaAulaOption = document.getElementById("denuncia-aula-option"); 
    var selectAula = document.getElementById("select-aula-option"); 

    var valor = select.options[select.selectedIndex].text

    if (valor == "Aula" || valor == "Lesson" || valor == "Clase") {
        denunciaAulaOption.classList.toggle('mostrar');
    }
    else{
        denunciaAulaOption.classList.remove('mostrar');
        selectAula.selectedIndex = 0;
    }
}

$('#star').raty({ 
    path: '/images/star',
    hints: ['péssimo', 'ruim', 'regular', 'bom', 'ótimo'],
});

var campoAdicionado = false;

function adicionarCampo() {
    if (!campoAdicionado) {
        var label = document.getElementById("text-comment");
        var camposInput = document.getElementById("comment");
        var novoCampo = document.createElement("textarea");
        
        novoCampo.classList.add("comment-area");
        novoCampo.setAttribute("rows", "4");
        novoCampo.setAttribute("name", "comment");
        camposInput.appendChild(novoCampo);
        label.innerHTML = "Digite seu comentário:";
        label.classList.remove("text-primary");
        label.classList.add("comment-start");

        campoAdicionado = true;
    }
}

// sistema de pesquisa
$('.avaliacao-bar').each(function() {
    $(this).click(function() {
        var score = $(this).attr('data-score');
        $('#starFilter').val(score);
        $('#formStarFilter').submit();
    });
});


