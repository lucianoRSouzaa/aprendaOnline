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