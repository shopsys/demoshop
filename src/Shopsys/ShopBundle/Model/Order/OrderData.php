<?php

namespace Shopsys\ShopBundle\Model\Order;

use Shopsys\FrameworkBundle\Model\Order\FrontOrderData as BaseFrontOrderData;

class OrderData extends BaseFrontOrderData
{
    /**
     * @var \Shopsys\ShopBundle\Model\PickUpPlace\PickUpPlace|null
     */
    public $pickUpPlace;

    public function __construct()
    {
        $this->pickUpPlace = null;
        parent::__construct();
    }
}
