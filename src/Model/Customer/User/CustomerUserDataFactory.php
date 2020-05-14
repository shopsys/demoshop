<?php

declare(strict_types=1);

namespace App\Model\Customer\User;

use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUser as BaseCustomerUser;
use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserData as BaseCustomerUserData;
use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserDataFactory as BaseUserDataFactory;

/**
 * @method \App\Model\Customer\User\CustomerUserData createForCustomer(\Shopsys\FrameworkBundle\Model\Customer\Customer $customer)
 */
class CustomerUserDataFactory extends BaseUserDataFactory
{
    /**
     * @return \App\Model\Customer\User\CustomerUserData
     */
    public function create(): BaseCustomerUserData
    {
        return new CustomerUserData();
    }

    /**
     * @param int $domainId
     * @return \App\Model\Customer\User\CustomerUserData
     */
    public function createForDomainId(int $domainId): BaseCustomerUserData
    {
        $customerUserData = new CustomerUserData();
        $this->fillForDomainId($customerUserData, $domainId);

        return $customerUserData;
    }

    /**
     * @param \App\Model\Customer\User\CustomerUser $customerUser
     * @return \App\Model\Customer\User\CustomerUserData
     */
    public function createFromCustomerUser(BaseCustomerUser $customerUser): BaseCustomerUserData
    {
        $customerUserData = new CustomerUserData();
        $this->fillFromUser($customerUserData, $customerUser);

        return $customerUserData;
    }

    /**
     * @param \App\Model\Customer\User\CustomerUser[] $customerUsers
     * @return \App\Model\Customer\User\CustomerUserData[]
     */
    public function createMultipleUserDataFromUsers(array $customerUsers)
    {
        $multipleUserData = [];

        foreach ($customerUsers as $customerUser) {
            $multipleUserData[] = $this->createFromCustomerUser($customerUser);
        }

        return $multipleUserData;
    }

    /**
     * @param \App\Model\Customer\User\CustomerUserData $customerUserData
     * @param \App\Model\Customer\User\CustomerUser $customerUser
     */
    public function fillFromUser(BaseCustomerUserData $customerUserData, BaseCustomerUser $customerUser)
    {
        $customerUserData->id = $customerUser->getId();
        $customerUserData->domainId = $customerUser->getDomainId();
        $customerUserData->firstName = $customerUser->getFirstName();
        $customerUserData->lastName = $customerUser->getLastName();
        $customerUserData->email = $customerUser->getEmail();
        $customerUserData->pricingGroup = $customerUser->getPricingGroup();
        $customerUserData->createdAt = $customerUser->getCreatedAt();
        $customerUserData->telephone = $customerUser->getTelephone();
        $customerUserData->discount = $customerUser->getDiscount();
    }
}
