<?php

declare(strict_types=1);

namespace App\Model\Order;

use Doctrine\ORM\Mapping as ORM;
use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUser;
use Shopsys\FrameworkBundle\Model\Order\Order as BaseOrder;
use Shopsys\FrameworkBundle\Model\Order\OrderData as BaseOrderData;
use Shopsys\FrameworkBundle\Model\Order\OrderEditResult;

/**
 * @ORM\Table(name="orders")
 * @ORM\Entity
 * @property \App\Model\Customer\User\CustomerUser|null $customer
 * @property \App\Model\Transport\Transport $transport
 * @property \App\Model\Administrator\Administrator|null $createdAsAdministrator
 * @method editData(\App\Model\Order\OrderData $orderData)
 * @method editOrderTransport(\App\Model\Order\OrderData $orderData)
 * @method editOrderPayment(\App\Model\Order\OrderData $orderData)
 * @method setDeliveryAddress(\App\Model\Order\OrderData $orderData)
 * @method \App\Model\Transport\Transport getTransport()
 * @method \App\Model\Customer\User\CustomerUser|null getCustomer()
 * @method \App\Model\Administrator\Administrator|null getCreatedAsAdministrator()
 * @property \App\Model\Customer\User\CustomerUser|null $customerUser
 * @method fillCommonFields(\App\Model\Order\OrderData $orderData)
 * @method \App\Model\Customer\User\CustomerUser|null getCustomerUser()
 */
class Order extends BaseOrder
{
    /**
     * @var \App\Model\PickUpPlace\PickUpPlace|null
     *
     * @ORM\ManyToOne(targetEntity="App\Model\PickUpPlace\PickUpPlace")
     * @ORM\JoinColumn(nullable=true,name="pick_up_place_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $pickUpPlace;

    /**
     * @param \App\Model\Order\OrderData $orderData
     * @param string $orderNumber
     * @param string $urlHash
     * @param \App\Model\Customer\User\CustomerUser|null $customerUser
     */
    public function __construct(
        BaseOrderData $orderData,
        string $orderNumber,
        string $urlHash,
        ?CustomerUser $customerUser = null
    ) {
        parent::__construct($orderData, $orderNumber, $urlHash, $customerUser);
        if ($orderData->transport !== null && $orderData->transport->isPickUpPlaceType()) {
            $this->pickUpPlace = $orderData->pickUpPlace;
        } else {
            $this->pickUpPlace = null;
        }
    }

    /**
     * @param \App\Model\Order\OrderData $orderData
     * @return \Shopsys\FrameworkBundle\Model\Order\OrderEditResult
     */
    public function edit(BaseOrderData $orderData): OrderEditResult
    {
        $orderEditResult = parent::edit($orderData);
        $this->pickUpPlace = $orderData->pickUpPlace;

        return $orderEditResult;
    }

    /**
     * @return null|\App\Model\PickUpPlace\PickUpPlace
     */
    public function getPickUpPlace()
    {
        return $this->pickUpPlace;
    }
}
