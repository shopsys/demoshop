<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Shopsys\FrameworkBundle\Controller\Admin\AdministratorController as BaseAdministratorController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @property \App\Model\Administrator\AdministratorFacade $administratorFacade
 * @method __construct(\App\Model\Administrator\AdministratorFacade $administratorFacade, \Shopsys\FrameworkBundle\Component\Grid\GridFactory $gridFactory, \Shopsys\FrameworkBundle\Model\AdminNavigation\BreadcrumbOverrider $breadcrumbOverrider, \Shopsys\FrameworkBundle\Model\Administrator\Activity\AdministratorActivityFacade $administratorActivityFacade, \Shopsys\FrameworkBundle\Model\Administrator\AdministratorDataFactoryInterface $administratorDataFactory)
 */
class AdministratorController extends BaseAdministratorController
{
    /**
     * @Route("/administrator/edit/{id}", requirements={"id" = "\d+"})
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $id
     */
    public function editAction(Request $request, $id)
    {
        /** @var \App\Model\Administrator\Administrator $administrator */
        $administrator = $this->administratorFacade->getById($id);

        if ($administrator->isDefaultAdmin()) {
            $message = 'Default admin cannot be edited.';
            throw new AccessDeniedException($message);
        }

        return parent::editAction($request, $id);
    }
}
