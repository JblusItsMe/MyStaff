<?php

namespace jblusitsme\mystaff\Interact;

use jblusitsme\mystaff\Main;
use pocketmine\block\FrostedIce;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\player\Player;

class FreezeStaff implements Listener {

  public array $freeze = [];

  public function onFreezePlayer(EntityDamageEvent $event) {
    $freeze = $event->getEntity();
    if($event instanceof EntityDamageByEntityEvent) {
      $sender = $event->getDamager();
      if($sender instanceof Player) {
        if(isset(Main::$staff[$sender->getName()])) {
          if($sender->getInventory()->getItemInHand()->getId() == 207) {
            if($freeze instanceof Player) {
              $event->cancel();
              if(isset($this->freeze[$freeze->getName()])) {
                unset($this->freeze[$freeze->getName()]);
                $sender->sendMessage(Main::$prefix . str_replace(
                  array("{user}"), array($freeze->getName()), Main::getConfigPlugin()->get("freeze-message-off")
                ));
                $freeze->setNameTag($freeze->getName());
              } else {
                $sender->sendMessage(Main::$prefix . str_replace(
                  array("{user}"), array($freeze->getName()), Main::getConfigPlugin()->get("freeze-message-on")
                ));
                $freeze->sendTitle(
                  Main::getConfigPlugin()->get("freeze-title"),
                  Main::getConfigPlugin()->get("freeze-subtitle")
                );
                $freeze->setNameTag("§l§4FREEZE §r§c" . $freeze->getName());
                $this->freeze[$freeze->getName()] = $freeze->getName();
              }
            }
          }
        }
      }
    }
  }

  public function onMove(PlayerMoveEvent $event) {
    $sender = $event->getPlayer();
    if(isset($this->freeze[$sender->getName()])) {
      $event->cancel();
    }
  }

}