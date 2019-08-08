<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Customer\Exception;

use Exception;
use Shopsys\FrameworkBundle\Model\Customer\Exception\CustomerException;

class DuplicateEmailsException extends Exception implements CustomerException
{
    /**
     * @var string
     */
    protected $email;

    /**
     * @param mixed $email
     * @param string $message
     * @param \Exception|null $previous
     */
    public function __construct($email, $message = '', ?Exception $previous = null)
    {
        $this->email = $email;

        parent::__construct($message, 0, $previous);
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
}
