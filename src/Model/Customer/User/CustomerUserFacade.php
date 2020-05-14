<?php

declare(strict_types=1);

namespace App\Model\Customer\User;

use App\Model\Customer\Exception\DuplicateEmailsException;
use Doctrine\ORM\EntityManagerInterface;
use Shopsys\FrameworkBundle\Model\Customer\BillingAddress;
use Shopsys\FrameworkBundle\Model\Customer\BillingAddressDataFactoryInterface;
use Shopsys\FrameworkBundle\Model\Customer\BillingAddressFacade;
use Shopsys\FrameworkBundle\Model\Customer\BillingAddressFactoryInterface;
use Shopsys\FrameworkBundle\Model\Customer\CustomerDataFactoryInterface;
use Shopsys\FrameworkBundle\Model\Customer\CustomerFacade;
use Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressFacade;
use Shopsys\FrameworkBundle\Model\Customer\Mail\CustomerMailFacade;
use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserFacade as BaseCustomerFacade;
use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserFactoryInterface;
use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserPasswordFacade;
use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserRefreshTokenChainFacade;
use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserRepository;
use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserUpdateDataFactoryInterface;

/**
 * @property \App\Model\Customer\User\CustomerUserRepository $customerUserRepository
 * @property \App\Model\Customer\BillingAddressFactory $billingAddressFactory
 * @property \App\Model\Customer\BillingAddressDataFactory $billingAddressDataFactory
 * @method \App\Model\Customer\User\CustomerUser getCustomerUserById(int $userId)
 * @method \App\Model\Customer\User\CustomerUser|null findCustomerUserByEmailAndDomain(string $email, int $domainId)
 * @method \App\Model\Customer\User\CustomerUser register(\App\Model\Customer\User\CustomerUserData $customerUserData)
 * @method \App\Model\Customer\User\CustomerUser create(\App\Model\Customer\User\CustomerUserUpdateData $customerUserUpdateData)
 * @method \App\Model\Customer\User\CustomerUser edit(int $userId, \App\Model\Customer\User\CustomerUserUpdateData $customerUserUpdateData)
 * @method \App\Model\Customer\User\CustomerUser editByAdmin(int $userId, \App\Model\Customer\User\CustomerUserUpdateData $customerUserUpdateData)
 * @method \App\Model\Customer\User\CustomerUser editByCustomerUser(int $userId, \App\Model\Customer\User\CustomerUserUpdateData $customerUserUpdateData)
 * @method amendCustomerDataFromOrder(\App\Model\Customer\User\CustomerUser $customerUser, \App\Model\Order\Order $order)
 * @method setEmail(string $email, \App\Model\Customer\User\CustomerUser $customerUser)
 */
class CustomerUserFacade extends BaseCustomerFacade
{
    /**
     * @var \App\Model\Customer\User\CustomerUserUpdateDataFactory
     */
    protected $customerUserUpdateDataFactory;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserFactoryInterface
     */
    protected $customerUserFactory;

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $em
     * @param \App\Model\Customer\User\CustomerUserRepository $customerUserRepository
     * @param \App\Model\Customer\User\CustomerUserUpdateDataFactory $customerUserUpdateDataFactory
     * @param \Shopsys\FrameworkBundle\Model\Customer\Mail\CustomerMailFacade $customerMailFacade
     * @param \App\Model\Customer\BillingAddressFactory $billingAddressFactory
     * @param \App\Model\Customer\BillingAddressDataFactory $billingAddressDataFactory
     * @param \Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserFactoryInterface $customerUserFactory
     * @param \Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserPasswordFacade $customerUserPasswordFacade
     * @param \Shopsys\FrameworkBundle\Model\Customer\CustomerFacade $customerFacade
     * @param \Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressFacade $deliveryAddressFacade
     * @param \Shopsys\FrameworkBundle\Model\Customer\CustomerDataFactoryInterface $customerDataFactory
     * @param \Shopsys\FrameworkBundle\Model\Customer\BillingAddressFacade $billingAddressFacade
     * @param \Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserRefreshTokenChainFacade $customerUserRefreshTokenChainFacade
     */
    public function __construct(
        EntityManagerInterface $em,
        CustomerUserRepository $customerUserRepository,
        CustomerUserUpdateDataFactoryInterface $customerUserUpdateDataFactory,
        CustomerMailFacade $customerMailFacade,
        BillingAddressFactoryInterface $billingAddressFactory,
        BillingAddressDataFactoryInterface $billingAddressDataFactory,
        CustomerUserFactoryInterface $customerUserFactory,
        CustomerUserPasswordFacade $customerUserPasswordFacade,
        CustomerFacade $customerFacade,
        DeliveryAddressFacade $deliveryAddressFacade,
        CustomerDataFactoryInterface $customerDataFactory,
        BillingAddressFacade $billingAddressFacade,
        CustomerUserRefreshTokenChainFacade $customerUserRefreshTokenChainFacade
    ) {
        parent::__construct(
            $em,
            $customerUserRepository,
            $customerUserUpdateDataFactory,
            $customerMailFacade,
            $billingAddressFactory,
            $billingAddressDataFactory,
            $customerUserFactory,
            $customerUserPasswordFacade,
            $customerFacade,
            $deliveryAddressFacade,
            $customerDataFactory,
            $billingAddressFacade,
            $customerUserRefreshTokenChainFacade
        );
    }

