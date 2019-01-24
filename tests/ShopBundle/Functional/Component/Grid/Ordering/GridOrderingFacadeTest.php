<?php

namespace Tests\ShopBundle\Functional\Component\Grid\Ordering;

use Shopsys\FrameworkBundle\Component\Grid\Ordering\GridOrderingFacade;
use Tests\ShopBundle\Test\TransactionFunctionalTestCase;

class GridOrderingFacadeTest extends TransactionFunctionalTestCase
{
    public function testSetPositionWrongEntity()
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $gridOrderingFacade = new GridOrderingFacade($em);
        $entity = new \StdClass();
        $this->expectException(\Shopsys\FrameworkBundle\Component\Grid\Ordering\Exception\EntityIsNotOrderableException::class);
        $gridOrderingFacade->saveOrdering($entity, []);
    }
}
