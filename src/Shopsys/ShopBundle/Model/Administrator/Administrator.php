<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Administrator;

use Doctrine\ORM\Mapping as ORM;
use Shopsys\FrameworkBundle\Model\Administrator\Administrator as BaseAdministrator;

/**
 * @ORM\Entity
 * @ORM\Table(
 *   name="administrators",
 *   indexes={
 *     @ORM\Index(columns={"username"})
 *   }
 * )
 */
class Administrator extends BaseAdministrator
{
    public const DEFAULT_ADMIN_ID = 2;

    /**
     * @return bool
     */
    public function isDefaultAdmin(): bool
    {
        return $this->getId() === self::DEFAULT_ADMIN_ID;
    }
}
