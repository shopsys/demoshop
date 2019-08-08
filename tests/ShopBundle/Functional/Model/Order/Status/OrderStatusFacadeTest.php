<?php

declare(strict_types=1);

namespace Tests\ShopBundle\Functional\Model\Order\Status;

use Shopsys\FrameworkBundle\Model\Order\OrderDataFactoryInterface;
use Shopsys\FrameworkBundle\Model\Order\OrderFacade;
use Shopsys\FrameworkBundle\Model\Order\Status\OrderStatusData;
use Shopsys\FrameworkBundle\Model\Order\Status\OrderStatusFacade;
use Shopsys\ShopBundle\DataFixtures\Demo\OrderDataFixture;
use Shopsys\ShopBundle\DataFixtures\Demo\OrderStatusDataFixture;
use Tests\ShopBundle\Test\TransactionFunctionalTestCase;

class OrderStatusFacadeTest extends TransactionFunctionalTestCase
{
    public function testDeleteByIdAndReplace()
    {
        $em = $this->getEntityManager();
        $orderStatusFacade = $this->getContainer()->get(OrderStatusFacade::class);
        /* @var $orderStatusFacade \Shopsys\FrameworkBundle\Model\Order\Status\OrderStatusFacade */
        $orderFacade = $this->getContainer()->get(OrderFacade::class);
        /* @var $orderFacade \Shopsys\FrameworkBundle\Model\Order\OrderFacade */

        $orderStatusData = new OrderStatusData();
        $orderStatusData->name = ['cs' => 'name'];
        $orderStatusToDelete = $orderStatusFacade->create($orderStatusData);
        $orderStatusToReplaceWith = $this->getReference(OrderStatusDataFixture::ORDER_STATUS_NEW);
        /* @var $orderStatusToReplaceWith \Shopsys\FrameworkBundle\Model\Order\Status\OrderStatus */
        $order = $this->getReference(OrderDataFixture::ORDER_PREFIX . '1');
        /* @var $order \Shopsys\FrameworkBundle\Model\Order\Order */
        $orderDataFactory = $this->getContainer()->get(OrderDataFactoryInterface::class);
        /* @var $orderDataFactory \Shopsys\FrameworkBundle\Model\Order\OrderDataFactory */

        $orderData = $orderDataFactory->createFromOrder($order);
        $orderData->status = $orderStatusToDelete;
        $orderFacade->edit($order->getId(), $orderData);

        $orderStatusFacade->deleteById($orderStatusToDelete->getId(), $orderStatusToReplaceWith->getId());

        $em->refresh($order);

        $this->assertEquals($orderStatusToReplaceWith, $order->getStatus());
    }
}
