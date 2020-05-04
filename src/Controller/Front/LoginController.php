<?php

declare(strict_types=1);

namespace App\Controller\Front;

use App\Form\Front\Login\LoginFormType;
use Shopsys\FrameworkBundle\Model\Security\Authenticator;
use Shopsys\FrameworkBundle\Model\Security\Roles;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

class LoginController extends FrontBaseController
{
    /**
     * @var \Shopsys\FrameworkBundle\Model\Security\Authenticator
     */
    private $authenticator;

    /**
     * @param \Shopsys\FrameworkBundle\Model\Security\Authenticator $authenticator
     */
    public function __construct(Authenticator $authenticator)
    {
        $this->authenticator = $authenticator;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function loginAction(Request $request)
    {
        if ($this->isGranted(Roles::ROLE_LOGGED_CUSTOMER)) {
            return $this->redirectToRoute('front_homepage');
        }

        $form = $this->getLoginForm();

        try {
            $this->authenticator->checkLoginProcess($request);
        } catch (\Shopsys\FrameworkBundle\Model\Security\Exception\LoginFailedException $e) {
            $form->addError(new FormError(t('Invalid login')));
        }

        return $this->render('@ShopsysShop/Front/Content/Login/loginForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function windowFormAction()
    {
        return $this->render('@ShopsysShop/Front/Content/Login/windowForm.html.twig', [
            'form' => $this->getLoginForm()->createView(),
        ]);
    }

    /**
     * @return \Symfony\Component\Form\Form
     */
    private function getLoginForm()
    {
        return $this->createForm(LoginFormType::class, null, [
            'action' => $this->generateUrl('front_login_check'),
        ]);
    }
}
