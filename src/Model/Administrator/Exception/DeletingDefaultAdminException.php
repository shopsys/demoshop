<?php

declare(strict_types=1);

namespace App\Model\Administrator\Exception;

use Exception;
use Shopsys\FrameworkBundle\Model\Administrator\Exception\AdministratorException;

class DeletingDefaultAdminException extends Exception implements AdministratorException
{
}
