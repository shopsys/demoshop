<?php

declare(strict_types=1);

namespace App\Model\Customer;

use Doctrine\ORM\EntityManagerInterface;

class BillingAddressFacade
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $em;

    /**
     * @var \App\Model\Customer\BillingAddressRepository
     */
    protected $billingAddressRepository;

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $em
     * @param \App\Model\Customer\BillingAddressRepository $billingAddressRepository
     */
    public function __construct(
        EntityManagerInterface $em,
        BillingAddressRepository $billingAddressRepository
    ) {
        $this->em = $em;
        $this->billingAddressRepository = $billingAddressRepository;
    }

    /**
     * @param int $id
     * @return \App\Model\Customer\BillingAddress
     */
    public function getById($id)
    {
        return $this->billingAddressRepository->getBillingAddressById($id);
    }

    /**
     * @param int $billingAddressId
     * @param \App\Model\Customer\BillingAddressData $billingAddressData
     * @return \App\Model\Customer\BillingAddress
     */
    public function edit($billingAddressId, BillingAddressData $billingAddressData)
    {
        $billingAddress = $this->getById($billingAddressId);

        $billingAddress->edit($billingAddressData);

        $this->em->persist($billingAddress);
        $this->em->flush($billingAddress);
        return $billingAddress;
    }
}
