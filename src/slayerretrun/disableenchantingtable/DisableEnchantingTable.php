<?php

/**
 *   _____ _                       _____      _                    
 *  / ____| |                     |  __ \    | |                   
 * | (___ | | __ _ _   _  ___ _ __| |__) |___| |_ _ __ _   _ _ __  
 *  \___ \| |/ _` | | | |/ _ \ '__|  _  // _ \ __| '__| | | | '_ \ 
 *  ____) | | (_| | |_| |  __/ |  | | \ \  __/ |_| |  | |_| | | | |
 * |_____/|_|\__,_|\__, |\___|_|  |_|  \_\___|\__|_|   \__,_|_| |_|
 *                  __/ |                                          
 *                 |___/                                           
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * @author SlayerRetrun Team
 * @link https://github.com/Slayer-Return
 * 
 * 
 */

declare(strict_types=1);

namespace slayerretrun\disableenchantingtable;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerEnchantingOptionsRequestEvent;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as T;

class DisableEnchantingTable extends PluginBase implements Listener
{
    protected function onLoad() : void
    {
        $this->saveDefaultConfig();
    }

    protected function onEnable() : void
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool
    {
        if (!$sender instanceof Player){
            $sender->sendMessage(T::RED . "You must be logged in to use this command.");
            return true;
        } else {
            if ($cmd->getName() === "disableenchantingtable"){
                if (!isset($args[0])){
                    $sender->sendMessage(T::RED . "Use: /disableenchantingtable <true:false>");
                    return true;
                } else {
                    switch (strtolower($args[0])){
                        case "false":
                            $this->getConfig()->set("disable-enchantingtable", false);
                            $this->getConfig()->save();
                            $sender->sendMessage(T::GREEN . "Disable Enchanting Table has been set to " . T::YELLOW . "false" . T::GREEN . ".");
                            return true;
                        case "true":
                            $this->getConfig()->set("disable-enchantingtable", true);
                            $this->getConfig()->save();
                            $sender->sendMessage(T::GREEN . "Disable Enchanting Table has been set to " . T::YELLOW . "true" . T::GREEN . ".");
                            return true;
                    }
                }
            }
        }
        return false;
    }

    public function onPlayerEnchanting(PlayerEnchantingOptionsRequestEvent $event) : void
    {
        $player = $event->getPlayer();
        if ($this->getValue() === true){
            if ($this->isWithMessage() === true){
                $player->sendMessage($this->getMessage());
            }
            $event->cancel();
        }
    }

    public function getValue() : bool
    {
        return $this->getConfig()->get("disable-enchantingtable", boolval(false));
    }

    public function getMessage() : string
    {
        return $this->getConfig()->get("message");
    }

    public function isWithMessage() : bool
    {
        return $this->getConfig()->get("use-message", boolval(true));
    }
}