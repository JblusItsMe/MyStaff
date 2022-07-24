<?php

namespace jblusitsme\mystaff;

use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\Config;

class StaffAPI implements PluginOwned {

  public function getOwningPlugin(): Plugin { return Main::getInstance(); }

  private function getPlayerConfig(Player $sender): Config {
    $plugin = $this->getOwningPlugin();
    return new Config(
      $plugin->getDataFolder() . "inventories/" . strtolower($sender->getName()) . ".yml",
      Config::YAML
    );
  }

  public function setInventoryContents(Player $sender): void {
    $config = $this->getPlayerConfig($sender);
    if($config->exists("inventory")) {
      $contents = [];
      foreach($config->get("inventory") as $item) {
        $contents[] = ItemFactory::getInstance()->get("$item[0]", "$item[1]", "$item[2]");
      }
      $sender->getInventory()->setContents($contents);
    }
    if($config->exists("armor")) {
      $contentsArmor = [];
      foreach($config->get("armor") as $armor) {
        $contentsArmor[] = ItemFactory::getInstance()->get("$armor[0]", "$armor[1]", "$armor[2]");
      }
      $sender->getArmorInventory()->setContents($contentsArmor);
    }
  }

  public function saveInventory(Player $sender): void {
    $inv = [];
    foreach($sender->getInventory()->getContents() as $content) {
      $inv[] = [$content->getId(), $content->getMeta(), $content->getCount()];
    }
    $config = $this->getPlayerConfig($sender);
    $config->set("inventory", $inv);
    $config->save();

    $armor = [];
    foreach($sender->getArmorInventory()->getContents() as $content) {
      $armor[] = [$content->getId(), $content->getMeta(), $content->getCount()];
    }
    $config = $this->getPlayerConfig($sender);
    $config->set("armor", $armor);
    $config->save();
  }

  public function sendStaffInventory(Player $sender): void {
    $item = ItemFactory::getInstance();
    $sender->getArmorInventory()->clearAll();
    $sender->getInventory()->clearAll();
    $sender->getInventory()->setItem(0, $item->get(ItemIds::BLAZE_ROD)
      ->setCustomName("§r§6Knockback")
      ->addEnchantment(new EnchantmentInstance(VanillaEnchantments::KNOCKBACK(), 6)));
    $sender->getInventory()->setItem(1, $item->get(ItemIds::SLIMEBALL)->setCustomName("§r§2Random Teleport"));

    $sender->getInventory()->setItem(3, $item->get(ItemIds::NETHER_STAR)->setCustomName("§r§4Ban"));
    $sender->getInventory()->setItem(4, $item->get(ItemIds::FROSTED_ICE)->setCustomName("§r§bFreeze"));
    $sender->getInventory()->setItem(5, $item->get(ItemIds::FEATHER)->setCustomName("§r§cKick"));

    $sender->getInventory()->setItem(7, $item->get(ItemIds::ENDER_EYE)->setCustomName("§r§eVanish"));
    $sender->getInventory()->setItem(8, $item->get(ItemIds::ARROW)->setCustomName("§r§dQuit Staff Mode"));
  }

  public function QuitStaffMode(Player $sender): void {
    if(isset(Main::$staff[$sender->getName()])) {
      unset(Main::$staff[$sender->getName()]);
      $sender->sendMessage(Main::$prefix . Main::getConfigPlugin()->get("staff-off"));
      if($sender->isInvisible()) { $sender->setInvisible(false); }
      $this->setInventoryContents($sender);
    }
  }

  public function setVanish(Player $sender): void {
    if($sender->isInvisible()) {
      $sender->setInvisible(false);
      $sender->sendMessage(Main::$prefix . Main::getConfigPlugin()->get("vanish-off"));
    } else {
      $sender->setInvisible();
      $sender->sendMessage(Main::$prefix . Main::getConfigPlugin()->get("vanish-on"));
    }
  }

}