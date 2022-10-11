<?php
namespace test\pages;

use PHPUnit\Framework\TestCase;
use test\AppTester;

class NotFound404Test extends TestCase
{

  public function testRender()
  {
    AppTester::assertThatGet('/none-existing-site')
      ->statusCode(404)
      ->bodyContains('Sorry! Not found');
  }
}
