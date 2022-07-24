<?php

namespace jblusitsme\mystaff;

use jblusitsme\mystaff\Interact\BanStaff;
use jblusitsme\mystaff\Interact\DamageOnStaffMode;
use jblusitsme\mystaff\Interact\FreezeStaff;
use jblusitsme\mystaff\Interact\KickStaff;
use jblusitsme\mystaff\Interact\PlaceDestroyInStaff;
use jblusitsme\mystaff\Interact\QuitStaff;
use jblusitsme\mystaff\Interact\RandomTpStaff;
use jblusitsme\mystaff\Interact\StaffTransaction;
use jblusitsme\mystaff\Interact\VanishItem;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener {

  public static Main $instance;
  public static Config $config;
  public static string $prefix = "§6MyStaff §7» §r";
  public static array $staff = [];

  protected function onEnable(): void {
    self::$instance = $this;
    self::$config = new Config($this->getDataFolder() . "config.yml", Config::YAML, [
      "staff-on" => "You have opened your staff menu.",
      "staff-off" => "You have closed your staff menu.",
      "vanish-on" => "You are now invisible.",
      "vanish-off" => "You are now visible.",
      "kick-message" => "You’ve been kicking by §e{staff}§r.{line}Reason: §b{reason}",
      "freeze-message-on" => "You have frozen §e{user}§r user.",
      "freeze-message-off" => "You have unfrozen §e{user}§r user.",
      "freeze-title" => "§r§l§4YOU'RE FREEZING",
      "freeze-subtitle" => "§r§https://discord.gg/yourserver",
      "ban-message" => "You've banned banned by §e{staff}§r.{line}Reason: §b{reason}",
      "kick-reason" => array(
        1 => "§cChoose your reason",
        2 => "Fly",
        3 => "Use bug"
      ),
      "ban-reason" => array(
        1 => "§cChoose your reason",
        2 => "Fly",
        3 => "Cheat",
        4 => "Use bug"
      )
    ]);

    $dir = $this->getDataFolder() . "inventories";
    if(!is_dir($dir)) {
      mkdir($dir);
    }

    $this->getServer()->getCommandMap()->registerAll("staff", [
      new StaffCommand()
    ]);

    $this->getServer()->getPluginManager()->registerEvents($this, $this);
    $this->getServer()->getPluginManager()->registerEvents(new PlaceDestroyInStaff(), $this);
    $this->getServer()->getPluginManager()->registerEvents(new QuitStaff(), $this);
    $this->getServer()->getPluginManager()->registerEvents(new VanishItem(), $this);
    $this->getServer()->getPluginManager()->registerEvents(new StaffTransaction(), $this);
    $this->getServer()->getPluginManager()->registerEvents(new KickStaff(), $this);
    $this->getServer()->getPluginManager()->registerEvents(new FreezeStaff(), $this);
    $this->getServer()->getPluginManager()->registerEvents(new RandomTpStaff(), $this);
    $this->getServer()->getPluginManager()->registerEvents(new BanStaff(), $this);
  }

  protected function onDisable(): void {
    foreach($this->getServer()->getOnlinePlayers() as $sender) {
      if(isset(self::$staff[$sender->getName()])) {
        (new StaffAPI())->setInventoryContents($sender);
      }
    }
  }

  public static function getConfigPlugin(): Config { return self::$config; }

  public static function getInstance(): Main { return self::$instance; }

  public function onQuit(PlayerQuitEvent $event) {
    $name = $event->getPlayer()->getName();
    if(isset(self::$staff[$name])) {
      (new StaffAPI())->setInventoryContents($event->getPlayer());
      unset(self::$staff[$name]);
    }
  }

}