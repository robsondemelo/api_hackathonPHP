<?php

use Alfa\Categoria;
use Alfa\Empresa;
use Alfa\Produto;
use Alfa\Query;
use Alfa\Database;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Symfony\Component\VarDumper\VarDumper;
use Slim\Psr7\Response as Psr7Response;

require_once './../vendor/autoload.php';

$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

$database = new Database(
    getenv('DATABASE_HOST'),
    getenv('DATABASE_NAME'),
    getenv('DATABASE_USER'),
    getenv('DATABASE_PASS')
);

$authMiddleware = function ( Request $request, RequestHandlerInterface $handler) {
    if(!isset ($request->getHeaders()['Authorization'][0])) {
        $response = new Psr7Response();
        $response->getBody()->write(json_encode(['error' => 'Token não informado'])
        );
        return $response->withHeader('Content-Type', 'application/json' )
                        ->withStatus(StatusCodeInterface::STATUS_BAD_REQUEST);
    }
    return $handler->handle($request);
};

$logMiddleware = function ( Request $request, RequestHandlerInterface $handler) {
    $inicio = microtime(true);
    $response = $handler->handle($request);
    $fim = microtime(true);
    file_put_contents("../log/access_log",
        sprintf("%s [%s] %s %s %ss\n",
        date("d/m/Y H:i:s"),
        $request->getMethod(),
        $request->getUri(),
        $response->getStatusCode(),
        round($fim-$inicio, 2)
        
        ),
        FILE_APPEND
    );
    return $response;
 };

$query = new Query($database);

//rota categorias
$app->post('/categorias', function(Request $request, Response $response) use ($query) {
    
    $categoriaRequest = json_decode($request->getBody()->getContents());
   
    $categoria = new Categoria();

    $categoria->categoria = $categoriaRequest->categoria;
    
    $id = $query->insert($categoria);

    $newCategoria= $query->find($id, Categoria::class);
    
    $response->getBody()->write(json_encode($newCategoria));
    return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(201);
});

$app->get('/categorias/{id}', function(Request $request, Response $response, $args) use ($query){
    $id= $args['id'];
    $categoria = $query->find($id, Categoria::class);
    if (is_null($categoria)) {
        return $response->withStatus(404);
    }

    $response->getBody()->write(json_encode($categoria));
    return $response
                ->withHeader('Content-Type', 'application/json');
});

$app->get('/categorias', function(Request $request, Response $response) use ($query) {
    $response->getBody()->write(json_encode($query->findAll(Categoria::class)));
    return $response
               ->withHeader('Content-Type', 'application/json')
               ->withStatus(200);

})/*->add($logMiddleware)
    ->add($authMiddleware)*/;

$app->put('/categorias/{id}', function(Request $request, Response $response, array $args) use ($query) {
   
    $id = $args['id'];
    
    $categoria = $query->find($id, Categoria::class);
        
    if (is_null($categoria)) {
        return $response->withStatus(404);
    }

    $categoriaRequest = json_decode($request->getBody()->getContents());

    $categoria->categoria = $categoriaRequest->categoria;
        
    $query->update($categoria);

    $response->getBody()->write(json_encode($query->find($id, Categoria::class)));
    return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(200);
});

$app->delete('/categorias/{id}', function (Request $request, Response $response, $args) use ($query) {
    $categoria= $query->find($args['id'], Categoria::class);
    
    if (is_null($categoria)) {
        return $response->withStatus(404);
    }
    
    $query->delete($categoria);
    return $response->withStatus(204);

});

//rota empresas
$app->post('/empresas', function(Request $request, Response $response) use ($query) {
    
    $empresaRequest = json_decode($request->getBody()->getContents());
   
    $empresa = new Empresa();

    $empresa->empresa = $empresaRequest->empresa;
    $empresa->whatsapp = $empresaRequest->whatsapp;
    
    $id = $query->insert($empresa);

    $newEmpresa= $query->find($id, Empresa::class);
    
    $response->getBody()->write(json_encode($newEmpresa));
    return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(201);
});

$app->get('/empresas/{id}', function(Request $request, Response $response, $args) use ($query){
    $id= $args['id'];
    $empresa = $query->find($id, Empresa::class);
    if (is_null($empresa)) {
        return $response->withStatus(404);
    }

    $response->getBody()->write(json_encode($empresa));
    return $response
                ->withHeader('Content-Type', 'application/json');
});

