<?php

declare(strict_types=1);

namespace App\Model\Product\Pricing;

use App\Model\Customer\CurrentCustomer;
use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Model\Customer\User;
use Shopsys\FrameworkBundle\Model\Pricing\Group\PricingGroupSettingFacade;
use Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPriceCalculation;
use Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPriceCalculationForUser as BaseProductPriceCalculationForUser;
use Shopsys\FrameworkBundle\Model\Product\Product;

class ProductPriceCalculationForUser extends BaseProductPriceCalculationForUser
{
    /**
     * @var \App\Model\Product\Pricing\ProductPriceCalculation
     */
    protected $productPriceCalculation;

    /**
     * @var \App\Model\Customer\CurrentCustomer
     */
    protected $currentCustomer;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Pricing\Group\PricingGroupSettingFacade
     */
    protected $pricingGroupSettingFacade;

    /**
     * @var \Shopsys\FrameworkBundle\Component\Domain\Domain
     */
    protected $domain;

    /**
     * @param \App\Model\Product\Pricing\ProductPriceCalculation $productPriceCalculation
     * @param \App\Model\Customer\CurrentCustomer $currentCustomer
     * @param \Shopsys\FrameworkBundle\Model\Pricing\Group\PricingGroupSettingFacade $pricingGroupSettingFacade
     * @param \Shopsys\FrameworkBundle\Component\Domain\Domain $domain
     */
    public function __construct(
        ProductPriceCalculation $productPriceCalculation,
        CurrentCustomer $currentCustomer,
        PricingGroupSettingFacade $pricingGroupSettingFacade,
        Domain $domain
    ) {
        parent::__construct($productPriceCalculation, $currentCustomer, $pricingGroupSettingFacade, $domain);

        $this->productPriceCalculation = $productPriceCalculation;
        $this->currentCustomer = $currentCustomer;
        $this->pricingGroupSettingFacade = $pricingGroupSettingFacade;
        $this->domain = $domain;
    }

    /**
     * @param \App\Model\Product\Product $product
     * @return \Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPrice
     */
    public function calculatePriceForCurrentUser(Product $product)
    {
        return $this->productPriceCalculation->calculatePrice(
            $product,
            $this->domain->getId(),
            $this->currentCustomer->getPricingGroup(),
            $this->currentCustomer->getDiscountCoeficient()
        );
    }

    /**
     * @param \App\Model\Product\Product $product
     * @param int $domainId
     * @param \App\Model\Customer\User|null $user
     * @return \Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPrice
     */
    public function calculatePriceForUserAndDomainId(Product $product, $domainId, ?User $user = null)
    {
        if ($user === null) {
            $pricingGroup = $this->pricingGroupSettingFacade->getDefaultPricingGroupByDomainId($domainId);
        } else {
            $pricingGroup = $user->getPricingGroup();
        }

        return $this->productPriceCalculation->calculatePrice($product, $domainId, $pricingGroup, $this->currentCustomer->getDiscountCoeficient());
    }
}
