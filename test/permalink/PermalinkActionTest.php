<?php
namespace test\permalink;

use PHPUnit\Framework\TestCase;
use test\AppTester;

class PermalinkActionTest extends TestCase
{

  public function testPermalink()
  {
    AppTester::assertThatGet('/permalink?branch=master&product=engine&type=all')
      ->statusCode(302)
      ->headerContains('Location', 'master')
      ->headerContains('Location', 'https')
      ->headerContains('Location', 'AxonIvyEngine')
      ->headerContains('Location', 'All_x64')
      ->headerContains('Location', '.zip');
  }

  public function testNotFound()
  {
    AppTester::assertThatGet('/permalink')
      ->statusCode(404)
      ->bodyContains('query param branch missing');
    AppTester::assertThatGet('/permalink?branch=master')
      ->statusCode(404)
      ->bodyContains('query param product missing');
    AppTester::assertThatGet('/permalink?branch=master&product=engine')
      ->statusCode(404)
      ->bodyContains('query param type missing');
  }
}
