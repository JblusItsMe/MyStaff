<?php

namespace jblusitsme\mystaff\Interact;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use jblusitsme\mystaff\Main;

class PlaceDestroyInStaff implements Listener {

  public function onPlace(BlockPlaceEvent $event) {
    $sender = $event->getPlayer();
    if(isset(Main::$staff[$sender->getName()])) {
      $event->cancel();
    }
  }

  public function onDestroy(BlockBreakEvent $event) {
    $sender = $event->getPlayer();
    if(isset(Main::$staff[$sender->getName()])) {
      $event->cancel();
    }
  }

}