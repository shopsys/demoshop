<?php

namespace Shopsys\ShopBundle\Model\Customer;

use Doctrine\ORM\EntityManagerInterface;
use Shopsys\FrameworkBundle\Model\Customer\BillingAddress;
use Shopsys\FrameworkBundle\Model\Customer\BillingAddressDataFactoryInterface;
use Shopsys\FrameworkBundle\Model\Customer\BillingAddressFactoryInterface;
use Shopsys\FrameworkBundle\Model\Customer\CustomerDataFactoryInterface;
use Shopsys\FrameworkBundle\Model\Customer\CustomerFacade as BaseCustomerFacade;
use Shopsys\FrameworkBundle\Model\Customer\CustomerService;
use Shopsys\FrameworkBundle\Model\Customer\Mail\CustomerMailFacade;
use Shopsys\FrameworkBundle\Model\Customer\UserFactoryInterface;

class CustomerFacade extends BaseCustomerFacade
{
    /**
     * @var \Shopsys\FrameworkBundle\Model\Customer\CustomerDataFactoryInterface
     */
    protected $customerDataFactory;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Customer\UserFactoryInterface
     */
    protected $userFactory;

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $em
     * @param \Shopsys\FrameworkBundle\Model\Customer\UserRepository $userRepository
     * @param \Shopsys\FrameworkBundle\Model\Customer\CustomerService $customerService
     * @param \Shopsys\FrameworkBundle\Model\Customer\Mail\CustomerMailFacade $customerMailFacade
     * @param \Shopsys\FrameworkBundle\Model\Customer\BillingAddressFactoryInterface $billingAddressFactory
     * @param \Shopsys\FrameworkBundle\Model\Customer\BillingAddressDataFactoryInterface $billingAddressDataFactory
     */
    public function __construct(
        EntityManagerInterface $em,
        UserRepository $userRepository,
        CustomerService $customerService,
        CustomerMailFacade $customerMailFacade,
        BillingAddressFactoryInterface $billingAddressFactory,
        BillingAddressDataFactoryInterface $billingAddressDataFactory,
        CustomerDataFactoryInterface $customerDataFactory,
        UserFactoryInterface $userFactory
    ) {
        $this->customerDataFactory = $customerDataFactory;
        $this->userFactory = $userFactory;
        parent::__construct($em, $userRepository, $customerService, $customerMailFacade, $billingAddressFactory, $billingAddressDataFactory, $customerDataFactory);
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Customer\BillingAddress $billingAddress
     * @param int $domainId
     * @return \Shopsys\ShopBundle\Model\Customer\User[]
     */
    public function getUsersByBillingAddressAndDomain(BillingAddress $billingAddress, int $domainId)
    {
        return $this->userRepository->getUsersByBillingAddressAndDomain($billingAddress, $domainId);
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Customer\BillingAddress $billingAddress
     * @return array
     */
    public function getAllByBillingAddress(BillingAddress $billingAddress)
    {
        return $this->userRepository->getAllByBillingAddress($billingAddress);
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Customer\BillingAddress $billingAddress
     * @return \Shopsys\ShopBundle\Model\Customer\User
     */
    public function getUserByBillingAddress(BillingAddress $billingAddress)
    {
        return $this->userRepository->getUserByBillingAddress($billingAddress);
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Customer\BillingAddress $billingAddress
     * @param \Shopsys\ShopBundle\Model\Customer\CustomerData $companyData
     */
    public function editCompanyWithMultipleUsers(BillingAddress $billingAddress, CustomerData $companyData)
    {
        $newCompanyUsersData = [];
        foreach ($companyData->companyUsersData as $companyUserData) {
            $companyDataOfCompanyUser = clone $companyData;
            $companyDataOfCompanyUser->userData = $companyUserData;

            if ($companyUserData->id > 0) {
                $this->editByAdmin($companyUserData->id, $companyDataOfCompanyUser);
            } else {
                $newCompanyUsersData[] = $companyUserData;
            }
        }

        $this->removeOldCompanyUsers($billingAddress, $companyData);
        $this->createNewCompanyUsers($companyData, $billingAddress, $newCompanyUsersData);
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Customer\BillingAddress $billingAddress
     * @param \Shopsys\ShopBundle\Model\Customer\CustomerData $companyData
     */
    protected function removeOldCompanyUsers(BillingAddress $billingAddress, CustomerData $companyData)
    {
        $oldCompanyUsers = $this->userRepository->getAllByBillingAddress($billingAddress);

        foreach ($oldCompanyUsers as $oldCompanyUser) {
            $this->removeOldCompanyUserIfNotFound($oldCompanyUser, $companyData);
        }
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Customer\User $companyUser
     * @param \Shopsys\ShopBundle\Model\Customer\CustomerData $newCompanyData
     */
    protected function removeOldCompanyUserIfNotFound(User $companyUser, CustomerData $newCompanyData)
    {
        $oldCompanyUserIsNotFoundInNewCompanyData = true;
        foreach ($newCompanyData->companyUsersData as $companyUserData) {
            if ($companyUserData->id === $companyUser->getId()) {
                $oldCompanyUserIsNotFoundInNewCompanyData = false;
            }
        }

        if ($oldCompanyUserIsNotFoundInNewCompanyData) {
            $this->em->remove($companyUser);
            $this->em->flush($companyUser);
        }
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Customer\CustomerData $companyData
     * @param \Shopsys\ShopBundle\Model\Customer\BillingAddress $billingAddress
     * @param \Shopsys\ShopBundle\Model\Customer\UserData[] $newCompanyUsersData
     */
    protected function createNewCompanyUsers(CustomerData $companyData, BillingAddress $billingAddress, array $newCompanyUsersData)
    {
        $toFlush = [];
        foreach ($newCompanyUsersData as $newCompanyUserData) {
            $newCompanyUserData->pricingGroup = $companyData->userData->pricingGroup;
            $newCompanyUserData->domainId = $companyData->userData->domainId;

            $user = $this->customerService->create($newCompanyUserData, $billingAddress);
            $this->em->persist($user);

            $toFlush[] = $user;
        }

        if (!empty($toFlush)) {
            $this->em->flush($toFlush);
        }
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Customer\BillingAddress $billingAddress
     */
    public function removeBillingAddress(BillingAddress $billingAddress)
    {
        $users = $this->getAllByBillingAddress($billingAddress);

        $toFlush = [];
        foreach ($users as $user) {
            $this->em->remove($user);
            $toFlush[] = $user;
        }

        $this->em->remove($billingAddress);
        $toFlush[] = $billingAddress;

        $this->em->flush($toFlush);
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Customer\BillingAddress $billingAddress
     * @param \Shopsys\ShopBundle\Model\Customer\CustomerData $companyData
     */
    public function removeCompanyUsersExceptTheFirstOne(BillingAddress $billingAddress, CustomerData $companyData)
    {
        $companyUsersDataWithOneUser = reset($companyData->companyUsersData);
        $companyData->companyUsersData = [$companyUsersDataWithOneUser];
        $this->removeOldCompanyUsers($billingAddress, $companyData);
    }
}
