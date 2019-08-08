<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Customer\Exception;

use Shopsys\FrameworkBundle\Model\Customer\Exception\CustomerException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BillingAddressNotFoundException extends NotFoundHttpException implements CustomerException
{
}
