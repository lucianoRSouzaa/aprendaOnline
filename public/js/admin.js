
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
  $('#sidebar-notify').removeClass('open');
});

// sidebar notifications
const navBar = document.getElementById("sidebar-notify"),
    menuBtn = document.getElementById("notifications"),
    fecharBtn = document.getElementById("fechar-notifications");

menuBtn.addEventListener("click", mostrar);

function mostrar() {
  navBar.classList.toggle("open");
  $('.notification-div').removeClass('show');

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