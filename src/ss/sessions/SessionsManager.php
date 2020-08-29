<?php

/**
 * Plugin create by Z€TH£R DEVELOPER
 * Copyright © 2020 - Z€TH£R (@_ZetherDev)
 */

namespace ss\sessions;

use pocketmine\Server;
use ss\Loader;

class SessionsManager {
	
	/** @var SessionsManager */
	private static $instance;
	
	/** @var array */
	private $sessions = [];
	
	public function __construct() {
		self::$instance = $this;
	}

    public static function getInstance() {
        return self::$instance;
    }
	
	public function getSessions() {
		return $this->sessions;
	}

	public function getSession(string $name) {
	    if (isset($this->sessions[$name])) {
	        return $this->sessions[$name];
        }
	    return false;
    }

    public function createSession(string $player, string $staff = null) {
	    $this->sessions[$player] = new Session($player, $staff);
    }

    public function removeSession(string $player) {
	    unset($this->sessions[$player]);
    }
}