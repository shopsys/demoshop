<?php

declare(strict_types=1);

namespace Tests\App\Functional\Model\Order\Status;

use Shopsys\FrameworkBundle\Model\Order\Status\OrderStatusData;
use App\DataFixtures\Demo\OrderDataFixture;
use App\DataFixtures\Demo\OrderStatusDataFixture;
use Tests\App\Test\TransactionFunctionalTestCase;

class OrderStatusFacadeTest extends TransactionFunctionalTestCase
{
    /**
     * @var \Shopsys\FrameworkBundle\Model\Order\Status\OrderStatusFacade
     * @inject
     */
    private $orderStatusFacade;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Order\OrderFacade
     * @inject
     */
    private $orderFacade;

    /**
     * @var \App\Model\Order\OrderDataFactory
     * @inject
     */
    private $orderDataFactory;

    public function testDeleteByIdAndReplace()
    {
        $em = $this->getEntityManager();

        $orderStatusData = new OrderStatusData();
        $orderStatusData->name = ['cs' => 'name'];
        $orderStatusToDelete = $this->orderStatusFacade->create($orderStatusData);
        $orderStatusToReplaceWith = $this->getReference(OrderStatusDataFixture::ORDER_STATUS_NEW);
        /* @var $orderStatusToReplaceWith \Shopsys\FrameworkBundle\Model\Order\Status\OrderStatus */
        $order = $this->getReference(OrderDataFixture::ORDER_PREFIX . '1');
        /* @var $order \Shopsys\FrameworkBundle\Model\Order\Order */

        $orderData = $this->orderDataFactory->createFromOrder($order);
        $orderData->status = $orderStatusToDelete;
        $this->orderFacade->edit($order->getId(), $orderData);

        $this->orderStatusFacade->deleteById($orderStatusToDelete->getId(), $orderStatusToReplaceWith->getId());

        $em->refresh($order);

        $this->assertEquals($orderStatusToReplaceWith, $order->getStatus());
    }
}
