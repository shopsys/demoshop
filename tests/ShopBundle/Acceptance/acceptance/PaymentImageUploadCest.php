<?php

declare(strict_types=1);

namespace Tests\ShopBundle\Acceptance\acceptance;

use Tests\ShopBundle\Acceptance\acceptance\PageObject\Admin\EntityEditPage;
use Tests\ShopBundle\Acceptance\acceptance\PageObject\Admin\LoginPage;
use Tests\ShopBundle\Test\Codeception\AcceptanceTester;

class PaymentImageUploadCest
{
    protected const IMAGE_UPLOAD_FIELD_ID = 'payment_form_image_image_file';
    protected const SAVE_BUTTON_NAME = 'payment_form[save]';

    protected const TEST_IMAGE_NAME = 'paymentTestImage.png';

    /**
     * @param \Tests\ShopBundle\Test\Codeception\AcceptanceTester $me
     * @param \Tests\ShopBundle\Acceptance\acceptance\PageObject\Admin\EntityEditPage $entityEditPage
     * @param \Tests\ShopBundle\Acceptance\acceptance\PageObject\Admin\LoginPage $loginPage
     */
    public function testSuccessfulImageUpload(AcceptanceTester $me, EntityEditPage $entityEditPage, LoginPage $loginPage)
    {
        $me->wantTo('Upload an image in admin payment edit page');
        $loginPage->loginAsAdmin();
        $me->amOnPage('/admin/payment/edit/1');
        $entityEditPage->uploadTestImage(self::IMAGE_UPLOAD_FIELD_ID, self::TEST_IMAGE_NAME);
        $me->clickByName(self::SAVE_BUTTON_NAME);
        $me->seeTranslationAdmin('Payment <strong><a href="{{ url }}">{{ name }}</a></strong> modified', 'messages', [
            '{{ url }}' => '',
            '{{ name }}' => t('Credit card', [], 'dataFixtures', $me->getAdminLocale()),
        ]);
    }
}
