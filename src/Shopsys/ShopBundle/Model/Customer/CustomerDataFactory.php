<?php

namespace Shopsys\ShopBundle\Model\Customer;

use Shopsys\FrameworkBundle\Model\Customer\BillingAddressDataFactoryInterface;
use Shopsys\FrameworkBundle\Model\Customer\CustomerData as BaseCustomerData;
use Shopsys\FrameworkBundle\Model\Customer\CustomerDataFactory as BaseCustomerDataFactory;
use Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressDataFactoryInterface;
use Shopsys\FrameworkBundle\Model\Customer\User as BaseUser;
use Shopsys\FrameworkBundle\Model\Customer\UserDataFactoryInterface;

class CustomerDataFactory extends BaseCustomerDataFactory
{
    /**
     * @var \Shopsys\FrameworkBundle\Model\Customer\BillingAddressDataFactoryInterface
     */
    private $billingAddressDataFactory;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressDataFactoryInterface
     */
    private $deliveryAddressDataFactory;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Customer\UserDataFactoryInterface
     */
    private $userDataFactory;

    public function __construct(
        BillingAddressDataFactoryInterface $billingAddressDataFactory,
        DeliveryAddressDataFactoryInterface $deliveryAddressDataFactory,
        UserDataFactoryInterface $userDataFactory
    ) {
        $this->billingAddressDataFactory = $billingAddressDataFactory;
        $this->deliveryAddressDataFactory = $deliveryAddressDataFactory;
        $this->userDataFactory = $userDataFactory;

        parent::__construct($billingAddressDataFactory, $deliveryAddressDataFactory, $userDataFactory);
    }

    /**
     * @return \Shopsys\ShopBundle\Model\Customer\CustomerData
     */
    public function create(): BaseCustomerData
    {
        return new CustomerData(
            $this->billingAddressDataFactory->create(),
            $this->deliveryAddressDataFactory->create(),
            $this->userDataFactory->create()
        );
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Customer\User $user
     * @return \Shopsys\ShopBundle\Model\Customer\CustomerData
     */
    public function createFromUser(BaseUser $user): BaseCustomerData
    {
        $customerData = new CustomerData(
            $this->billingAddressDataFactory->createFromBillingAddress($user->getBillingAddress()),
            $this->getDeliveryAddressDataFromUser($user),
            $this->userDataFactory->createFromUser($user)
        );

        return $customerData;
    }
}
