<?php

if (!defined('SEJMOMETR_ROOT_DIR')) {
  define('SEJMOMETR_ROOT_DIR', realpath(dirname(__FILE__) . '/../'));
}

if (!defined('SEJMOMETR_SOURCE')) {
  define('SEJMOMETR_SOURCE', realpath(SEJMOMETR_ROOT_DIR) . '/src');
}

require_once SEJMOMETR_SOURCE .'/autoload.inc.php';
require_once 'PHPUnit.php';
