<?php

/**
	* All rights reserved RTGNetworkkk
	* GitHub: https://github.com/RTGNetworkkk
	* Author: InspectorGadget
*/

namespace RTG\Freeze;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\utils\Config;

use pocketmine\Server;
use pocketmine\Player;

use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\Cancellable;

class FreezeEm extends PluginBase implements Listener {
	
	public function onEnable() {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveResource("freeze.txt");
		$this->f = new Config($this->getDataFolder() . "freeze.txt");
		$this->getLogger()->warning("
		* Checking Freezer!
		* Version: 1.0.0, checking for update...
		* All set! I'm ready to freeze...
		");
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, $label, array $param) {
		switch(strtolower($cmd->getName())) {
			
			case "freeze":
				if($sender->hasPermission("freeze.player")) {
					if(isset($param[0])) {
						
						$v = $param[0];
						
						$p = $this->getServer()->getPlayer($v);
						
						if($p === null) {
							$sender->sendMessage("[Freezer] §c$v §fisn't a Player!");
						}
						else {
							$n = $p->getName();
							if($this->f->get($n) === false) {
								$this->f->set($n);
								$this->f->save();
								$sender->sendMessage("[Freezer] §c$v §fhas been Permanently Frozen!");
							}
							else {
								$sender->sendMessage("[Freezer] §e$v §fis already Frozen!");
							}
						}
					}
					else {
						$sender->sendMessage("Usage: /freeze <name>");
					}
				}
				else {
					$sender->sendMessage("§cYou have no permission to use this command!");
				}
				return true;
			break;
			
			case "unfreeze":
				if($sender->hasPermission("unfreeze.player")) {
					if(isset($param[0])) {
						
						$v = $param[0];
						
						if($this->f->get($v) === true) {
							$this->f->remove($v);
							$this->f->save();
							$sender->sendMessage("[Freezer] §e$v §fhas been UnFrozen!");
						}
						else {
							$sender->sendMessage("[Freezer] §b$v §eisnt Frozen!");
						}
					}
					else {
						$sender->sendMessage("Usage: /unfreeze <name>");
					}
				}
				else {
					$sender->sendMessage("§cYou have no permission to use this command!");
				}
				return true;
			break;
		}
	}
	
	public function onMove(PlayerMoveEvent $e) {
		$n = $e->getPlayer()->getName();
		if($this->f->get($n) === true) {
			$e->getPlayer()->sendPopup("§cYou are frozen!");
			$e->setCancelled();
		}
	}
	
}
