$(document).ready(function() {
    // PÁGINA DE SHOW
    var $activeNav = $('#inscrito');
    var $activeSection = $('#cursos-inscritos');
    var role = $('#role');

    function setActiveNavItem($item) {
        $activeNav.removeClass('active');
        $item.addClass('active');
        $activeNav = $item;
    }

    function setActiveSection($section) {
        $activeSection.removeClass('show');
        $section.addClass('show');
        $activeSection = $section;
    }

    $('#inscrito').click(function() {
        setActiveNavItem($(this));
        setActiveSection($('#cursos-inscritos'));
        role.val('inscrito');
    });

    $('#concluido').click(function() {
        setActiveNavItem($(this));
        setActiveSection($('#cursos-concluidos'));
        role.val('concluido');
    });

    $('#favoritado').click(function() {
        setActiveNavItem($(this));
        setActiveSection($('#cursos-favoritos'));
        role.val('favoritado');
    });

    $('#denuncias').click(function() {
        setActiveNavItem($(this));
        setActiveSection($('#denuncias-feitas'));
        role.val('denuncias');
    });

    $('#avaliacoes').click(function() {
        setActiveNavItem($(this));
        setActiveSection($('#avaliacoes-feitas'));
        role.val('avaliacoes');
    });


    // PÁGINA DE EDIT
    var uploadfoto = document.getElementById('uploadfoto');
    var fotopreview = document.getElementById('fotopreview');

    if (uploadfoto) {
        uploadfoto.addEventListener('change', function(e) {
            showThumbnail(this.files);
        });
    
        function showThumbnail(files) {
            if (files && files[0]) {
            var reader = new FileReader();
    
            reader.onload = function (e) {
                fotopreview.src = e.target.result;
            }
    
                reader.readAsDataURL(files[0]);
            }
        }
    }

    document.getElementById('role').addEventListener('click', function(event) {
        event.preventDefault();
        $('#ModalTermoCriador').modal('show');
    });

    document.getElementById('concordar-termo').addEventListener('click', function(event) {
        event.preventDefault();
        $('#ModalTermoCriador').modal('hide');
        $('#role').prop('checked', true);
    });
    
    document.getElementById('recusar-termo').addEventListener('click', function(event) {
        event.preventDefault();
        $('#ModalTermoCriador').modal('hide');
        $('#role').prop('checked', false);
    });
});
