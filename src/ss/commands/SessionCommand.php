<?php

/**
 * Plugin create by Z€TH£R DEVELOPER
 * Copyright © 2020 - Z€TH£R (@_ZetherDev)
 */

namespace ss\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use ss\sessions\Session;
use ss\sessions\SessionsManager;
use ss\translation\Translation;

class SessionCommand extends Command {

    public function __construct() {
        parent::__construct("screenshare", "", null, ['ss']);
    }

    public function execute(CommandSender $sender, $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            return;
        }

        if (!$sender->hasPermission("screenshare.command.permission")) {
        	$sender->sendMessage(Translation::getMessage("not-permission-message"));
            return;
        }

        if (!isset($args[0])) {
        	$sender->sendMessage(Translation::getMessage("use-command-message"));
            return;
        }
        $player = Server::getInstance()->getPlayer($args[0]);
        
        if (!($player instanceof Player)) {
        	$sender->sendMessage(Translation::getMessage("jogador-not-exists-message", ["player" => $args[0]]));
        	return;
        }
        
        if (($session = SessionsManager::getInstance()->getSession($player->getName()))) {
        	if ($session->getName(2) != $sender->getName()) {
        		if (!$sender->hasPermission("screenshare.destroy.session.permission")) {
        			$sender->sendMessage(Translation::getMessage("jogador-exists-session", ["player" => $player->getName()]));
        			return;
        		}
        	}
        	$player->teleport(Server::getInstance()->getDefaultLevel()->getSafeSpawn());
        	SessionsManager::getInstance()->removeSession($player->getName());
        	Server::getInstance()->broadcastMessage(Translation::getMessage("session-destroy-message", ["player" => $player->getName()]));
        	return;
        }
        SessionsManager::getInstance()->createSession($player->getName(), $sender->getName());
        Server::getInstance()->broadcastMessage(Translation::getMessage("session-create-message", ["player" => $player->getName()]));
    }
}