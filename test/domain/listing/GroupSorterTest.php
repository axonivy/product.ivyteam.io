<?php
namespace test\domain\listing;

use PHPUnit\Framework\TestCase;
use app\domain\listing\GroupSorter;
use app\domain\listing\model\Group;

class GroupSorterTest extends TestCase
{

  public function testSort()
  {
    $groups = [
        new Group("7.0", "release/7.0", "", []),
        new Group("8.0", "release/8.0", "", []),
        new Group("11.2", "release/11.2", "", []),
        new Group("master", "master", "", []),
        new Group("11.1", "release/11.1", "", []),
        new Group("10.0", "release/10.", "", []),
    ];
    shuffle($groups);
    
    $sortedGroups = GroupSorter::sort($groups);
    
    $this->assertEquals(6, sizeof($sortedGroups));
    $this->assertEquals("master", $sortedGroups[0]->getText());
    $this->assertEquals("11.2", $sortedGroups[1]->getText());
    $this->assertEquals("11.1", $sortedGroups[2]->getText());
    $this->assertEquals("10.0", $sortedGroups[3]->getText());
    $this->assertEquals("8.0", $sortedGroups[4]->getText());
    $this->assertEquals("7.0", $sortedGroups[5]->getText());
  }
}
