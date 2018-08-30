<?php

namespace Shopsys\ShopBundle\Model\Order;

use Shopsys\FrameworkBundle\Model\Order\OrderFacade as BaseOrderFacade;
use Shopsys\ShopBundle\Model\Customer\BillingAddress;

class OrderFacade extends BaseOrderFacade
{
    /**
     * @param \Shopsys\ShopBundle\Model\Customer\User[] $users
     * @return \Shopsys\FrameworkBundle\Model\Order\Order[]
     */
    public function getOrderListByCustomers(array $users)
    {
        return $this->orderRepository->getOrderListByCustomers($users);
    }

    /**
     * @param string $orderNumber
     * @param \Shopsys\ShopBundle\Model\Customer\BillingAddress $billingAddress
     * @return \Shopsys\FrameworkBundle\Model\Order\Order
     */
    public function getByOrderNumberAndBillingAddress($orderNumber, BillingAddress $billingAddress)
    {
        return $this->orderRepository->getByOrderNumberAndBillingAddress($orderNumber, $billingAddress);
    }
}
