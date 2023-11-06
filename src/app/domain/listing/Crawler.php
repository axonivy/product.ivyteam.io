<?php
namespace app\domain\listing;

class Crawler {

  const PRODUCT_URLS = [
    "https://jenkins.ivyteam.io/job/core_product/",
    "https://jenkins.ivyteam.io/job/core-7_product/"
  ];

  public function get(): array
  {
    $buildJobUrls = (new BuildJobUrlCrawler(self::PRODUCT_URLS))->crawl();
    $groups = (new ProductLinksCollector($buildJobUrls))->get();
    $groups = GroupSorter::sort($groups);
    return $groups;
  }
}
