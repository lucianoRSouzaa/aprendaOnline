## aprendaOnline
A plataforma "aprendaOnline" é um projeto educacional inovador que visa criar uma plataforma de aprendizado colaborativo, onde estudantes e criadores de conteúdo podem se conectar. A proposta principal é oferecer um espaço onde os alunos podem compartilhar seus conhecimentos e experiências de forma prática e eficiente.

### Tecnologias Utilizadas
- Laravel: Framework PHP.
- MySQL: Banco de dados relacional para armazenamento de dados.
- Pusher: WebSocket que facilita a implementação de chat em tempo real.
- Google Drive: Serviço para armazenamento de vídeos, proporcionando uma solução escalável


## Sobre o site
Resumo breve sobre as páginas e funcionalidades do site

### Páginas para Criadores de Conteúdo
Usuários que assumem o papel de criadores de conteúdo desfrutam de uma plataforma intuitiva e amigável. As páginas dedicadas a esses usuários oferecem ferramentas robustas para criar cursos interativos e envolventes. Aqui, eles podem estruturar o conteúdo, incorporar vídeos educacionais, ver dados referentes ao desempenho do curso, além de facilitar discussões em tempo real por meio do chat integrado.

### Páginas para Visualizadores de Conteúdo:
Os usuários que estão na busca por conhecimento encontram uma experiência dinâmica e diversificada. As páginas de visualizadores de conteúdo proporcionam acesso a uma ampla variedade de cursos, cada um projetado para oferecer uma jornada educacional única. Recursos como o tema claro/escuro e chat em tempo real aprimoram a experiência do usuário durante a exploração.

### Painel Administrativo:
A parte administrativa do site é projetada para eficiência e controle. Os administradores têm acesso a métricas vitais, relatórios de denúncias e ferramentas de moderação. Esta área proporciona uma visão abrangente do ecossistema educacional dentro da plataforma, garantindo uma administração eficaz.

---------

## Diário de bordo

#### Atualizações 26/05/2023
- Criação da landing page
- Adicionado pop-up para que o usuário possa fazer login ou cadastro na plataforma

#### Atualizações 28/05/2023
- Desenvolvimento da tela de cadastro de curso
- Desenvolvimento da tela de cadastro de módulo
- Desenvolvimento da tela de cadastro de aula

#### Atualizações 29/05/2023
- Feito o desenvolvimento da funcionalidade de cadastro na plataforma
- Feito o desenvolvimento da funcionalidade de login na plataforma

#### Atualizações 30/05/2023
- Desenvolvimento da tela de listagem de cursos
- Feito o design dos cards dos cursos

#### Atualizações 02/06/2023
- Criação da página de detalhes do curso

#### Atualizações 05/06/2023
- Desenvolvida a funcionalidade para criação de dados de cursos no banco de dados
- Adicionado ícones de edição e exclusão nos cards de cursos pertencentes ao usuário criador do curso
- Desenvolvida a funcionalidade para edição de dados de cursos no banco de dados
- Desenvolvida a funcionalidade para deleção de cursos do banco de dados

#### Atualizações 17/06/2023
- Feita a funcionalidade para criação de módulos do curso no banco de dados

#### Atualizações 19/06/2023
- Desenvolvimento da tela de exibição de módulos e aulas do curso por parte do criador do curso
- Implementação da sessão que exibe os módulos e as aulas do curso na página de detalhes de curso

#### Atualizações 26/06/2023
- Adição de ícones de edição e exclusão na página de exibição de módulos e aulas do curso por parte do criador
- Desenvolvimento da funcionalidade de edição de dados de módulos de um curso
- Implementação do pop-up de confirmação de exclusão de módulo
- Desenvolvimento da funcionalidade de exclusão de módulos de um curso

#### Atualizações 27/06/2023
- Adição do botão de criar aula para um módulo
- Criado o sistema de criação de aulas
- Foi implementado a exibição das aulas na tela de exibição de módulos e aulas do curso por parte do criador do curso
- Implementação das aulas dos módulos na sessão que exibe os módulos e as aulas do curso na página de detalhes de curso
- Realizado sistema de edição de aula

#### Atualizações 28/06/2023
- Implementação do pop-up de confirmação de exclusão de aula
- Desenvolvimento da funcionalidade de exclusão de aulas

#### Atualizações 29/06/2023
- Implementação do Soft Deletes em algumas tabelas do banco de dados para preservação de dados excluídos

#### Atualizações 07/07/2023
- Adição do botão "Inscrever-se" na página de detalhes de cursos
- Implementado sistema de inscrição em cursos
- Adição da sessão "Cursos que estou inscrito" na página inicial

#### Atualizações 14/07/2023
- Implementado ícone em forma de coração no card de cada curso
- Desenvolvido sistema de favoritação de cursos
- Adição da sessão "Meus cursos favoritos" na página inicial

#### Atualizações 04/08/2023
- Integração com o Google Drive da plataforma
- Armazenamento do vídeo das aulas no Google Drive da aplicação

