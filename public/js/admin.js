
// botões de gráfico interativo no dashboard
$('.filter-btns').click(function() {

  var periodValue = '';
  switch($(this).attr('id')) {
      case 'hours24':
          periodValue = 'hours_24';
          break;
      case 'days14':
          periodValue = 'days_14';
          break;
      case 'months3':
          periodValue = 'months_3';
          break;
      case 'months12':
          periodValue = 'months_11';
          break;
  }

  $('#period').val(periodValue);
  $('#form1').submit();
});

$('.filter-btns2').click(function() {

  var periodValue = '';
  switch($(this).attr('id')) {
      case 'days_5':
          periodValue = 'days_5';
          break;
      case 'days_14':
          periodValue = 'days_14';
          break;
      case 'months_3':
          periodValue = 'months_3';
          break;
      case 'months_12':
          periodValue = 'months_11';
          break;
  }

  $('#period2').val(periodValue);
  $('#form2').submit();
});

$('#config').click(function() {
  $('.notification-div').toggleClass('show');
});

// sidebar notifications
const navBar = document.getElementById("sidebar-notify"),
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

// SIDEBAR TOGGLE do dashboard
var sidebarOpen = false;
var sidebar = document.getElementById("sidebar");

function openSidebar() {
  if(!sidebarOpen) {
    sidebar.classList.add("sidebar-responsive");
    sidebarOpen = true;
  }
}

function closeSidebar() {
  if(sidebarOpen) {
    sidebar.classList.remove("sidebar-responsive");
    sidebarOpen = false;
  }
}

// slides de denuncia
const slides = document.querySelectorAll('.slide');
let currentSlide = 0;

function showSlide(slideIndex) {
  slides.forEach(slide => slide.style.display = 'none');
  slides[slideIndex].style.display = 'flex';
  updateCurrentSlide();
}

function nextSlide() {
  currentSlide = (currentSlide + 1) % slides.length;
  showSlide(currentSlide);
}

function prevSlide() {
  currentSlide = (currentSlide - 1 + slides.length) % slides.length;
  showSlide(currentSlide);
}

function updateCurrentSlide() {
  document.querySelector('#currentSlide').textContent = `${currentSlide + 1}/${slides.length}`;
}


document.querySelector('#right').addEventListener('click', nextSlide);
document.querySelector('#left').addEventListener('click', prevSlide);

showSlide(currentSlide);