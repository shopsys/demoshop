<?php

namespace Shopsys\ShopBundle\Model\Order;

use Shopsys\FrameworkBundle\Model\Order\OrderRepository as BaseOrderRepository;
use Shopsys\ShopBundle\Model\Customer\BillingAddress;

class OrderRepository extends BaseOrderRepository
{
    /**
     * @param \Shopsys\ShopBundle\Model\Customer\User[] $users
     * @return \Shopsys\FrameworkBundle\Model\Order\Order[]
     */
    public function getOrderListByCustomers(array $users)
    {
        return $this->createOrderQueryBuilder()
            ->select('o, oi, os, ost, c')
            ->join('o.items', 'oi')
            ->join('o.status', 'os')
            ->join('os.translations', 'ost')
            ->join('o.currency', 'c')
            ->andWhere('o.customer IN (:customers)')
            ->orderBy('o.createdAt', 'DESC')
            ->setParameter('customers', $users)
            ->getQuery()->execute();
    }

    /**
     * @param string $orderNumber
     * @param \Shopsys\ShopBundle\Model\Customer\BillingAddress $billingAddress
     * @return \Shopsys\FrameworkBundle\Model\Order\Order
     */
    public function getByOrderNumberAndBillingAddress($orderNumber, BillingAddress $billingAddress)
    {
        $order = $this->createOrderQueryBuilder()
            ->join('o.customer', 'u')
            ->andWhere('o.number = :number')
            ->andWhere('u.billingAddress = :billingAddress')
            ->setParameter(':number', $orderNumber)
            ->setParameter(':billingAddress', $billingAddress)
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();

        if ($order === null) {
            $message = 'Order with number "' . $orderNumber . '" and billingAddressId "' . $billingAddress->getId() . '" not found.';
            throw new \Shopsys\FrameworkBundle\Model\Order\Exception\OrderNotFoundException($message);
        }

        return $order;
    }
}
