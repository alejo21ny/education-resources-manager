<?php

namespace ERM\Core;

if (!defined('ABSPATH')) {
    exit;
}

class Activator {

    public static function activate() {
        
    ResourcesTable::create_table();

    }
}