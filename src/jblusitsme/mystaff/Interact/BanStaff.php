<?php

namespace jblusitsme\mystaff\Interact;

use jblusitsme\mystaff\Main;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;

class BanStaff implements Listener {

  public function onBanPlayer(EntityDamageEvent $event) {
    $banned = $event->getEntity();
    if($event instanceof EntityDamageByEntityEvent) {
      $sender = $event->getDamager();
      if($sender instanceof Player) {
        if(isset(Main::$staff[$sender->getName()])) {
          if($sender->getInventory()->getItemInHand()->getId() == ItemIds::NETHER_STAR) {
            if($banned instanceof Player) {
              $form = new SimpleForm(function(Player $sender, $data) use ($banned) {
                if($data == null) {
                  return true;
                }
                foreach(Main::getConfigPlugin()->get("ban-reason") as $key => $reason) {
                  $res = str_replace(
                    array("{staff}", "{reason}", "{line}"),
                    array($sender->getName(), $reason, "\n"),
                    Main::getConfigPlugin()->get("ban-message")
                  );
                  if($data == intval($key) - 1) {
                    $sender->getServer()->getNameBans()->addBan(
                      $banned->getName(), $res, null, $sender->getName()
                    );
                    $banned->kick($res);
                    $sender->sendMessage(Main::$prefix . $res);
                  }
                }
              });
              $form->setTitle("MyStaff - Ban");
              foreach(Main::getConfigPlugin()->get("ban-reason") as $key => $reason) {
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