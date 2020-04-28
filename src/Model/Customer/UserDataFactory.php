<?php

declare(strict_types=1);

namespace App\Model\Customer;

use Shopsys\FrameworkBundle\Model\Customer\User as BaseUser;
use Shopsys\FrameworkBundle\Model\Customer\UserData as BaseUserData;
use Shopsys\FrameworkBundle\Model\Customer\UserDataFactory as BaseUserDataFactory;

class UserDataFactory extends BaseUserDataFactory
{
    /**
     * @return \App\Model\Customer\UserData
     */
    public function create(): BaseUserData
    {
        return new UserData();
    }

    /**
     * @param int $domainId
     * @return \App\Model\Customer\UserData
     */
    public function createForDomainId(int $domainId): BaseUserData
    {
        $userData = new UserData();
        $this->fillForDomainId($userData, $domainId);

        return $userData;
    }

    /**
     * @param \App\Model\Customer\User $user
     * @return \App\Model\Customer\UserData
     */
    public function createFromUser(BaseUser $user): BaseUserData
    {
        $userData = new UserData();
        $this->fillFromUser($userData, $user);

        return $userData;
    }

    /**
     * @param \App\Model\Customer\User[] $users
     * @return \App\Model\Customer\UserData[]
     */
    public function createMultipleUserDataFromUsers(array $users)
    {
        $multipleUserData = [];

        foreach ($users as $user) {
            $multipleUserData[] = $this->createFromUser($user);
        }

        return $multipleUserData;
    }

    /**
     * @param \App\Model\Customer\UserData $userData
     * @param \App\Model\Customer\User $user
     */
    public function fillFromUser(BaseUserData $userData, BaseUser $user)
    {
        $userData->id = $user->getId();
        $userData->domainId = $user->getDomainId();
        $userData->firstName = $user->getFirstName();
        $userData->lastName = $user->getLastName();
        $userData->email = $user->getEmail();
        $userData->pricingGroup = $user->getPricingGroup();
        $userData->createdAt = $user->getCreatedAt();
        $userData->telephone = $user->getTelephone();
        $userData->discount = $user->getDiscount();
    }
}
