<?php

namespace OCA\WechangeCsp\Model;

use OCP\AppFramework\Http\ContentSecurityPolicy;

class WechangeCsp extends ContentSecurityPolicy {
    public function __construct($domains) {
        foreach($domains as $domain) {
            $this->addAllowedScriptDomain($domain);
            $this->addAllowedImageDomain($domain);
            $this->addAllowedConnectDomain($domain);
            $this->addAllowedFormActionDomain($domain);
            $this->addAllowedFrameDomain($domain);
            $this->addAllowedFrameAncestorDomain($domain);
          }
    }
}
