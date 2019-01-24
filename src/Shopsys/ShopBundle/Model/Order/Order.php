<?php

namespace Shopsys\ShopBundle\Model\Order;

use Doctrine\ORM\Mapping as ORM;
use Shopsys\FrameworkBundle\Model\Customer\User;
use Shopsys\FrameworkBundle\Model\Order\Item\OrderItemPriceCalculation;
use Shopsys\FrameworkBundle\Model\Order\Item\OrderProductFactoryInterface;
use Shopsys\FrameworkBundle\Model\Order\Order as BaseOrder;
use Shopsys\FrameworkBundle\Model\Order\OrderData as BaseOrderData;
use Shopsys\FrameworkBundle\Model\Order\OrderEditResult;
use Shopsys\FrameworkBundle\Model\Order\OrderPriceCalculation;

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
     * Order constructor.
     * @param \Shopsys\ShopBundle\Model\Order\OrderData $orderData
     * @param string $orderNumber
     * @param string $urlHash
     * @param \Shopsys\FrameworkBundle\Model\Customer\User|null $user
     */
    public function __construct(
        BaseOrderData $orderData,
        string $orderNumber,
        string $urlHash,
        ?User $user = null
    ) {
        if ($orderData->transport !== null && $orderData->transport->isPickUpPlaceType()) {
            $this->pickUpPlace = $orderData->pickUpPlace;
        } else {
            $this->pickUpPlace = null;
        }
        parent::__construct($orderData, $orderNumber, $urlHash, $user);
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Order\OrderData $orderData
     * @param \Shopsys\FrameworkBundle\Model\Order\Item\OrderItemPriceCalculation $orderItemPriceCalculation
     * @param \Shopsys\FrameworkBundle\Model\Order\Item\OrderProductFactoryInterface $orderProductFactory
     * @param \Shopsys\FrameworkBundle\Model\Order\OrderPriceCalculation $orderPriceCalculation
     * @return \Shopsys\FrameworkBundle\Model\Order\OrderEditResult
     */
    public function edit(
        BaseOrderData $orderData,
        OrderItemPriceCalculation $orderItemPriceCalculation,
        OrderProductFactoryInterface $orderProductFactory,
        OrderPriceCalculation $orderPriceCalculation
    ): OrderEditResult {
        $this->pickUpPlace = $orderData->pickUpPlace;
        return parent::edit($orderData, $orderItemPriceCalculation, $orderProductFactory, $orderPriceCalculation);
    }

    /**
     * @return null|\Shopsys\ShopBundle\Model\PickUpPlace\PickUpPlace
     */
    public function getPickUpPlace()
    {
        return $this->pickUpPlace;
    }
}
