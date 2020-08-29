<?php

/**
 * Plugin create by Z€TH£R DEVELOPER
 * Copyright © 2020 - Z€TH£R (@_ZetherDev)
 */

namespace ss\translation;

use ss\Loader;

class Translation {

    public static function getMessage(string $identifier, array $args = []) {
        if (!Loader::getInstance()->messages->exists($identifier)) {
            throw new TranslationException("Invalid identifier: " . $identifier);
        }
        $message = Loader::getInstance()->messages->get($identifier);
        foreach ($args as $arg => $value) {
            $message = str_replace("{" . $arg . "}", $value . "§r", $message);
        }
        return $message;
    }
}