<?php
namespace app\pages\home;

use app\domain\listing\Crawler;
use Slim\Views\Twig;

class HomeAction
{
  private Twig $view;

  public function __construct(Twig $view)
  {
    $this->view = $view;
  }

  public function __invoke($request, $response, $args)
  {
    $groups = (new Crawler())->get();
    return $this->view->render($response, 'home/home.twig', [
      'groups' => $groups
    ]);
  }
}
