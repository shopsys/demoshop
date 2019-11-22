<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Form\Admin;

use Shopsys\FrameworkBundle\Form\Admin\Customer\BillingAddressFormType;
use Shopsys\FrameworkBundle\Form\ValidationGroup;
use Shopsys\FrameworkBundle\Model\Customer\BillingAddressData;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BillingAddressFormTypeExtension extends AbstractTypeExtension
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builderCompanyDataGroup = $builder->get('companyData');

        $originalFormChildrenSorting = array_keys($builder->all());

        $builder->remove('companyData');

        $builderCompanyDataGroup
            ->add('isCompanyWithMultipleUsers', CheckboxType::class, [
                'required' => false,
                'label' => t('Is company with multiple users'),
                'attr' => ['class' => 'js-is-company-with-multiple-users form-line__js'],
            ]);

        $builder->add($builderCompanyDataGroup);

        $this->rebuiltFormWithNewChildrenSorting($builder, $originalFormChildrenSorting);
    }

    /**
     * @return string
     */
    public function getExtendedType()
    {
        return BillingAddressFormType::class;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired('domain_id')
            ->addAllowedTypes('domain_id', 'int')
            ->setDefaults([
                'data_class' => BillingAddressData::class,
                'attr' => ['novalidate' => 'novalidate'],
                'validation_groups' => function (FormInterface $form) {
                    $validationGroups = [ValidationGroup::VALIDATION_GROUP_DEFAULT];

                    $billingAddressData = $form->getData();
                    /* @var $billingAddressData \Shopsys\ShopBundle\Model\Customer\BillingAddressData */

                    if ($billingAddressData->companyCustomer) {
                        $validationGroups[] = BillingAddressFormType::VALIDATION_GROUP_COMPANY_CUSTOMER;
                    }

                    return $validationGroups;
                },
                'billingAddress' => null,
            ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $newChildrenSorting
     */
    protected function rebuiltFormWithNewChildrenSorting(FormBuilderInterface $builder, array $newChildrenSorting)
    {
        $formChildrenPreparedByNewSorting = [];

        foreach ($newChildrenSorting as $childName) {
            if ($builder->has($childName)) {
                $formChildrenPreparedByNewSorting[] = $builder->get($childName);
                $builder->remove($childName);
            }
        }

        foreach ($formChildrenPreparedByNewSorting as $formChild) {
            $builder->add($formChild);
        }
    }
}
