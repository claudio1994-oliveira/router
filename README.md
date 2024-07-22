# Componente Router

[![Latest Stable Version](https://poser.pugx.org/claud/router/v)](//packagist.org/packages/claud/router)
[![Total Downloads](https://poser.pugx.org/claud/router/downloads)](//packagist.org/packages/claud/router)
[![Latest Unstable Version](https://poser.pugx.org/claud/router/v/unstable)](//packagist.org/packages/claud/router)
[![License](https://poser.pugx.org/claud/router/license)](//packagist.org/packages/claud/router)

O **Componente Router** é uma ferramenta simples e flexível para gerenciar rotas em aplicativos PHP. Este componente permite que você defina rotas e associe funções ou métodos de controladores a elas, facilitando a criação de aplicativos da web com URLs amigáveis.

## Recursos

- Fácil definição de rotas
- Suporte a rotas com parâmetros
- Funções ou métodos de controladores associados a rotas
- Suporte para Request e Response
- Suporte a Middlewares globais e locais em cada rota

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
$router->addRoute('/perfil/{id}', [App\Controller\PerfilController::class,'show']);

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

Por padrão, o método http usado será o GET mas você pode alternar o método passando um terceiro parâmetro (string $method) na função addRoute()

```php
<?php

require_once 'vendor/autoload.php';

use Router\Router\Router;

$router = new Router();

$router->addRoute('/', [App\Controller\PerfilControlle::class, 'store'], 'POST');

$router->run();
```

Você poderá usar Middlewares para interceptar suas requisições. Para criar os seus middlewares, basta implementar a interface MiddlewareInterface do mesmo pacote.

Além do contrato da interface, provemos suas classes de Request e Response para suas implementações.

```php
<?php

require_once 'vendor/autoload.php';

use Router\Router\Router;
use Router\Contracts\MiddlewareInterface;

class GlobalMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next)
    {
        if (!isset($_SESSION['user'])) {

            return 'not authorized';
        }

        return $next($request);
    }
}

class LocalMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next)
    {

        return $next($request);
    }
}

$router = new Router();

//Para adicionar globalmente
$router->addMiddleware(new GlobalMiddleware());

// Para adicionar especifico para a rota
$router->addRoute('/', [App\Controller\PerfilControlle::class, 'store'], 'POST', [new LocalMiddlware()]);

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
