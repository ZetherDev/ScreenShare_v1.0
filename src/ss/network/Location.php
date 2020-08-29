<?php

/**
 * Plugin create by Z€TH£R DEVELOPER
 * Copyright © 2020 - Z€TH£R (@_ZetherDev)
 */
 
namespace ss\network;

class Location {
	
	private static function getData(string $ip) {
		return @unserialize(file_get_contents('http://ip-api.com/php/'.$ip, $ip));
	}
	
	public static function getCountry(string $ip) {
		$get = self::getData($ip);
		return $get['country'];
	}
	
	public static function getCity(string $ip) {
		$get = self::getData($ip);
		return $get['city'];
	}
}