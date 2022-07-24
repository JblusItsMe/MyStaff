<?php

namespace jblusitsme\mystaff\Interact;

use jblusitsme\mystaff\Main;
use jblusitsme\mystaff\StaffAPI;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;

class VanishItem implements Listener {

  public function onItemVanish(PlayerInteractEvent $event) {
    $sender = $event->getPlayer();
    $item = $sender->getInventory()->getItemInHand();
    if($item->getId() == 381) {
      if(isset(Main::$staff[$sender->getName()])) {
        (new StaffAPI())->setVanish($sender);
      }
    }
  }

}