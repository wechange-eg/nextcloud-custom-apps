<?php

namespace OCA\WechangeCsp\Listener;

use OCA\WechangeCsp\Model\WechangeCsp;
use OCP\EventDispatcher\IEventListener;
use OCP\Security\CSP\AddContentSecurityPolicyEvent;
use OCP\EventDispatcher\Event;
use OCP\IConfig;

class SetWechangeCspListener implements IEventListener {
    protected $domains;
    
    public function __construct(IConfig $config) {
        $this->domains = $config->getSystemValue('wechange_csp_domains'); 
    }

    public function handle(Event $event): void {
        if (!$event instanceof AddContentSecurityPolicyEvent) {
            return;
        }
        $event->addPolicy(new WechangeCsp($this->domains));
    }
}