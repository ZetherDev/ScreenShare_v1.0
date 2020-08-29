<?php

/**
 * Plugin create by Z€TH£R DEVELOPER
 * Copyright © 2020 - Z€TH£R (@_ZetherDev)
 */

namespace ss;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener as ZetherDeveloper;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Player;
use pocketmine\Server;
use ss\network\Location;
use ss\sessions\Session;
use ss\sessions\SessionsManager;
use ss\translation\Translation;
use ss\translation\TranslationException;

class EventListener implements ZetherDeveloper {
	
	public function __construct(Loader $plugin) {
		Server::getInstance()->getPluginManager()->registerEvents($this, $plugin);
	}

	public function onBreak(BlockBreakEvent $event) {
	    $player = $event->getPlayer();
	    $name = $player->getName();

	    if (SessionsManager::getInstance()->getSession($name)) {
	        $event->setCancelled();
        }
    }

    public function onPlace(BlockPlaceEvent $event) {
	    $player = $event->getPlayer();
	    $name = $player->getName();

	    if (SessionsManager::getInstance()->getSession($name)) {
	        $event->setCancelled();
        }
    }

    public function onDamage(EntityDamageEvent $event) {
	    $entity = $event->getEntity();

	    if ($entity instanceof Player) {
	        $name = $entity->getName();
	        if (SessionsManager::getInstance()->getSession($name)) {
	            $event->setCancelled();
            }
        }

	    if ($event instanceof EntityDamageByEntityEvent) {
	        $damager = $event->getDamager();

	        if ($damager instanceof Player) {
	            $name = $damager->getName();
                if (SessionsManager::getInstance()->getSession($name)) {
                    $event->setCancelled();
                }
            }
        }
    }

    public function onChat(PlayerChatEvent $event) {
	    $message = $event->getMessage();
	    $player = $event->getPlayer();
	    $name = $player->getName();

	    /** Player Chat */
        if (($session = SessionsManager::getInstance()->getSession($name))) {
            $event->setCancelled();
            try {
                $player->sendMessage(Translation::getMessage("format-chat-message", ["player" => $name, "message" => $message]));
            } catch (TranslationException $e) {
                echo $e->getMessage();
            }


            if ($session instanceof Session && $session->isOnline(2)) {
                try {
                    $session->getInstance(2)->sendMessage(Translation::getMessage("format-chat-message", ["player" => $name, "message" => $message]));
                } catch (TranslationException $e) {
                    echo $e->getMessage();
                }
            }
            return;
        }

        /** Staff Chat */
        foreach (SessionsManager::getInstance()->getSessions() as $session) {
            if ($session instanceof Session) {
                if ($session->getName(2) == $name) {
                    $event->setCancelled();
                    
                    /** Commands */
                    if ((explode(" ", $message))[0] == "!data") {
                    	$player->sendMessage("§9Informações sobre o jogador");
                    	$player->sendMessage(" §9+ País: §f" . Location::getCountry($session->getAddress()));
                    	$player->sendMessage(" §9+ Cidade: §f" . Location::getCity($session->getAddress()));
                    	return;
                    }
                    
                    if ((explode(" ", $message))[0] == "!tphere") {
                    	if ($session->isOnline()) {
                    		$session->getInstance()->teleport($player->getPosition());
                    	}
                    	return;
                    }
                    
                    try {
                        $player->sendMessage(Translation::getMessage("format-chat-message", ["player" => $session->getName(2), "message" => $message]));
                    } catch (TranslationException $e) {
                        echo $e->getMessage();
                    }

                    if ($session->isOnline()) {
                        try {
                            $session->getInstance()->sendMessage(Translation::getMessage("format-chat-message", ["player" => $session->getName(2), "message" => $message]));
                        } catch (TranslationException $e) {
                            echo $e->getMessage();
                        }
                    }
                    return;
                }
            }
        }
    }

    public function onCommandPreprocess(PlayerCommandPreprocessEvent $event) {
	    $message = $event->getMessage();
	    $player = $event->getPlayer();
	    $name = $player->getName();

	    if (SessionsManager::getInstance()->getSession($name)) {
	        if ($message{0} == '/') {
	            $event->setCancelled();
            }
        }
    }
    public function onDropItem(PlayerDropItemEvent $event) {
        $player = $event->getPlayer();
        $name = $player->getName();

        if (SessionsManager::getInstance()->getSession($name)) {
            $event->setCancelled();
        }
    }

    public function onItemConsume(PlayerItemConsumeEvent $event) {
        $player = $event->getPlayer();
        $name = $player->getName();

        if (SessionsManager::getInstance()->getSession($name)) {
            $event->setCancelled();
        }
    }

    public function onMove(PlayerMoveEvent $event) {
        $player = $event->getPlayer();
        $name = $player->getName();

        if (SessionsManager::getInstance()->getSession($name)) {
        	$to = clone $event->getFrom();
 	       $to->yaw = $event->getTo()->getYaw();
  	      $to->pitch = $event->getTo()->getPitch();
   	     $event->setTo($to);
        }
    }

    public function onQuit(PlayerQuitEvent $event) {
	    $player = $event->getPlayer();
	    $name = $player->getName();

	    if (SessionsManager::getInstance()->getSession($name)) {
	        SessionsManager::getInstance()->removeSession($name);
        }
    }
}