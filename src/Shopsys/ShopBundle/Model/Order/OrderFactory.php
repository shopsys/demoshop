<?php

namespace Shopsys\ShopBundle\Model\Order;

use Shopsys\FrameworkBundle\Model\Customer\User;
use Shopsys\FrameworkBundle\Model\Order\Order as BaseOrder;
use Shopsys\FrameworkBundle\Model\Order\OrderData as BaseOrderData;
use Shopsys\FrameworkBundle\Model\Order\OrderFactoryInterface;

class OrderFactory implements OrderFactoryInterface
{
    /**
     * @param \Shopsys\ShopBundle\Model\Order\OrderData $orderData
     * @param string $orderNumber
     * @param string $urlHash
     * @param \Shopsys\FrameworkBundle\Model\Customer\User|null $user
     * @return \Shopsys\ShopBundle\Model\Order\Order
     */
    public function create(
        BaseOrderData $orderData,
        string $orderNumber,
        string $urlHash,
        ?User $user
    ): BaseOrder {
        return new Order($orderData, $orderNumber, $urlHash, $user);
    }
}
