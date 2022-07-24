<?php

namespace jblusitsme\mystaff\Interact;

use jblusitsme\mystaff\StaffAPI;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use jblusitsme\mystaff\Main;
use pocketmine\item\Arrow;

class QuitStaff implements Listener {

  public function onQuitStaffMode(PlayerInteractEvent $event) {
    $sender = $event->getPlayer();
    $item = $sender->getInventory()->getItemInHand();
    if(isset(Main::$staff[$sender->getName()])) {
      if($item instanceof Arrow) {
        (new StaffAPI())->QuitStaffMode($sender);
      }
    }
  }

}