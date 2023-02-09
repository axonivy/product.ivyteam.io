<?php
namespace app\domain\listing;

class Crawler {

  public function get(array $productBuildUrls): array
  {
    $buildJobUrls = (new BuildJobUrlCrawler($productBuildUrls))->crawl();
    $groups = (new ProductLinksCollector($buildJobUrls))->get();
    GroupSorter::sort($groups);
    return $groups;
  }
}
