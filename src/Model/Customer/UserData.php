<?php

declare(strict_types=1);

namespace App\Model\Customer;

use Shopsys\FrameworkBundle\Model\Customer\UserData as BaseUserData;

class UserData extends BaseUserData
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
