<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Administrator\Exception;

use Exception;
use Shopsys\FrameworkBundle\Model\Administrator\Exception\AdministratorException;

class DeletingDefaultAdminException extends Exception implements AdministratorException
{
}
