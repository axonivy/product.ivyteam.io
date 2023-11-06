<?php
namespace app\permalink;

use Slim\Exception\HttpNotFoundException;
use app\domain\listing\Crawler;
use Slim\Views\Twig;

class PermalinkAction
{
  private Twig $view;

  public function __construct(Twig $view)
  {
    $this->view = $view;
  }

  public function __invoke($request, $response, $args)
  {
    $branch = $request->getQueryParams()['branch'] ?? ''; // master, release/10.0
    if (empty($branch)) {
      throw new HttpNotFoundException($request, 'query param branch missing');
    }

    $product = $request->getQueryParams()['product'] ?? ''; // engine or designer
    if (empty($product)) {
      throw new HttpNotFoundException($request, 'query param product missing (engine or designer)');
    }

    $type = $request->getQueryParams()['type'] ?? ''; // all, slim, windows
    if (empty($type)) {
      throw new HttpNotFoundException($request, 'query param type missing (all, slim or windows)');
    }

    $groups = (new Crawler())->get();

    foreach ($groups as $group) {
      if ($group->getBranchName() == $branch) {
        foreach ($group->getLinks() as $link) {
          $text = strtolower($link->getText());
          if (str_contains($text, $product)) {
            if (str_contains($text, $type)) {
              $location = $link->getUrl();
              return $response->withHeader('Location', $location)->withStatus(302);
            }
          }
        }
      }
    }
    throw new HttpNotFoundException($request, 'could not find a product with the gien query params');
  }
}
