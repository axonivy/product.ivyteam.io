<?php
namespace test\domain\listing;

use PHPUnit\Framework\TestCase;
use app\domain\listing\ProductLinksCollector;
use DOMDocument;

class ProductLinksCollectorTest extends TestCase
{

  public function testParse()
  { 
    libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $dom->loadHTMLFile(dirname(__FILE__) . "/build-job.html");

    $urls = ProductLinksCollector::parse($dom, "https://anyurl/");
    $this->assertEquals(5, sizeof($urls));
    $this->assertEquals("AxonIvyDesigner10.0.0.2210080034_Linux_x64.zip", $urls[0]->getText());
    $this->assertEquals("https://anyurl/lastSuccessfulBuild/artifact/workspace/ch.ivyteam.ivy.designer.product/target/products/AxonIvyDesigner10.0.0.2210080034_Linux_x64.zip", $urls[0]->getUrl());

    $this->assertEquals("AxonIvyDesigner10.0.0.2210080034_Windows_x64.zip", $urls[1]->getText());
    $this->assertEquals("https://anyurl/lastSuccessfulBuild/artifact/workspace/ch.ivyteam.ivy.designer.product/target/products/AxonIvyDesigner10.0.0.2210080034_Windows_x64.zip", $urls[1]->getUrl());

    $this->assertEquals("AxonIvyEngine10.0.0.2210080034_All_x64.zip", $urls[2]->getText());
    $this->assertEquals("https://anyurl/lastSuccessfulBuild/artifact/workspace/ch.ivyteam.ivy.server.product/target/products/AxonIvyEngine10.0.0.2210080034_All_x64.zip", $urls[2]->getUrl());

    $this->assertEquals("AxonIvyEngine10.0.0.2210080034_Slim_All_x64.zip", $urls[3]->getText());
    $this->assertEquals("https://anyurl/lastSuccessfulBuild/artifact/workspace/ch.ivyteam.ivy.server.product/target/products/AxonIvyEngine10.0.0.2210080034_Slim_All_x64.zip", $urls[3]->getUrl());

    $this->assertEquals("AxonIvyEngine10.0.0.2210080034_Windows_x64.zip", $urls[4]->getText());
    $this->assertEquals("https://anyurl/lastSuccessfulBuild/artifact/workspace/ch.ivyteam.ivy.server.product/target/products/AxonIvyEngine10.0.0.2210080034_Windows_x64.zip", $urls[4]->getUrl());
  }

  public function testName()
  {
    $this->assertEquals("master", ProductLinksCollector::name("https://anyurl/job/master/lastSuccessfulBuild/"));
    $this->assertEquals("8.0", ProductLinksCollector::name("https://anyurl/job/release%252F8.0/lastSuccessfulBuild/"));
  }

  public function testNonExistingBuildJobUrl()
  {
    $crawler = new ProductLinksCollector(["https://jenkins.ivyteam.io/any-non-existing-url"]);
    $this->assertEmpty($crawler->get());
  }
}
