<?php
namespace app\domain\listing;

class GroupSorter {

  const MASTER = "master";

  public static function sort(array $groups)
  {
    usort($groups, function ($a, $b) {
      if ($b->getText() == self::MASTER) {
        return 1;
      } else if ($a->getText() == self::MASTER) {
        return -1;
      } else {
        return version_compare($b->getText(), $a->getText());
      }
    });
    return $groups;
  }
}