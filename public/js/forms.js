$(document).ready(function() {
    // funcionalidade de páginas do form
    const progressText = $(".step p"); //texto
    const progressCheck = $(".step .check"); //icone de feito
    const bullet = $(".step .bullet"); //bolinha
    let current = 0; //página atual

    const pages = $(".page"); //todas as páginas
    const prevButtons = $(".prev"); //botão de previous
    const nextButtons = $(".next"); //botão de next

    // função para exibir a página de determinado index e hide nas outras
    function showPage(pageIndex) {
        pages.hide(500);
        pages.eq(pageIndex).show(500);
    }

    // função para atualizar o progresso
    function updateProgress(pageIndex) {
        bullet.each(function(i) {
            if (i < pageIndex) {
                $(this).addClass("active");
                progressCheck.eq(i).addClass("active");
                progressText.eq(i).addClass("active");
            } else {
                $(this).removeClass("active");
                progressCheck.eq(i).removeClass("active");
                progressText.eq(i).removeClass("active");
            }
        });
    }

    // função chamada ao clicar no botão next ou prev
    function navigate(event, direction) {
        event.preventDefault();
        const currentPage = current;
        const newPage = currentPage + direction;

        showPage(newPage);
        updateProgress(newPage);
        current = newPage;
    }

    nextButtons.click(function(event) {
        navigate(event, 1);
    });

    prevButtons.click(function(event) {
        navigate(event, -1);
    });

    // exibindo a página 1 que já é exibida automaticamente
    showPage(0);
    updateProgress(0);

  // Input da imagem drag and drop
  const fileInput = $('#file-upload')[0];
  const box = $('.border-dashed');
  const fileNameElement = $('#file-name');

  // event listeners para drag and drop events na box
  box.on('dragenter', handleDragEnter);
  box.on('dragover', handleDragOver);
  box.on('dragleave', handleDragLeave);
  box.on('drop', handleDrop);

  // Adicionado event listener para mudança de arquivo
  $(fileInput).on('change', handleFileSelect);

  function handleDragEnter(event) {
      event.preventDefault();
      event.stopPropagation();
      box.addClass('dragover');
  }

  function handleDragOver(event) {
      event.preventDefault();
      event.stopPropagation();
  }

  function handleDragLeave(event) {
      event.preventDefault();
      event.stopPropagation();
      box.removeClass('dragover');
  }

  function handleDrop(event) {
      event.preventDefault();
      event.stopPropagation();
      box.removeClass('dragover');
      fileInput.files = event.originalEvent.dataTransfer.files;
      handleFileSelect();
  }

  // Manipulando a alteração de seleção de arquivo
  function handleFileSelect() {
      if (fileInput.files.length > 0) {
          const fileName = fileInput.files[0].name;
          fileNameElement.text(fileName);
          fileNameElement.removeClass('hidden');
      } else {
          fileNameElement.text('');
          fileNameElement.addClass('hidden');
      }
  }
});
