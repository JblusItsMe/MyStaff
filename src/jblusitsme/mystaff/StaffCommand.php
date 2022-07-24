<?php

namespace jblusitsme\mystaff;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginOwned;

class StaffCommand extends Command  implements PluginOwned {

  public function __construct() {
    parent::__construct("staff", "Open staff menu", "/staff");
    $this->setPermission("staff.commands");
  }
  public function execute(CommandSender $sender, string $commandLabel, array $args) {
    $plugin = $this->getOwningPlugin();
    if($sender instanceof Player) {
      $name = $sender->getName();
      if(isset(Main::$staff[$name])) {
        (new StaffAPI())->QuitStaffMode($sender);
        (new StaffAPI())->setInventoryContents($sender);
      } else {
        Main::$staff[$name] = $name;
        $sender->sendMessage(Main::$prefix . Main::getConfigPlugin()->get("staff-on"));
        (new StaffAPI())->saveInventory($sender);
        (new StaffAPI())->sendStaffInventory($sender);
      }
    }
  }

  public function getOwningPlugin(): Main {
    return Main::getInstance();
  }

}