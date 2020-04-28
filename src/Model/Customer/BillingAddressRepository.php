<?php

declare(strict_types=1);

namespace App\Model\Customer;

use App\Model\Customer\Exception\BillingAddressNotFoundException;
use Doctrine\ORM\EntityManagerInterface;

class BillingAddressRepository
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $em;

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getBillingAddressRepository()
    {
        return $this->em->getRepository(BillingAddress::class);
    }

    /**
     * @param int $id
     * @return \App\Model\Customer\BillingAddress
     */
    public function getBillingAddressById($id)
    {
        $billingAddress = $this->getBillingAddressRepository()->find($id);

        if ($billingAddress === null) {
            throw new BillingAddressNotFoundException($id);
        }
        return $billingAddress;
    }
}
