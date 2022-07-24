<?php

namespace jblusitsme\mystaff\Interact;

use jblusitsme\mystaff\Main;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\player\Player;

class KickStaff implements Listener {

  public function onKickPlayer(EntityDamageEvent $event) {
    $kicked = $event->getEntity();
    if($event instanceof EntityDamageByEntityEvent) {
      $sender = $event->getDamager();
      if($sender instanceof Player) {
        if(isset(Main::$staff[$sender->getName()])) {
          if($sender->getInventory()->getItemInHand()->getId() == 288) {
            if($kicked instanceof Player) {
              $form = new SimpleForm(function(Player $sender, $data) use ($kicked) {
                if($data == null) {
                  return true;
                }
                foreach(Main::getConfigPlugin()->get("kick-reason") as $key => $reason) {
                  if($data == intval($key) - 1) {
                    $kicked->kick(str_replace(
                      array("{staff}", "{reason}", "{line}"), array($sender->getName(), $reason, "\n"),
                      Main::getConfigPlugin()->get("kick-message")
                    ));
                    $sender->sendMessage(Main::$prefix . str_replace(
                      array("{staff}", "{reason}", "{line}"), array($sender->getName(), $reason, "\n"),
                      Main::getConfigPlugin()->get("kick-message")
                    ));
                  }
                }
              });
              $form->setTitle("MyStaff - Kick");
              foreach(Main::getConfigPlugin()->get("kick-reason") as $key => $reason) {
                $form->addButton($reason);
              }
              $form->sendToPlayer($sender);
            }
          }
        }
      }
    }
  }

}