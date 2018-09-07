<?php

namespace Shopsys\ShopBundle\Twig;

use Shopsys\FrameworkBundle\Twig\FormThemeExtension as BaseFormThemeExtension;

class FormThemeExtension extends BaseFormThemeExtension
{
    /**
     * @return string
     */
    public function getDefaultFormTheme()
    {
        $masterRequest = $this->requestStack->getMasterRequest();
        if (mb_stripos($masterRequest->get('_controller'), 'Shopsys\FrameworkBundle\Controller\Admin') === 0 ||
            mb_stripos($masterRequest->get('_controller'), 'Shopsys\ShopBundle\Controller\Admin') === 0
        ) {
            return self::ADMIN_THEME;
        } else {
            return self::FRONT_THEME;
        }
    }
}
