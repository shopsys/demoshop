<?php

namespace Shopsys\ShopBundle\Model\Order;

use Shopsys\FrameworkBundle\Model\Order\OrderRepository as BaseOrderRepository;

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
}
