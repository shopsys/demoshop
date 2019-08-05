<?php

namespace Shopsys\ShopBundle\Form\Admin;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Shopsys\FormTypesBundle\MultidomainType;
use Shopsys\FrameworkBundle\Form\Admin\Category\CategoryFormType;
use Shopsys\FrameworkBundle\Form\FormRenderingConfigurationExtension;
use Shopsys\FrameworkBundle\Form\GroupType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;

class CategoryFormTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builderDescriptionSecondGroup = $builder->create('descriptionSecond', GroupType::class, [
            'label' => t('Category description above product list'),
        ]);

        $builderDescriptionSecondGroup
            ->add('descriptionsSecond', MultidomainType::class, [
                'entry_type' => CKEditorType::class,
                'required' => false,
                'display_format' => FormRenderingConfigurationExtension::DISPLAY_FORMAT_MULTIDOMAIN_ROWS_NO_PADDING,
            ]);

        $builder
            ->add($builderDescriptionSecondGroup);

        $this->rebuildChildWithAdditionalOptions($builder, 'description', [
            'label' => t('Category description under product list'),
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string $name
     * @param array $options
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    protected function rebuildChildWithAdditionalOptions(FormBuilderInterface $builder, string $name, array $options): FormBuilderInterface
    {
        $originalBuilder = $builder->get($name);
        $builder->remove($name);

        $type = get_class($originalBuilder->getType()->getInnerType());
        $options = array_merge($originalBuilder->getOptions(), $options);
        $newBuilder = $builder->create($name, $type, $options);
        foreach ($originalBuilder->all() as $child) {
            $newBuilder->add($child);
        }

        $builder->add($newBuilder);

        return $newBuilder;
    }

    /**
     * @return string
     */
    public function getExtendedType()
    {
        return CategoryFormType::class;
    }
}
