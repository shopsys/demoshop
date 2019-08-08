<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Order;

use Doctrine\ORM\Mapping as ORM;
use Shopsys\FrameworkBundle\Model\Customer\User;
use Shopsys\FrameworkBundle\Model\Order\Order as BaseOrder;
use Shopsys\FrameworkBundle\Model\Order\OrderData as BaseOrderData;
use Shopsys\FrameworkBundle\Model\Order\OrderEditResult;

/**
 * @ORM\Table(name="orders")
 * @ORM\Entity
 */
class Order extends BaseOrder
{
    /**
     * @var \Shopsys\ShopBundle\Model\PickUpPlace\PickUpPlace|null
     *
     * @ORM\ManyToOne(targetEntity="Shopsys\ShopBundle\Model\PickUpPlace\PickUpPlace")
     * @ORM\JoinColumn(nullable=true,name="pick_up_place_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $pickUpPlace;

    /**
     * @param \Shopsys\ShopBundle\Model\Order\OrderData $orderData
     * @param string $orderNumber
     * @param string $urlHash
     * @param \Shopsys\ShopBundle\Model\Customer\User|null $user
     */
    public function __construct(
        BaseOrderData $orderData,
        string $orderNumber,
        string $urlHash,
        ?User $user = null
    ) {
        parent::__construct($orderData, $orderNumber, $urlHash, $user);
        if ($orderData->transport !== null && $orderData->transport->isPickUpPlaceType()) {
            $this->pickUpPlace = $orderData->pickUpPlace;
        } else {
            $this->pickUpPlace = null;
        }
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Order\OrderData $orderData
     * @return \Shopsys\FrameworkBundle\Model\Order\OrderEditResult
     */
    public function edit(BaseOrderData $orderData): OrderEditResult
    {
        $orderEditResult = parent::edit($orderData);
        $this->pickUpPlace = $orderData->pickUpPlace;

        return $orderEditResult;
    }

    /**
     * @return null|\Shopsys\ShopBundle\Model\PickUpPlace\PickUpPlace
     */
    public function getPickUpPlace()
    {
        return $this->pickUpPlace;
    }
}
