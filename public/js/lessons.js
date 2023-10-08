// mostrar aulas
function toggleLessons(moduleNumber) {
    const moduleElement = document.querySelector('#aula-' + moduleNumber);
    moduleElement.classList.toggle('active');

    const iconElement = document.querySelector('#icon-module-' + moduleNumber);
    iconElement.classList.toggle('rotate-180');
}

// checkbox para marcar aula como concluída
const checkboxes = document.querySelectorAll('.ckx');
checkboxes.forEach(checkbox => {
    checkbox.addEventListener('click', function() {
        const form = this.closest('form');
        form.submit();
    });
});

// acabar o vídeo e marcar aula como concluída
const videoPlayer = document.querySelector('video');
videoPlayer.addEventListener('ended', function() {
    if ($('#mark-lesson-input').is(":checked")){
        location.reload();
    } else{
        $('#mark-lesson-input').prop('checked', true);
        $('#mark-lesson-form').submit();
    }
});

// ver progresso
$('#progress').click(function() {
    $('.notification-div').toggleClass('show');
});

// modal de avaliação
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

// modal de denuncia
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