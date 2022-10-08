<?php
namespace app\domain\listing;

use app\domain\listing\model\Group;

class Crawler {

  public function get(array $productBuildUrls): array
  {
    $buildJobUrls = (new BuildJobUrlCrawler($productBuildUrls))->crawl();
    $groups = (new ProductLinksCollector($buildJobUrls))->get();
    usort($groups, fn(Group $a, Group $b) => strcmp($b->getText(), $a->getText()));
    return $groups;
  }
}
