<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Customer;

use Doctrine\ORM\EntityManagerInterface;
use Shopsys\FrameworkBundle\Model\Customer\BillingAddress;
use Shopsys\FrameworkBundle\Model\Customer\BillingAddressDataFactoryInterface;
use Shopsys\FrameworkBundle\Model\Customer\BillingAddressFactoryInterface;
use Shopsys\FrameworkBundle\Model\Customer\CustomerDataFactoryInterface;
use Shopsys\FrameworkBundle\Model\Customer\CustomerFacade as BaseCustomerFacade;
use Shopsys\FrameworkBundle\Model\Customer\CustomerPasswordFacade;
use Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressFactoryInterface;
use Shopsys\FrameworkBundle\Model\Customer\Mail\CustomerMailFacade;
use Shopsys\FrameworkBundle\Model\Customer\UserFactoryInterface;
use Shopsys\ShopBundle\Model\Customer\Exception\DuplicateEmailsException;

/**
 * @property \Shopsys\ShopBundle\Model\Customer\UserRepository $userRepository
 */
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
     * @param \Shopsys\ShopBundle\Model\Customer\UserRepository $userRepository
     * @param \Shopsys\FrameworkBundle\Model\Customer\CustomerDataFactoryInterface $customerDataFactory
     * @param \Shopsys\FrameworkBundle\Model\Customer\Mail\CustomerMailFacade $customerMailFacade
     * @param \Shopsys\FrameworkBundle\Model\Customer\BillingAddressFactoryInterface $billingAddressFactory
     * @param \Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressFactoryInterface $deliveryAddressFactory
     * @param \Shopsys\FrameworkBundle\Model\Customer\BillingAddressDataFactoryInterface $billingAddressDataFactory
     * @param \Shopsys\FrameworkBundle\Model\Customer\UserFactoryInterface $userFactory
     * @param \Shopsys\FrameworkBundle\Model\Customer\CustomerPasswordFacade $customerPasswordFacade
     */
    public function __construct(
        EntityManagerInterface $em,
        UserRepository $userRepository,
        CustomerDataFactoryInterface $customerDataFactory,
        CustomerMailFacade $customerMailFacade,
        BillingAddressFactoryInterface $billingAddressFactory,
        DeliveryAddressFactoryInterface $deliveryAddressFactory,
        BillingAddressDataFactoryInterface $billingAddressDataFactory,
        UserFactoryInterface $userFactory,
        CustomerPasswordFacade $customerPasswordFacade
    ) {
        parent::__construct($em, $userRepository, $customerDataFactory, $customerMailFacade, $billingAddressFactory, $deliveryAddressFactory, $billingAddressDataFactory, $userFactory, $customerPasswordFacade);
        $this->customerDataFactory = $customerDataFactory;
        $this->userFactory = $userFactory;
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
        $this->processUniqueEmailsCheck($billingAddress, $companyData);

        $newCompanyUsersData = [];
        foreach ($companyData->companyUsersData as $companyUserData) {
            /**
             * For every user of company there will be only available base user data from the form
             * But every customer needs to have billing address data too. So for every user of company there is taken the
             * billing address from company data.
             */
            $companyDataOfCompanyUser = clone $companyData;
            $companyDataOfCompanyUser->userData = $companyUserData;
            $companyDataOfCompanyUser->userData->discount = $companyData->userData->discount;

            /**
             * When companyUserData has set an ID, it means this is an modified existing entity.
             * When companyUserData has not set an ID, it means this is a new company customer that needs to be saved
             */
            if ($companyUserData->id !== null) {
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

            $user = $this->userFactory->create(
                $newCompanyUserData,
                $billingAddress,
                null,
                null
            );
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

    /**
     * @param \Shopsys\FrameworkBundle\Model\Customer\BillingAddress $billingAddress
     * @param \Shopsys\ShopBundle\Model\Customer\CustomerData $companyData
     * @throws \Shopsys\ShopBundle\Model\Customer\Exception\DuplicateEmailsException
     */
    protected function processUniqueEmailsCheck(BillingAddress $billingAddress, CustomerData $companyData)
    {
        $emailsOfCompanyCustomers = [];
        foreach ($companyData->companyUsersData as $companyUser) {
            $emailsOfCompanyCustomers[] = $companyUser->email;
        }

        $duplicatedEmails = array_diff_assoc($emailsOfCompanyCustomers, array_unique($emailsOfCompanyCustomers));

        if (count($duplicatedEmails) > 0) {
            throw new DuplicateEmailsException(reset($duplicatedEmails));
        }

        foreach ($emailsOfCompanyCustomers as $email) {
            $user = $this->findUserByEmailAndDomain($email, $companyData->userData->domainId);

            if ($user !== null && $user->getBillingAddress()->getId() != $billingAddress->getId()) {
                throw new DuplicateEmailsException($email);
            }
        }
    }
}
