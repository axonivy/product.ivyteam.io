<?php
namespace app\domain\listing;

use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use DOMDocument;
use app\domain\listing\model\Group;
use app\domain\listing\model\ProductLink;

class ProductLinksCollector {

  private array $buildJobUrls;

  public function __construct(array $buildJobUrls)
  {
    libxml_use_internal_errors(true);
    $this->buildJobUrls = $buildJobUrls;
  }
  
  public function get(): array
  {
    $client = new Client();
    
    $promises = [];
    foreach ($this->buildJobUrls as $buildJobUrl) {
      $promises[$buildJobUrl] = $client->getAsync($buildJobUrl, ['http_errors' => false]);
    }
    
    $responses = Promise\Utils::unwrap($promises);
    
    $groups =[];
    foreach ($responses as $url => $response) {
      if ($response->getStatusCode() == 404) {
        continue;
      }
      $content = $response->getBody();
      $dom = new DOMDocument();
      $dom->loadHTML($content);
      $productLinks = self::parse($dom, $url);
      $name = self::name($url);
      $branchName = self::branchName($url);
      $groups[] = new Group($name, $branchName, $url, $productLinks);
    }
    return $groups;
  }
  
  public static function parse(DOMDocument $dom, String $baseUrl): array {
    $productLinks = [];
    $child_elements = $dom->getElementsByTagName('a');
    foreach ($child_elements as $child) {
      $href = $child->getAttribute('href');
      if (str_contains($href, "AxonIvy") && str_ends_with($href, '.zip') && !str_contains($href, "Repository")) {
        $text = basename($href);
        $url = $baseUrl . $href;
        $link = new ProductLink($text, $url);
        $productLinks[] = $link;
      }
    }
    return $productLinks;
  }

  public static function name($url): String {
    return basename(dirname(str_replace("%252F", "/", $url)));
  }

  public static function branchName($url): String {
    $url = str_replace("%252F", "/", $url);
    $url = substr($url, strrpos($url, 'job/') + 4);
    return dirname($url);
  }
}
