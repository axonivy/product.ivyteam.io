<?php
namespace app\domain\listing;

use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use DOMDocument;

class BuildJobUrlCrawler
{
  private array $productBuildUrls;

  public function __construct(array $productBuildUrls)
  {
    $this->productBuildUrls = $productBuildUrls;
  }
  
  public function crawl(): array
  {
    $client = new Client();
    $promises = [];
    foreach ($this->productBuildUrls as $productBuildUrl) {
      $promises[$productBuildUrl] = $client->getAsync($productBuildUrl);
    }
    
    $responses = Promise\Utils::unwrap($promises);
    
    $productLinks =[];
    foreach ($responses as $url => $response) {
      $content = $response->getBody();
      $dom = new DOMDocument();
      $dom->loadHTML($content);
      $productLinks = array_merge($productLinks, self::parse($dom, $url));
    }
    return $productLinks;
  }
  
  public static function parse(DOMDocument $dom, String $baseUrl): array {
    libxml_use_internal_errors(true);
    $urls = [];
    $child_elements = $dom->getElementsByTagName('a');
    foreach ($child_elements as $child) {
      $title = $child->getAttribute('title');
      $href = $child->getAttribute('href');
      if (str_contains($title, "master") || str_contains($title, "release")) {
        $urls[] = $baseUrl . $href . "lastSuccessfulBuild/";
      }
    }
    return $urls;
  }
}
