<?php

declare(strict_types=1);

namespace App\Model\Customer\User;

use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserData as BaseCustomerUserData;

class CustomerUserData extends BaseCustomerUserData
{
    /**
     * @var int|null
     */
    public $id;

    /**
     * @var int
     */
    public $discount;

    public function __construct()
    {
        parent::__construct();
        $this->discount = 0;
    }
}
