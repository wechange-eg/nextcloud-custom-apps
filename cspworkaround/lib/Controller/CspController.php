<?php
declare(strict_types=1);

namespace OCA\CspWorkaround\Controller;

use OC\AppFramework\Http\Request;
use OC\Core\Controller\CSRFTokenController;
use OC\Security\CSRF\CsrfTokenManager;

use OCA\CspWorkaround\AppInfo\Application;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\ContentSecurityPolicy;
use OCP\AppFramework\Http\JSONResponse;
use OCP\AppFramework\Http\RedirectResponse;
use OCP\AppFramework\Http\Response;
use OCP\IConfig;
use OCP\IRequest;
use OCP\ISession;
use OCP\IUserSession;


class CspController extends Controller {
    private $csrfTokenManager;
    private $config;
    private $userSession;
    private $session;

    public function __construct(
        IRequest $request,
        IConfig $config,
        CSRFTokenManager $csrfTokenManager,
        IUserSession $userSession,
        ISession $session
    ) {
        parent::__construct(Application::APP_NAME, $request);
        $this->csrfTokenManager = $csrfTokenManager;
        $this->config = $config;
        $this->userSession = $userSession;
        $this->session = $session;
        $this->request = $request;
    }

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 * @PublicPage
	 * @return JSONResponse
	 */
    public function getCsrfToken(): JSONResponse {
        
        // we can't inject the CSRFTokenController, so we have to copy paste 

        /* start copy paste from CSRFTokenController */
		if (!$this->request->passesStrictCookieCheck()) {
			return new JSONResponse([], Http::STATUS_FORBIDDEN);
		}

		$requestToken = $this->csrfTokenManager->getToken();

		$response = new JSONResponse([
			'token' => $requestToken->getEncryptedValue(),
		]);
        /* end copy paste */
        
        // this is the line we care about
        $response->setContentSecurityPolicy(new ContentSecurityPolicy());
        return $response;
    }

    /**
	 * @NoAdminRequired
	 * @NoCSRFRequired
     * @UseSession
	 *
     * Copy pasted from LoginController, just with NoCSRFRequired and also
     * takes the redirect URL from a configuration variable wechange_logout_url
	 * @return RedirectResponse
	 */
	public function logout() {
		$loginToken = $this->request->getCookie('nc_token');
		if (!is_null($loginToken)) {
			$this->config->deleteUserValue($this->userSession->getUser()->getUID(), 'login_token', $loginToken);
		}
		$this->userSession->logout();

		$response = new RedirectResponse($this->config->getSystemValue('wechange_logout_redirect_url'));

		$this->session->set('clearingExecutionContexts', '1');
		$this->session->close();

		if (!$this->request->isUserAgent([Request::USER_AGENT_CHROME, Request::USER_AGENT_ANDROID_MOBILE_CHROME])) {
			$response->addHeader('Clear-Site-Data', '"cache", "storage"');
		}

		return $response;
	}

}