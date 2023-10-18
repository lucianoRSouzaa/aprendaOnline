// menu responsivo
const toggleBtn = document.querySelector('.toggle_btn')
const toggleBtnIcon = document.querySelector('.toggle_btn i')
const dropDownMenu = document.querySelector('.dropdown_menu')

toggleBtn.onclick = function () {
    dropDownMenu.classList.toggle('open')
    const isOpen = dropDownMenu.classList.contains('open')

    toggleBtnIcon.classList = isOpen ? 'fa fa-times' : 'fa fa-bars'
}

// navbar sticky
window.addEventListener("scroll", function(){
    var nav = document.querySelector("nav");
    nav.classList.toggle("sticky", window.scrollY > 0)
})

// página viewer index
$('#config').click(function() {
    $('.notification-div').toggleClass('show');
});

const navBar = document.getElementById("sidebar"),
    menuBtn = document.getElementById("notifications"),
    fecharBtn = document.getElementById("fechar-notifications");

menuBtn.addEventListener("click", mostrar);

function mostrar() {
    navBar.classList.add("open");

    fetch('/marking-notifications-as-read')
        .then(response => response.json())
        .then(data => console.log(data));
}

fecharBtn.addEventListener("click", fechar);

function fechar() {
    navBar.classList.remove("open");
}