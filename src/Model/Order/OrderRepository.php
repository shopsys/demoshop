<?php

declare(strict_types=1);

namespace App\Model\Order;

use App\Model\Customer\BillingAddress;
use Shopsys\FrameworkBundle\Model\Order\OrderRepository as BaseOrderRepository;

/**
 * @method \App\Model\Order\Order[] getOrdersByUserId(int $userId)
 * @method \App\Model\Order\Order|null findLastByUserId(int $userId)
 * @method \App\Model\Order\Order|null findById(int $id)
 * @method \App\Model\Order\Order getById(int $id)
 * @method \App\Model\Order\Order[] getCustomerUserOrderList(\App\Model\Customer\User\CustomerUser $customerUser)
 * @method \App\Model\Order\Order[] getOrderListForEmailByDomainId(string $email, int $domainId)
 * @method \App\Model\Order\Order getByUrlHashAndDomain(string $urlHash, int $domainId)
 * @method \App\Model\Order\Order getByOrderNumberAndUser(string $orderNumber, \App\Model\Customer\User\CustomerUser $customerUser)
 * @method \App\Model\Order\Order|null findByUrlHashIncludingDeletedOrders(string $urlHash)
 */
class OrderRepository extends BaseOrderRepository
{
    /**
     * @param \App\Model\Customer\User\CustomerUser[] $customerUsers
     * @return \App\Model\Order\Order[]
     */
    public function getOrderListByCustomers(array $customerUsers)
    {
        return $this->createOrderQueryBuilder()
            ->select('o, oi, os, ost, c')
            ->join('o.items', 'oi')
            ->join('o.status', 'os')
            ->join('os.translations', 'ost')
            ->join('o.currency', 'c')
            ->andWhere('o.customer IN (:customers)')
            ->orderBy('o.createdAt', 'DESC')
            ->setParameter('customers', $customerUsers)
            ->getQuery()->execute();
    }

    /**
     * @param string $orderNumber
     * @param \App\Model\Customer\BillingAddress $billingAddress
     * @return \App\Model\Order\Order
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
