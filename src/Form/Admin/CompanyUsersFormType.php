<?php

declare(strict_types=1);

namespace App\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CompanyUsersFormType extends AbstractType
{
    /**
     * @return string
     */
    public function getParent()
    {
        return CollectionType::class;
    }
}
