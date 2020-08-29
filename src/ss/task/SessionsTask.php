<?php

/**
 * Plugin create by Z€TH£R DEVELOPER
 * Copyright © 2020 - Z€TH£R (@_ZetherDev)
 */

namespace ss\task;

use pocketmine\scheduler\PluginTask;
use ss\Loader;
use ss\sessions\Session;
use ss\sessions\SessionsManager;

class SessionsTask extends PluginTask {

    public function __construct(Loader $owner) {
        parent::__construct($owner);
    }

    public function onRun($currentTick) {
        foreach (SessionsManager::getInstance()->getSessions() as $session) {
            if ($session instanceof Session) {
                $session->addSecond();
                $this->getScoreboard($session);
            }
        }
    }

    private function getScoreboard(Session $session) {
        $space = str_repeat(" ", 73);
        if ($session->isOnline()) {
            $score = [
                $space . "  " . "§l§9× SCREENSHARE ×§r",
                $space . " ",
                $space . "§9Você foi puxado para screenshare,",
                $space . "§9siga as instruções dada pelo staff",
                $space . "§7-----------------------------------",
                $space . "§9Staff: §f" . ($session->getName(2) == null ? "§cNão atribuído" : "§f" . $session->getName(2) . " §7[" . (!$session->isOnline(2) ? "§cOFFLINE" : "§aONLINE")  . "§7]"),
                $space . "§9Tempo decorrido: §f" . ($session->getTime() < 60 * 60 ? gmdate("i:s", $session->getTime()) : gmdate("H:i:s", $session->getTime())),
            ];
            $session->getInstance()->sendTip(implode("\n", $score));
        }
        if ($session->getName(2) !== null && $session->isOnline(2)) {
            $score = [
                $space . "  " . "§l§9× SCREENSHARE ×§r",
                $space . " ",
                $space . "§9Você iniciou uma screenshare",
                $space . "§9indica os passos a seguir para o jogador",
                $space . "§7-----------------------------------",
                $space . "§9Player: §f" . ($session->getName() . " §7[" . (!$session->isOnline() ? "§cOFFLINE" : "§aONLINE") . " §7]"),
                $space . "§9Tempo decorrido: §f" . ($session->getTime() < 60 * 60 ? gmdate("i:s", $session->getTime()) : gmdate("H:i:s", $session->getTime())),
            ];
            $session->getInstance(2)->sendTip(implode("\n", $score));
        }
    }
}