$app->get('/empresas', function(Request $request, Response $response) use ($query) {
    $response->getBody()->write(json_encode($query->findAll(Empresa::class)));
    return $response
               ->withHeader('Content-Type', 'application/json')
               ->withStatus(200);

})/*->add($logMiddleware)
    ->add($authMiddleware)*/;

$app->put('/empresas/{id}', function(Request $request, Response $response, array $args) use ($query) {
   
    $id = $args['id'];
    
    $empresa = $query->find($id, Empresa::class);
        
    if (is_null($empresa)) {
        return $response->withStatus(404);
    }

    $empresaRequest = json_decode($request->getBody()->getContents());

    $empresa->empresa = $empresaRequest->empresa;
    $empresa->whatsapp = $empresaRequest->whatsapp;
        
    $query->update($empresa);

    $response->getBody()->write(json_encode($query->find($id, Empresa::class)));
    return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(200);
});

$app->delete('/empresas/{id}', function (Request $request, Response $response, $args) use ($query) {
    $empresa= $query->find($args['id'], Empresa::class);
    
    if (is_null($empresa)) {
        return $response->withStatus(404);
    }
    
    $query->delete($empresa);
    return $response->withStatus(204);

});

//produtos
$app->post('/produtos', function(Request $request, Response $response) use ($query) {
    //esta recebendo o json e decodificando pro php
    $produtoRequest = json_decode($request->getBody()->getContents());
   
    $produto = new Produto();

    $produto->produto = $produtoRequest->produto;
    $produto->foto = $produtoRequest->foto;
    $produto->descricao = $produtoRequest->descricao;
    $produto->valor = $produtoRequest->valor;
    $produto->categoria_id = $produtoRequest->categoria_id;
    $produto->empresa_id = $produtoRequest->empresa_id;
    
    
    $id = $query->insert($produto);

    $newProduto= $query->find($id, Produto::class);
    //ele faz um novo encode pra devolver como json para uso
    $response->getBody()->write(json_encode($newProduto));
    return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(201);
});

$app->get('/produtos/{id}', function(Request $request, Response $response, $args) use ($query){
    $id= $args['id'];
    $produto = $query->find($id, Produto::class);
    if (is_null($produto)) {
        return $response->withStatus(404);
    }

    $response->getBody()->write(json_encode($produto));
    return $response
                ->withHeader('Content-Type', 'application/json');
});

$app->get('/produtos', function(Request $request, Response $response) use ($query) {
    $response->getBody()->write(json_encode($query->findAll(Produto::class)));
    return $response
               ->withHeader('Content-Type', 'application/json')
               ->withStatus(200);

})/*->add($logMiddleware)
    ->add($authMiddleware)*/;

$app->put('/produtos/{id}', function(Request $request, Response $response, array $args) use ($query) {
   
    $id = $args['id'];
    
    $produto = $query->find($id, Produto::class);
        
    if (is_null($produto)) {
        return $response->withStatus(404);
    }

    $produtoRequest = json_decode($request->getBody()->getContents());

    $produto->produto = $produtoRequest->produto;
    $produto->foto = $produtoRequest->foto;
    $produto->descricao = $produtoRequest->descricao;
    $produto->valor = $produtoRequest->valor;
    $produto->categoria_id = $produtoRequest->categoria_id;
    $produto->empresa_id = $produtoRequest->empresa_id;

    $query->update($produto);

    $response->getBody()->write(json_encode($query->find($id, Produto::class)));
    return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(200);
});

$app->delete('/produtos/{id}', function (Request $request, Response $response, $args) use ($query) {
    $produto= $query->find($args['id'], Produto::class);
    
    if (is_null($produto)) {
        return $response->withStatus(404);
    }
    
    $query->delete($produto);
    return $response->withStatus(204);

});

//rota teste ip server
$app->get('/server', function(Request $request, Response $response) {
    $response->getBody()->write(
        json_encode([
            'IP' => $request->getServerParams()['SERVER_ADDR']
        ])
);
    return $response
               ->withHeader('Content-Type', 'application/json')
               ->withStatus(200);

});



$app->run();