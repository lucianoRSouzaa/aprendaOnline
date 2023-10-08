// div dropdown módulos e aulas
function toggleDivs(element) {
    element.classList.toggle('expandido');
    var divFilhas = element.nextElementSibling;
    divFilhas.classList.toggle('mostrar');
}