<?php

/**
 * Plugin create by Z€TH£R DEVELOPER
 * Copyright © 2020 - Z€TH£R (@_ZetherDev)
 */

namespace ss\sessions;

use pocketmine\Player;
use pocketmine\Server;

class Session {
	
	/** @var string */
	private $player;
	
	/** @var null|string */
	private $staff = null;
	
	/** @var int */
	private $time = 0;
	
	/** @var string */
	private $address;
	
	public function __construct(string $player, string $staff = null) {
		$this->player = $player;
		$this->staff = $staff;
		$this->address = Server::getInstance()->getPlayer($player)->getAddress();
	}
	
	public function getName(int $type = 1) {
		if ($type == 1) return $this->player;
		if ($type == 2) return $this->staff;
		return false;
	}
	
	public function getInstance(int $type = 1) {
		return Server::getInstance()->getPlayer($this->getName($type));
	}
	
	public function isOnline(int $type = 1) {
		return $this->getInstance($type) instanceof Player;
	}
	
	public function setStaff(string $name) {
		$this->staff = $name;
	}
	
	public function getTime() {
		return $this->time;
	}
	
	public function addSecond() {
		$this->time++;
	}
	
	public function getAddress() {
		return $this->address;
	}
}