    /**
     * @param \App\Model\Customer\BillingAddress $billingAddress
     * @param int $domainId
     * @return \App\Model\Customer\User\CustomerUser[]
     */
    public function getUsersByBillingAddressAndDomain(BillingAddress $billingAddress, int $domainId)
    {
        return $this->customerUserRepository->getUsersByBillingAddressAndDomain($billingAddress, $domainId);
    }

    /**
     * @param \App\Model\Customer\BillingAddress $billingAddress
     * @return array
     */
    public function getAllByBillingAddress(BillingAddress $billingAddress)
    {
        return $this->customerUserRepository->getAllByBillingAddress($billingAddress);
    }

    /**
     * @param \App\Model\Customer\BillingAddress $billingAddress
     * @return \App\Model\Customer\User\CustomerUser
     */
    public function getUserByBillingAddress(BillingAddress $billingAddress)
    {
        return $this->customerUserRepository->getUserByBillingAddress($billingAddress);
    }

    /**
     * @param \App\Model\Customer\BillingAddress $billingAddress
     * @param \App\Model\Customer\User\CustomerUserUpdateData $companyData
     */
    public function editCompanyWithMultipleUsers(BillingAddress $billingAddress, CustomerUserUpdateData $companyData)
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
            $companyDataOfCompanyUser->customerUserData = $companyUserData;
            $companyDataOfCompanyUser->customerUserData->discount = $companyData->customerUserData->discount;

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
     * @param \App\Model\Customer\BillingAddress $billingAddress
     * @param \App\Model\Customer\User\CustomerUserUpdateData $companyData
     */
    protected function removeOldCompanyUsers(BillingAddress $billingAddress, CustomerUserUpdateData $companyData)
    {
        $oldCompanyUsers = $this->customerUserRepository->getAllByBillingAddress($billingAddress);

        foreach ($oldCompanyUsers as $oldCompanyUser) {
            $this->removeOldCompanyUserIfNotFound($oldCompanyUser, $companyData);
        }
    }

    /**
     * @param \App\Model\Customer\User\CustomerUser $companyUser
     * @param \App\Model\Customer\User\CustomerUserUpdateData $newCompanyData
     */
    protected function removeOldCompanyUserIfNotFound(CustomerUser $companyUser, CustomerUserUpdateData $newCompanyData)
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
     * @param \App\Model\Customer\User\CustomerUserUpdateData $companyData
     * @param \App\Model\Customer\BillingAddress $billingAddress
     * @param \App\Model\Customer\User\CustomerUserData[] $newCompanyUsersData
     */
    protected function createNewCompanyUsers(CustomerUserUpdateData $companyData, BillingAddress $billingAddress, array $newCompanyUsersData)
    {
        $toFlush = [];
        foreach ($newCompanyUsersData as $newCompanyUserData) {
            $newCompanyUserData->pricingGroup = $companyData->customerUserData->pricingGroup;
            $newCompanyUserData->domainId = $companyData->customerUserData->domainId;

            $customerUser = $this->customerUserFactory->create(
                $newCompanyUserData,
                $billingAddress,
                null,
                null
            );
            $this->em->persist($customerUser);

            $toFlush[] = $customerUser;
        }

        if (!empty($toFlush)) {
            $this->em->flush($toFlush);
        }
    }

    /**
     * @param \App\Model\Customer\BillingAddress $billingAddress
     */
    public function removeBillingAddress(BillingAddress $billingAddress)
    {
        $customerUsers = $this->getAllByBillingAddress($billingAddress);

        $toFlush = [];
        foreach ($customerUsers as $customerUser) {
            $this->em->remove($customerUser);
            $toFlush[] = $customerUser;
        }

        $this->em->remove($billingAddress);
        $toFlush[] = $billingAddress;

        $this->em->flush($toFlush);
    }

    /**
     * @param \App\Model\Customer\BillingAddress $billingAddress
     * @param \App\Model\Customer\User\CustomerUserUpdateData $companyData
     */
    public function removeCompanyUsersExceptTheFirstOne(BillingAddress $billingAddress, CustomerUserUpdateData $companyData)
    {
        $companyUsersDataWithOneUser = reset($companyData->companyUsersData);
        $companyData->companyUsersData = [$companyUsersDataWithOneUser];
        $this->removeOldCompanyUsers($billingAddress, $companyData);
    }

    /**
     * @param \App\Model\Customer\BillingAddress $billingAddress
     * @param \App\Model\Customer\User\CustomerUserUpdateData $companyData
     * @throws \App\Model\Customer\Exception\DuplicateEmailsException
     */
    protected function processUniqueEmailsCheck(BillingAddress $billingAddress, CustomerUserUpdateData $companyData)
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
            $customerUser = $this->findCustomerUserByEmailAndDomain($email, $companyData->customerUserData->domainId);

            if ($customerUser !== null && $customerUser->getCustomer()->getBillingAddress()->getId() != $billingAddress->getId()) {
                throw new DuplicateEmailsException($email);
            }
        }
    }
}
