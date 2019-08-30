<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Component\Router\DomainRouterFactory;
use Shopsys\FrameworkBundle\Controller\Admin\LoginController as BaseLoginController;
use Shopsys\FrameworkBundle\Model\Security\AdministratorLoginFacade;
use Shopsys\FrameworkBundle\Model\Security\Authenticator;
use Shopsys\FrameworkBundle\Model\Security\Roles;
use Shopsys\ShopBundle\Component\Router\DomainContextSwitcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LoginController extends BaseLoginController
{
    /**
     * @var \Shopsys\ShopBundle\Component\Router\DomainContextSwitcher
     */
    private $domainContextSwitcher;

    /**
     * @param \Shopsys\FrameworkBundle\Model\Security\Authenticator $authenticator
     * @param \Shopsys\FrameworkBundle\Component\Domain\Domain $domain
     * @param \Shopsys\FrameworkBundle\Component\Router\DomainRouterFactory $domainRouterFactory
     * @param \Shopsys\ShopBundle\Component\Router\DomainContextSwitcher $domainContextSwitcher
     * @param \Shopsys\FrameworkBundle\Model\Security\AdministratorLoginFacade $administratorLoginFacade
     */
    public function __construct(
        Authenticator $authenticator,
        Domain $domain,
        DomainRouterFactory $domainRouterFactory,
        DomainContextSwitcher $domainContextSwitcher,
        AdministratorLoginFacade $administratorLoginFacade
    ) {
        parent::__construct($authenticator, $domain, $domainRouterFactory, $administratorLoginFacade);
        $this->domainContextSwitcher = $domainContextSwitcher;
    }

    /**
     * @Route("/", name="admin_login")
     * @Route("/login-check/", name="admin_login_check")
     * @Route("/logout/", name="admin_logout")
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function loginAction(Request $request)
    {
        $currentDomainId = $this->domain->getId();
        if ($currentDomainId !== Domain::MAIN_ADMIN_DOMAIN_ID && !$this->isGranted(Roles::ROLE_ADMIN)) {
            $this->domainContextSwitcher->changeRouterContext(Domain::MAIN_ADMIN_DOMAIN_ID);

            $redirectTo = $this->generateUrl(
                'admin_login_sso',
                [
                    self::ORIGINAL_DOMAIN_ID_PARAMETER_NAME => $currentDomainId,
                    self::ORIGINAL_REFERER_PARAMETER_NAME => $request->server->get('HTTP_REFERER'),
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            return $this->redirect($redirectTo);
        }

        return parent::loginAction($request);
    }

    /**
     * @Route("/sso/{originalDomainId}", requirements={"originalDomainId" = "\d+"})
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $originalDomainId
     */
    public function ssoAction(Request $request, $originalDomainId)
    {
        $administrator = $this->getUser();
        /* @var $administrator \Shopsys\FrameworkBundle\Model\Administrator\Administrator */
        $multidomainToken = $this->administratorLoginFacade->generateMultidomainLoginTokenWithExpiration($administrator);
        $this->domainContextSwitcher->changeRouterContext((int)$originalDomainId);

        $redirectTo = $this->generateUrl(
            'admin_login_authorization',
            [
                self::MULTIDOMAIN_LOGIN_TOKEN_PARAMETER_NAME => $multidomainToken,
                self::ORIGINAL_REFERER_PARAMETER_NAME => $request->get(self::ORIGINAL_REFERER_PARAMETER_NAME),
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return $this->redirect($redirectTo);
    }
}
