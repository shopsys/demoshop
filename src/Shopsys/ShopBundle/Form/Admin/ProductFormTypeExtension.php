<?php

namespace Shopsys\ShopBundle\Form\Admin;

use Shopsys\FrameworkBundle\Form\Admin\Product\ProductFormType;
use Shopsys\ShopBundle\Model\Product\ProductConditionFacade;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class ProductFormTypeExtension extends AbstractTypeExtension
{
    /**
     * @var \Shopsys\ShopBundle\Model\Product\ProductConditionFacade
     */
    private $productConditionFacade;

    public function __construct(ProductConditionFacade $productConditionFacade)
    {
        $this->productConditionFacade = $productConditionFacade;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('condition', ChoiceType::class, [
            'required' => true,
            'choices' => $this->productConditionFacade->getAll(),
            'label' => t('Condition'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return ProductFormType::class;
    }
}
