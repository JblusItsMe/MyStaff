<?php

namespace jblusitsme\mystaff\Interact;

use jblusitsme\mystaff\Main;
use pocketmine\entity\Location;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use pocketmine\world\Position;

class RandomTpStaff implements Listener {

  protected array $players = [];

  public function randomTeleportItem(PlayerInteractEvent $event) {
    $sender = $event->getPlayer();
    if($sender->getInventory()->getItemInHand()->getId() == ItemIds::SLIMEBALL) {
      if(isset(Main::$staff[$sender->getName()])) {
        foreach(Main::getInstance()->getServer()->getOnlinePlayers() as $player) {
          if($player instanceof Player) {
            $this->players[$player->getName()] = $player->getName();
          }
        }
        $rand = array_rand($this->players);
        $server = Main::getInstance()->getServer()->getPlayerExact($rand);
        $pos = $server->getPosition();
        $sender->teleport(new Position($pos->getX(), $pos->getY(), $pos->getZ(), $pos->getWorld()));
      }
    }
  }

}