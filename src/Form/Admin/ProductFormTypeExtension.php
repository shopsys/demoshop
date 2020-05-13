<?php

declare(strict_types=1);

namespace App\Form\Admin;

use App\Model\Product\ProductConditionFacade;
use Shopsys\FrameworkBundle\Form\Admin\Product\ProductFormType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class ProductFormTypeExtension extends AbstractTypeExtension
{
    /**
     * @var \App\Model\Product\ProductConditionFacade
     */
    private $productConditionFacade;

    /**
     * @param \App\Model\Product\ProductConditionFacade $productConditionFacade
     */
    public function __construct(ProductConditionFacade $productConditionFacade)
    {
        $this->productConditionFacade = $productConditionFacade;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $basicInformationGroupBuilder = $builder->get('basicInformationGroup');

        $basicInformationGroupBuilder->add('condition', ChoiceType::class, [
            'required' => true,
            'choices' => $this->productConditionFacade->getAll(),
            'label' => t('Condition'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function getExtendedTypes(): iterable
    {
        yield ProductFormType::class;
    }
}
