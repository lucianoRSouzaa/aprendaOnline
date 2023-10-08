// mostrar módulos e aulas excluídos
function toggleModulesLessonsDeleted(moduleNumber) {
    const moduleElement = document.querySelector('#modulo-' + moduleNumber);
    const iconElement = document.querySelector('#icon-module-' + moduleNumber);

    if (moduleElement && iconElement) {
        moduleElement.classList.toggle('show');
        iconElement.classList.toggle('rotate-180');
    }
}

function toggleLessonsDeleted(moduleNumber) {
    const moduleElement = document.querySelector('#aula-' + moduleNumber);
    const iconElement = document.querySelector('#icon-aula-' + moduleNumber);

    if (moduleElement && iconElement) {
        moduleElement.classList.toggle('show');
        iconElement.classList.toggle('rotate-180');
    }
}

// confirmação de exclusões permanentes
// aula
$('.delete-lesson-icon').click(function() {
    var lessonId = $(this).data('lesson-id');
    var lessonTitle = $(this).data('lesson-title');

    $('#text-confirmation').text('Se você excluir a aula: "' + lessonTitle + '" permanentemente você nunca mais terá acesso a ela, será como se ela nunca tivesse existido, além disso o vídeo dela também será excluído!');
    

    var form = $('#ModalConfirmacaoExclusao .modal-content').find('form.delete-form');

    var action = form.attr('action');
    action = action.replace(':id', lessonId);
    action = action.replace(':type', "aula");
    form.attr('action', action);
});

// módulo
$('.delete-module-icon').click(function() {
    var moduleId = $(this).data('module-id');
    var moduleTitle = $(this).data('module-title');

    $('#text-confirmation').text('Se você excluir o módulo: "' + moduleTitle + '" permanentemente você nunca mais terá acesso a ele, será como se ele nunca tivesse existido, além disso todas as aulas desses módulo também serão excluído!');
    

    var form = $('#ModalConfirmacaoExclusao .modal-content').find('form.delete-form');

    var action = form.attr('action');
    action = action.replace(':id', moduleId);
    action = action.replace(':type', "modulo");
    form.attr('action', action);
});

// curso
$('.delete-course-icon').click(function() {
    var courseId = $(this).data('course-id');
    var courseTitle = $(this).data('course-title');

    $('#text-confirmation').text('Se você excluir o curso: "' + courseTitle + '" permanentemente você nunca mais terá acesso a ele, será como se ele nunca tivesse existido, além disso todos os módulos e todas as aulas desses módulo também serão excluído!');
    

    var form = $('#ModalConfirmacaoExclusao .modal-content').find('form.delete-form');

    var action = form.attr('action');
    action = action.replace(':id', courseId);
    action = action.replace(':type', "curso");
    form.attr('action', action);
});

// todos registros
$('.delete-all-icon').click(function() {
    var id = $(this).data('id');
    var title = $(this).data('title');
    var type = $(this).data('type');


    if (type == "curso") {
        $('#text-confirmation').text('Se você excluir o curso: "' + title + '" permanentemente você nunca mais terá acesso a ele, será como se ele nunca tivesse existido, além disso todos os módulos e todas as aulas desses módulo também serão excluído!');
    } else if(type == "modulo"){
        $('#text-confirmation').text('Se você excluir o módulo: "' + title + '" permanentemente você nunca mais terá acesso a ele, será como se ele nunca tivesse existido, além disso todas as aulas desses módulo também serão excluído!');
    } else {
        $('#text-confirmation').text('Se você excluir a aula: "' + title + '" permanentemente você nunca mais terá acesso a ela, será como se ela nunca tivesse existido, além disso o vídeo dela também será excluído!');
    }    

    var form = $('#ModalConfirmacaoExclusao .modal-content').find('form.delete-form');

    var action = form.attr('action');
    action = action.replace(':id', id);
    action = action.replace(':type', type);
    form.attr('action', action);
});

// confirmação de restaurações
// aula
$('.restore-lesson-icon').click(function() {
    var lessonId = $(this).data('lesson-id');
    var lessonTitle = $(this).data('lesson-title');

    $('#text-confirmation-restaurar').text('Você está prestes a restaurar a aula excluída: "' + lessonTitle +'". Esta ação é irreversível e pode ter consequências substanciais se utilizada incorretamente. Por favor, verifique cuidadosamente os dados da aula que está prestes a restaurar. Prossiga com cautela.');
    

    var form = $('#ModalConfirmacaoRestaurar .modal-content').find('form.restore-form');

    var action = form.attr('action');
    action = action.replace(':id', lessonId);
    action = action.replace(':type', "aula");
    form.attr('action', action);
});
// módulo
$('.restore-module-icon').click(function() {
    var moduleId = $(this).data('module-id');
    var moduleTitle = $(this).data('module-title');

    $('#text-confirmation-restaurar').text('Você está prestes a restaurar o módulo excluído: "' + moduleTitle +'". Esta ação é irreversível e pode ter consequências substanciais se utilizada incorretamente. Por favor, verifique cuidadosamente os dados do módulo que está prestes a restaurar. Prossiga com cautela.');
    

    var form = $('#ModalConfirmacaoRestaurar .modal-content').find('form.restore-form');

    var action = form.attr('action');
    action = action.replace(':id', moduleId);
    action = action.replace(':type', "modulo");
    form.attr('action', action);
});
// curso
$('.restore-course-icon').click(function() {
    var courseId = $(this).data('course-id');
    var courseTitle = $(this).data('course-title');

    $('#text-confirmation-restaurar').text('Você está prestes a restaurar o curso excluído: "' + courseTitle +'". Esta ação é irreversível e pode ter consequências substanciais se utilizada incorretamente. Por favor, verifique cuidadosamente os dados do curso que está prestes a restaurar. Prossiga com cautela.');
    

    var form = $('#ModalConfirmacaoRestaurar .modal-content').find('form.restore-form');

    var action = form.attr('action');
    action = action.replace(':id', courseId);
    action = action.replace(':type', "curso");
    form.attr('action', action);
});

// todos registros
$('.restore-all-icon').click(function() {
    var id = $(this).data('id');
    var title = $(this).data('title');
    var type = $(this).data('type');

    if (type == "curso") {
        $('#text-confirmation-restaurar').text('Você está prestes a restaurar o curso excluído: "' + title +'". Esta ação é irreversível e pode ter consequências substanciais se utilizada incorretamente. Por favor, verifique cuidadosamente os dados do curso que está prestes a restaurar. Prossiga com cautela.');
    } else if(type == "modulo"){
        $('#text-confirmation-restaurar').text('Você está prestes a restaurar o módulo excluído: "' + title +'". Esta ação é irreversível e pode ter consequências substanciais se utilizada incorretamente. Por favor, verifique cuidadosamente os dados do módulo que está prestes a restaurar. Prossiga com cautela.');
    } else {
        $('#text-confirmation-restaurar').text('Você está prestes a restaurar a aula excluída: "' + title +'". Esta ação é irreversível e pode ter consequências substanciais se utilizada incorretamente. Por favor, verifique cuidadosamente os dados da aula que está prestes a restaurar. Prossiga com cautela.');
    }    

    var form = $('#ModalConfirmacaoRestaurar .modal-content').find('form.restore-form');

    var action = form.attr('action');
    action = action.replace(':id', id);
    action = action.replace(':type', type);
    form.attr('action', action);
});