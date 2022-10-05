<?php

declare(strict_types=1);

namespace OCA\WechangeCsp\AppInfo;

use Closure;
use OC\AppFramework\Middleware\MiddlewareDispatcher;

use OCA\WechangeCsp\Middleware\SetWechangeCspWhenEmptyMiddleware;
use OCA\WechangeCsp\Listener\SetWechangeCspListener;

use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\Security\CSP\AddContentSecurityPolicyEvent;
use OCP\IServerContainer;
use OCP\IConfig;

class Application extends App implements IBootstrap {

    public function __construct() {
        parent::__construct('wechangecsp');
    }

    public function register(IRegistrationContext $context): void {
        $context->registerEventListener(AddContentSecurityPolicyEvent::class, SetWechangeCspListener::class);
    }

    public function boot(IBootContext $context): void {}

}
