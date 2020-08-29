<?php

/**
 * Plugin create by Z€TH£R DEVELOPER
 * Copyright © 2020 - Z€TH£R (@_ZetherDev)
 */

namespace ss;

use pocketmine\plugin\PluginBase as ScreenShare;
use pocketmine\utils\Config;
use ss\commands\SessionCommand;
use ss\sessions\SessionsManager;
use ss\task\SessionsTask;

class Loader extends ScreenShare {
	
	/** @var Loader */
	private static $instance;
	
	/** @var Config */
	public $messages;
	
	public function onLoad() {
		self::$instance = $this;
	}
	
	public function onEnable() {
		// Logger messages
		$this->getLogger()->info("§aPlugin created by Z € T H £ R");
		$this->getLogger()->info("§a Follow me on Twitter @_ZetherDev");
		
		// Creation of the configuration files and folder
		@mkdir($this->getDataFolder());
		$this->saveResource("config.yml");
		$this->saveResource("messages.yml");
		
		// Saving the settings in variables
		$this->messages = new Config($this->getDataFolder() . 'messages.yml', Config::YAML);
		
		// Manager registration
		new SessionsManager();
		
		// Event registration
		new EventListener($this);
		
		// Commands registration
		$this->getServer()->getCommandMap()->register("ScreenShare", new SessionCommand());
		
		// Task registration
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new SessionsTask($this), 25);
	}
	
	public static function getInstance() {
		return self::$instance;
	}
}
