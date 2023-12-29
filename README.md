# Componente Router

O **Componente Router** é uma ferramenta simples e flexível para gerenciar rotas em aplicativos PHP. Este componente permite que você defina rotas e associe funções ou métodos de controladores a elas, facilitando a criação de aplicativos da web com URLs amigáveis.

## Recursos

- Fácil definição de rotas
- Suporte a rotas com parâmetros
- Funções ou métodos de controladores associados a rotas

## Instalação

Você pode instalar este componente via Composer. Execute o seguinte comando no terminal:

```bash
composer composer require claud/router
```

## Uso

Aqui está um exemplo de como você pode usar este componente em seu projeto PHP:

```php
<?php

require_once 'vendor/autoload.php';

use Router\Router\Router;

$router = new Router();

$router->addRoute('/', function () {
    echo 'Bem-vindo à página inicial!';
});
//Para que o componente router encontre o controller, passe o caminho completo
$router->addRoute('/perfil/{id}', 'App\Controller\PerfilController@show');

$router->prefix('/users', function(Router $router) {
    $router->addRoute('/edit/{id}', function ($id) {
        return "Rota com prefixo e parâmetro dinâmico {$id}";
    });

    $router->addRoute('/update/{id}', function ($id) {
        return "Rota com prefixo e parâmetro dinâmico {$id}";
    });
});

$router->run();
```

## Contribuição

Se você gostaria de contribuir para este projeto, por favor, siga estas etapas:

1. Faça um fork do repositório
2. Crie um branch com uma descrição significativa: `git checkout -b minha-funcionalidade`
3. Faça suas alterações e adicione comentários relevantes ao código
4. Certifique-se de executar testes unitários, se aplicável
5. Envie um pull request descrevendo suas alterações

## Licença

Este projeto é licenciado sob a Licença MIT.

## Contato

Se você tiver alguma dúvida ou precisar de ajuda, sinta-se à vontade para entrar em contato:

- Cláudio Oliveira
- franciscoclaudiooliveira@gmail.com
