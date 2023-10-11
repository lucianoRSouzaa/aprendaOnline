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


// Modal de compartilhar
const link = window.location.href;
console.log(courseTitle)
const msg = encodeURIComponent(`Confira este curso incrível: ${courseTitle}`);
const title = encodeURIComponent(courseTitle);

const fb = document.querySelector('.facebook');
fb.href = `https://www.facebook.com/share.php?u=${link}`;

const twitter = document.querySelector('.twitter');
twitter.href = `http://twitter.com/share?&url=${link}&text=${msg}&hashtags=aprendaOnline,estudos`;

const linkedIn = document.querySelector('.linkedin');
linkedIn.href = `https://www.linkedin.com/sharing/share-offsite/?url=${link}`;

const reddit = document.querySelector('.reddit');
reddit.href = `http://www.reddit.com/submit?url=${link}&title=${title}`;

const whatsapp = document.querySelector('.whatsapp');
whatsapp.href = `https://api.whatsapp.com/send?text=${msg}: ${link}`;

const telegram = document.querySelector('.telegram');
telegram.href = `https://telegram.me/share/url?url=${link}&text=${msg}`;

$('#copy-btn').click(function() {
    navigator.clipboard.writeText(window.location.href).then(function() {
        console.log('Copiado com sucesso!');
    }, function(err) {
        console.error('Erro ao copiar: ', err);
    });
});
