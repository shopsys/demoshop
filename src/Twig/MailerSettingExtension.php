<?php

declare(strict_types=1);

namespace App\Twig;

use Shopsys\FrameworkBundle\Twig\MailerSettingExtension as BaseMailerSettingExtension;

class MailerSettingExtension extends BaseMailerSettingExtension
{
    /**
     * @return bool
     */
    public function isMailerSettingUnusual(): bool
    {
        return $this->mailerMasterEmailAddress !== null;
    }
}
