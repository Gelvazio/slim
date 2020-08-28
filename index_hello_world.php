<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require_once "vendor/autoload.php";

// Configuração de erros
$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];
$configurationContainer = new \Slim\Container($configuration);

// Middlewares
$middlware01 = function (Request $request , Response $response, $next){
    // codigo de validação de dados do usuario
    $response->getBody()->write('DENTRO DO MIDDLEARE 01');

    $response = $next($request, $response);

    $response->getBody()->write('DENTRO DO MIDDLEARE 02');

    return $response;
};

$app = new \Slim\App($configurationContainer);

// Agrupando rotas para adicionar o middleware em todas as rotas de uma so vez
$app->group('/produtos10', function () use ($app) {
    $app->get('/produtos_teste');
    $app->get('/produtos_teste2');
    $app->get('/produtos_teste3');
})->add($middlware01);

$app->get('/', function(Request $request, Response $response, array $args){
    return $response->getBody()->write('Bem vindo ao Slim!');
});

$app->get('/produto', function(Request $request, Response $response, array $args){
    $data = $request->getParsedBody();

    $nome = $data['nome'] ?? '';

    return $response->getBody()->write("Produto {$nome} (POST)");
})
    // Adicionando middlware na rota
    ->add($middlware01);

$app->post('/produto', function(Request $request, Response $response, array $args){
    $data = $request->getParsedBody();

    $nome = $data['nome'] ?? '';

    return $response->getBody()->write("Produto {$nome} (POST)");
})
    // Adicionando middlware na rota
    ->add($middlware01);

$app->put('/produto', function(Request $request, Response $response, array $args){
    $data = $request->getParsedBody();

    $nome = $data['nome'] ?? '';

    return $response->getBody()->write("Produto {$nome} (PUT)");
});

$app->delete('/produto', function(Request $request, Response $response, array $args){
    $data = $request->getParsedBody();

    $nome = $data['nome'] ?? '';

    return $response->getBody()->write("Produto {$nome} (DELETE)");
});

$app->run();