#### Atualizações 11/08/2023
- Adicionado dropdown com botões de compartilhar e denunciar na página de detalhes de curso
- Introduzido modal de denuncia
- Desenvolvido sistema de denúncia

#### Atualizações 17/08/2023
- Criação do dashboard administrativo
- Desenvolvido tabela com todas as denúncias pendentes
- Implementação de busca personalizada

#### Atualizações 18/08/2023
- Desenvolvida página de visualização de uma denúncia específica
- Desenvolvida página de visualização de todas as denúncias pendentes em forma de slide

#### Atualizações 20/08/2023
- Adicionado botão de classificar curso na página de detalhes de cursos
- Implementado pop-up de classificação de curso
- Desenvolvida funcionalidade de classificação do curso

#### Atualizações 22/08/2023
- Implementada sessão para mostrar as avaliações do curso na página de detalhes de cursos

#### Atualizações 25/08/2023 
- Criação da página de visualização de aula
- Implementação do sistema de conclusão de aula
- Adicionado campo que armazena a média das avaliações de um curso na tabela Courses do Banco de Dados
- Implementado sistema de cancelamento de inscrição de cursos

#### Atualizações 26/08/2023 
- Desenvolvido sistema de conclusão de cursos
- Implementado funcionalidade automática de conclusão de aulas quando o vídeo assistido é terminado

#### Atualizações 27/08/2023
- Adicionada funcionalidade de cancelamento de conclusão de aula

#### Atualizações 06/09/2023
- Adicionado caixa de seleção "Desejo ser criador de conteúdo" no pop-up de cadastro
- Desenvolvimento do termo de responsabilidade
- Cadastro de usuários criadores de conteúdo

#### Atualizações 07/09/2023
- Desenvolvida tabela com todas as denúncias pedentes, inclusive as que tiveram seu conteúdo excluído, na página do administrador

#### Atualizações 08/09/2023
- Implementada funcionalidade para o administrador visualizar cursos, módulos e aulas excluídas

#### Atualizações 09/09/2023
- Implementada para o administrador a funcionalidade restaurar cursos, módulos e aulas excluídas
- Implementada para o administrador a funcionalidade excluir permanentemente cursos, módulos e aulas excluídas

#### Atualizações 12/09/2023
- Implementado barra de pesquisa na tabelas de registros excluídos

#### Atualizações 14/09/2023
- Desenvolvida, na página do administrador, uma tabela que exibe todos os cursos disponíveis na plataforma
- Implementada barra de pesquisa para o administrador buscar pelo nome do curso
- Implementado paginação

#### Atualizações 15/09/2023
- Desenvolvida, na página do administrador, uma tabela que exibe todos os usuários da plataforma
- Implementada barra de pesquisa para o administrador buscar pelo nome do usuário
- Implementado paginação

#### Atualizações 17/09/2023
- Desenvolvida, na página do administrador, uma tabela que exibe todas as categorias já criadas da plataforma
- Criado pop-up para criação de uma nova categoria
- Adicionada funcionalidade de criar nova categoria

#### Atualizações 18/09/2023
- Desenvolvida página para exibir dados das categorias por meio de gráficos
- Desenvolvida página para exibição de uma categoria individual

#### Atualizações 20/09/2023
- Adicionado dropdown na página incial da plataforma
- Implementada funcionalidade de alternar entre os modos de criador de conteúdo e visualizador

#### Atualizações 21/09/2023
- Desenvolvida a página de perfil do usuário
- Adicionada as sessões na página de perfil do usuário: cursos inscritos, concluídos, favoritados, criados, denuncias feitas e avaliações feitas pelo usuário

#### Atualizações 22/09/2023
- Adição do campo image na tabela Users do banco de dados para armazenar da foto do usuário
- Desenvolvida página de edição de dados do usuário

#### Atualizações 26/09/2023
- Implementada a página de configurações da plataforma

#### Atualizações 27/09/2023
- Implementada a funcionalidade de alterar o idioma da plataforma

#### Atualizações 29/09/2023
- Desenvolvida página index de visualização de dados do curso por parte do criador do curso

#### Atualizações 02/10/2023
- Desenvolvida a página em que o criador do curso pode ver as avaliações do seu curso
- Adiconada funcionalidade de filtrar avaliações pela quantidade de estrelas desejada

#### Atualizações 03/10/2023
- Desenvolvida página em que o criador do curso pode ver o progresso dos usuários inscritos no curso

#### Atualizações 05/10/2023
- Desenvolvida página em que o criador do curso pode ver o desempenho do seu curso ao longo do tempo

#### Atualizações 06/10/2023
- Desenvolvimento da página de configurações do curso

#### Atualizações 07/10/2023
- Implementação da verificação de E-mail para utilização da plataforma
- Criado E-mail de verificação
- Criado pop-up de confirmação de envio de E-mail para o usuário que acabou de se cadastrar
- Criado pop-up de confirmação de verificação para o usuário que acabou de confirmar a verificação no E-mail
- Criado pop-up de erro para o usuário que não verificou sua conta no E-mail e tentou acessar uma página em que é necessária a verificação do E-mail

