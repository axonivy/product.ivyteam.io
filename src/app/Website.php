<?php

namespace app;

use DI\Container;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Factory\AppFactory;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use DI\ContainerBuilder;
use app\pages\home\HomeAction;
use app\permalink\PermalinkAction;
use Slim\App;
use Throwable;

class Website
{
  private $app;

  function __construct()
  {
    $container = $this->createDiContainer();
    $this->app = AppFactory::createFromContainer($container);
    $this->installRoutes();
    $this->installErrorHandling();
  }

  private function createDiContainer(): Container
  {
    $builder = new ContainerBuilder();
    $builder->addDefinitions([
      Twig::class => Twig::create(__DIR__ . '/../app/pages')
    ]);
    return $builder->build();
  }

  public function app(): App
  {
    return $this->app;
  }

  public function start()
  {
    $this->app->run();
  }

  private function installRoutes()
  {
    $this->app->get('/permalink', PermalinkAction::class);
    $this->app->get('/', HomeAction::class);
  }

  private function installErrorHandling()
  {
    $container = $this->app->getContainer();
    $errorMiddleware = $this->app->addErrorMiddleware(true, true, true);
    $errorMiddleware->setErrorHandler(HttpNotFoundException::class, function (ServerRequestInterface $request, Throwable $exception, bool $displayErrorDetails) use ($container) {
      $response = new Response();
      $data = ['message' => $exception->getMessage()];
      return $container->get(Twig::class)
        ->render($response, '_error/404.twig', $data)
        ->withStatus(404);
    });

    $errorMiddleware->setDefaultErrorHandler(function (ServerRequestInterface $request, Throwable $exception, bool $displayErrorDetails) use ($container) {
      $response = new Response();
      $data = ['message' => $exception->getMessage()];
      return $container->get(Twig::class)
          ->render($response, '_error/500.twig', $data)
          ->withStatus(500);
    });
  }
}
