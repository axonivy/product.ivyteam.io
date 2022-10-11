<?php
namespace test\domain\listing;

use PHPUnit\Framework\TestCase;
use app\domain\listing\BuildJobUrlCrawler;
use DOMDocument;

class BuildJobUrlCrawlerTest extends TestCase
{

  public function testParse()
  {
    libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $dom->loadHTMLFile(dirname(__FILE__) . "/product-build.html");

    $urls = BuildJobUrlCrawler::parse($dom, "https://anyurl/");
    $this->assertEquals(2, sizeof($urls));
    $this->assertEquals("https://anyurl/job/master/lastSuccessfulBuild/", $urls[0]);
    $this->assertEquals("https://anyurl/job/release%252F8.0/lastSuccessfulBuild/", $urls[1]);
  }
}