#### Atualizações 08/10/2023
- Implementada funcionalidade de "Esqueci minha senha"
- Criado pop-up para inserir email do usuário para o envio do E-mail de redefinição de senha
- Criado E-mail de redefinição de senha
- Criado pop-up para redefinição de senha

#### Atualizações 09/10/2023
- Adição da funcionalidade de alterar a ordem das aulas de um módulo via drag and drop

#### Atualizações 10/10/2023
- Criação da página de busca de curso avançada
- Implementado pop-up de compartilhamento de curso na página de detalhes de curso

#### Atualizações 10/10/2023
- introduzidos os campos: "contact_email" e "what_students_learn" na tabela de Courses
- Melhoria na página de cadastro de cursos e edição de cursos
- Adição da sessão "O que você irá aprender" na página de detalhes de cursos

#### Atualizações 13/10/2023
- Desenvolvido middleware para bloquear acesso de usuários a páginas que são reservada exclusivamente para criadores de conteúdo
- Desenvolvido middleware para bloquear acesso de usuários criadores em cursos de outros usuários criadores de conteúdo

#### Atualizações 14/10/2023
- Adicão da descrição da aula na página de assistir aulas
- Solicitação da senha do usuário para confirmar a intenção de exclusão de um registro

#### Atualizações 16/10/2023
- Aprimoramente com gráficos no dashboard do administrador

#### Atualizações 18/10/2023
- Desenvolvimento do sistema de deferimento de denúncias por parte do administrador
- Criação da tabela Notification no Banco de Dados
- Implementação de notificações referente ao deferimento da denúncia para o usuário autor do conteúdo denunciado e para o usuário que realizou a denúncia

#### Atualizações 19/10/2023
- Implementado notificações para informar ao administrador sobre novas denúncias feitas pelos usuários

#### Atualizações 20/10/2023
- Aprimoramento de controle de conteúdo na página de denúncias
- Criação do botão "Excluir curso denunciado"
- Criação do botão "Excluir aula denunciada"

#### Atualizações 21/10/2023
- Implementado sistema de penalidades em caso de denúncia aceita
- Criado pop-up para avisar a suspensão da atividade da conta de um usuário

#### Atualizações 23/10/2023
- Adicionado o campo "views" à tabela Courses para registrar a quantidade de visualizações de cada curso
- Criação de um slider infinito na página incial que mostra os cursos mais populares da plataforma

#### Atualizações 27/10/2023
- Adicionada sessão "principais ferramentas" na landing page

#### Atualizações 03/11/2023
- Instalação de dependências para iniciar desenvolvimento do chat em tempo real

#### Atualizações 04/11/2023
- Criação do componente Index do chat

#### Atualizações 05/11/2023
-Criação do Componente de Lista de Chat

#### Atualizações 06/11/2023
- Implementação das salas de conversa específicas
- Criado o componente "Chat box"

#### Atualizações 07/11/2023
- Criação da tabela Conversations
- Criação da tabela Messages

#### Atualizações 08/11/2023
- Criado pop-up para pesquisar por usuários e iniciar uma nova conversa
- Adicionada função de abrir pop-up no botão adicionar do componente "Chat list"
- Desenvolida funcionalidade de iniciar uma nova conversa

#### Atualizações 09/11/2023
- Desenvolvida funcionalidade de escolher uma conversa
- Criada a funcionalidade que permite aos usuários enviar mensagens dentro das conversas

#### Atualizações 11/11/2023
- Configuração do Pusher para ser utilizado no projeto
- Desenvolvida funcionalidade que torna as mensagens em tempo real
- Desenvolvida funcionalidade que torna a visualização da mensagem em tempo real

#### Atualizações 13/11/2023
- Melhorada a experiência do usuário com o chat

#### Atualizações 14/11/2023
- Adicionada a funcionalidade "Chat com o autor do conteúdo denunciado"

#### Atualizações 16/11/2023
- Criação da página de visualização de cursos favoritados pelo usuário

#### Atualizações 18/11/2023
- Criado link para acessar o chat
- Desenvolvido middleware para verificar se o usuário está inscrito no curso que ele está tentando acessar

#### Atualizações 20/11/2023
- Implementado a escolha de tema: claro e escuro

#### Atualizações 21/11/2023
- Feito o tema escuro das páginas dos usuários
- Melhorias estéticas nas páginas de cursos favoritos e cursos concluídos

#### Atualizações 22/11/2023
- Adicionada a sessão "Desenvolvedores" na landing page

#### Atualizações 23/11/2023
- Desenvolvida a funcionalidade: suporte
- Adicionado a funcionalidade "Arquivos da aula"
- Adicionado o botão baixar arquivos da aula na página de assistir aulas
- Criado tooltip para ver perfil na página de detalhes de curso