<?php
/**
 * Created by IntelliJ IDEA.
 * User: dw01f
 * Date: 25.09.18
 * Time: 12:37
 */

namespace Engine\lib;


trait SingletonTrait
{

    private static $singleton = false;

    public function __construct() {
        $this->instance();
    }

    public static function getInstance() {
        if (self::$singleton === false) {
            self::$singleton = new self();
        }

        return self::$singleton;
    }

    abstract public function instance();
}