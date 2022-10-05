<?php

namespace OCA\CspWorkaround\AppInfo;

use OCP\AppFramework\App;

class Application extends App {

    public const APP_NAME = 'cspworkaround';

    public function __construct() {
        parent::__construct(Application::APP_NAME);
    }
}