<?php

declare(strict_types=1);

if (!isset($_SERVER) || !array_key_exists('HTTP_HOST', $_SERVER)) {
    return;
}

$_SERVER['MAGE_RUN_TYPE'] = 'store';

switch ($_SERVER['HTTP_HOST']) {
    case '3dprintentexel.test':
    case '3dprintentexel.nl':
    case 'www.3dprintentexel.nl':
        $_SERVER['MAGE_RUN_CODE'] = 'threedprintentexel_dutch';
        break;
    default:
        $_SERVER['MAGE_RUN_CODE'] = 'default';
        break;
}
