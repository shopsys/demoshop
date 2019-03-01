<?php

namespace Tests\ShopBundle\Functional\Model\Pricing\Group;

use Shopsys\ShopBundle\DataFixtures\Demo\PricingGroupDataFixture;
use Shopsys\ShopBundle\DataFixtures\Demo\ProductDataFixture;
use Shopsys\FrameworkBundle\Model\Customer\CustomerDataFactory;
use Shopsys\FrameworkBundle\Model\Customer\CustomerFacade;
use Shopsys\FrameworkBundle\Model\Customer\UserDataFactory;
use Shopsys\FrameworkBundle\Model\Pricing\Group\PricingGroupData;
use Shopsys\FrameworkBundle\Model\Pricing\Group\PricingGroupFacade;
use Shopsys\FrameworkBundle\Model\Product\Pricing\ProductCalculatedPrice;
use Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPriceRecalculator;
use Tests\ShopBundle\Test\TransactionFunctionalTestCase;

class PricingGroupFacadeTest extends TransactionFunctionalTestCase
{
    public function testCreate()
    {
        $em = $this->getEntityManager();
        $product = $this->getReference(ProductDataFixture::PRODUCT_PREFIX . '1');
        /* @var $prodcu \Shopsys\ShopBundle\Model\Product\Product */
        $pricingGroupFacade = $this->getContainer()->get(PricingGroupFacade::class);
        /* @var $pricingGroupFacade \Shopsys\FrameworkBundle\Model\Pricing\Group\PricingGroupFacade */
        $productPriceRecalculator = $this->getContainer()->get(ProductPriceRecalculator::class);
        /* @var $productPriceRecalculator \Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPriceRecalculator */
        $pricingGroupData = new PricingGroupData();
        $pricingGroupData->name = 'pricing_group_name';
        $domainId = 1;
        $pricingGroup = $pricingGroupFacade->create($pricingGroupData, $domainId);
        $productPriceRecalculator->runAllScheduledRecalculations();
        $productCalculatedPrice = $em->getRepository(ProductCalculatedPrice::class)->findOneBy([
            'product' => $product,
            'pricingGroup' => $pricingGroup,
        ]);

        $this->assertNotNull($productCalculatedPrice);
    }

    public function testDeleteAndReplace()
    {
        $em = $this->getEntityManager();
        $pricingGroupFacade = $this->getContainer()->get(PricingGroupFacade::class);
        /* @var $pricingGroupFacade \Shopsys\FrameworkBundle\Model\Pricing\Group\PricingGroupFacade */
        $customerFacade = $this->getContainer()->get(CustomerFacade::class);
        /* @var $customerFacade \Shopsys\FrameworkBundle\Model\Customer\CustomerFacade */

        $domainId = 1;
        $pricingGroupData = new PricingGroupData();
        $pricingGroupData->name = 'name';
        $pricingGroupToDelete = $pricingGroupFacade->create($pricingGroupData, $domainId);
        $pricingGroupToReplaceWith = $this->getReference(PricingGroupDataFixture::PRICING_GROUP_ORDINARY_DOMAIN_1);
        /* @var $pricingGroup \Shopsys\FrameworkBundle\Model\Pricing\Group\PricingGroup */
        $user = $customerFacade->getUserById(1);
        /* @var $user \Shopsys\FrameworkBundle\Model\Customer\User */
        $userDataFactory = $this->getContainer()->get(UserDataFactory::class);
        /* @var $userDataFactory \Shopsys\FrameworkBundle\Model\Customer\UserDataFactory */
        $userData = $userDataFactory->createFromUser($user);
        $customerDataFactory = $this->getContainer()->get(CustomerDataFactory::class);
        /* @var $customerDataFactory \Shopsys\FrameworkBundle\Model\Customer\CustomerDataFactory */

        $userData->pricingGroup = $pricingGroupToDelete;
        $customerData = $customerDataFactory->create();
        $customerData->userData = $userData;
        $customerFacade->editByAdmin($user->getId(), $customerData);

        $pricingGroupFacade->delete($pricingGroupToDelete->getId(), $pricingGroupToReplaceWith->getId());

        $em->refresh($user);

        $this->assertEquals($pricingGroupToReplaceWith, $user->getPricingGroup());
    }
}
