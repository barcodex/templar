<?php
/**
 * Created by PhpStorm.
 * User: anvarzonzurajev
 * Date: 3/24/15
 * Time: 12:33 PM
 */

namespace Templar;


class Locator
{
    static $instance = null;

    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new Locator();
        }

        return self::$instance;
    }

    public function bark() {
        print 'auh auh' . PHP_EOL;
    }
} 