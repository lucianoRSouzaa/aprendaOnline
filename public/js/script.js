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

// página index de cursos
const navBar = document.getElementById("sidebar"),
    menuBtn = document.getElementById("notifications"),
    fecharBtn = document.getElementById("fechar-notifications");

$('#config').click(function() {
    $('.notification-div').toggleClass('show');
    $('#sidebar').removeClass("open");
});

menuBtn.addEventListener("click", mostrar);

function mostrar() {
    navBar.classList.toggle("open");
    $('.notification-div').removeClass("show");

    fetch('/marking-notifications-as-read')
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        console.log('Notification marked as read');
    })
    .catch(error => console.error(`There has been a problem with your fetch operation: ${error.message}`));
}

fecharBtn.addEventListener("click", fechar);

function fechar() {
    navBar.classList.remove("open");
}