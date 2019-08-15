<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Shopsys\FrameworkBundle\Controller\Admin\AdministratorController as BaseAdministratorController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AdministratorController extends BaseAdministratorController
{
    /**
     * @Route("/administrator/edit/{id}", requirements={"id" = "\d+"})
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $id
     */
    public function editAction(Request $request, $id)
    {
        /** @var \Shopsys\ShopBundle\Model\Administrator\Administrator $administrator */
        $administrator = $this->administratorFacade->getById($id);

        if ($administrator->isDefaultAdmin()) {
            $message = 'Default admin cannot be edited.';
            throw new AccessDeniedException($message);
        }

        return parent::editAction($request, $id);
    }
}
