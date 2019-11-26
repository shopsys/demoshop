<?php

declare(strict_types=1);

namespace Tests\ShopBundle\Acceptance\acceptance;

use Tests\ShopBundle\Acceptance\acceptance\PageObject\Admin\EntityEditPage;
use Tests\ShopBundle\Acceptance\acceptance\PageObject\Admin\LoginPage;
use Tests\ShopBundle\Test\Codeception\AcceptanceTester;

class TransportImageUploadCest
{
    protected const IMAGE_UPLOAD_FIELD_ID = 'transport_form_image_image_file';
    protected const SAVE_BUTTON_NAME = 'transport_form[save]';

    protected const TEST_IMAGE_NAME = 'transportTestImage.png';

    /**
     * @param \Tests\ShopBundle\Test\Codeception\AcceptanceTester $me
     * @param \Tests\ShopBundle\Acceptance\acceptance\PageObject\Admin\EntityEditPage $entityEditPage
     * @param \Tests\ShopBundle\Acceptance\acceptance\PageObject\Admin\LoginPage $loginPage
     */
    public function testSuccessfulImageUpload(AcceptanceTester $me, EntityEditPage $entityEditPage, LoginPage $loginPage)
    {
        $me->wantTo('Upload an image in admin transport edit page');
        $loginPage->loginAsAdmin();
        $me->amOnPage('/admin/transport/edit/1');
        $entityEditPage->uploadTestImage(self::IMAGE_UPLOAD_FIELD_ID, self::TEST_IMAGE_NAME);
        $me->clickByName(self::SAVE_BUTTON_NAME);
        $me->seeTranslationAdmin('Shipping <strong><a href="{{ url }}">%name%</a></strong> was modified', 'messages', [
            '{{ url }}' => '',
            '%name%' => t('Czech post', [], 'dataFixtures', $me->getAdminLocale()),
        ]);
    }
}
