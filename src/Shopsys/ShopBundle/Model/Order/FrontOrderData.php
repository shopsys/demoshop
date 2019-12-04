<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Order;

use Shopsys\FrameworkBundle\Model\Order\FrontOrderData as BaseFrontOrderData;

/**
 * @property \Shopsys\ShopBundle\Model\Transport\Transport|null $transport
 * @property \Shopsys\ShopBundle\Model\Order\Item\OrderItemData[] $itemsWithoutTransportAndPayment
 * @property \Shopsys\ShopBundle\Model\Administrator\Administrator|null $createdAsAdministrator
 * @property \Shopsys\ShopBundle\Model\Order\Item\OrderItemData|null $orderPayment
 * @property \Shopsys\ShopBundle\Model\Order\Item\OrderItemData|null $orderTransport
 * @method \Shopsys\ShopBundle\Model\Order\Item\OrderItemData[] getNewItemsWithoutTransportAndPayment()
 */
class FrontOrderData extends BaseFrontOrderData
{
    /**
     * @var \Shopsys\ShopBundle\Model\PickUpPlace\PickUpPlace|null
     */
    public $pickUpPlace;
}
