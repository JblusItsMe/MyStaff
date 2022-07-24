<?php

namespace jblusitsme\mystaff\Interact;

use jblusitsme\mystaff\Main;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\Listener;

class StaffTransaction implements Listener {

  public function onTransaction(InventoryTransactionEvent $event) {
    $sender = $event->getTransaction()->getSource();
    $name = $sender->getName();
    if(isset(Main::$staff[$name])) {
      $event->cancel();
    }
  }

}