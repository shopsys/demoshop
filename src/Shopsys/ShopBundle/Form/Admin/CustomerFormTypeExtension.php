<?php

namespace Shopsys\ShopBundle\Form\Admin;

use Shopsys\FrameworkBundle\Form\Admin\Customer\CustomerFormType;
use Shopsys\FrameworkBundle\Form\GroupType;
use Shopsys\ShopBundle\Model\Customer\BillingAddress;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerFormTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['billingAddress'] !== null && $options['billingAddress']->getIsCompanyWithMultipleUsers()) {
            $builder
                ->remove('orders')
                ->remove('userData')
                ->remove('deliveryAddressData');

            $builderCompanyUsersDataGroup = $builder->create('companyUsersDataGroup', GroupType::class, [
                'label' => t('Company users'),
            ]);

            $builderCompanyUsersDataGroup
                ->add('companyUsersData', CompanyUsersFormType::class, [
                    'required' => false,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'entry_type' => CompanyUserFormType::class,
                    'error_bubbling' => false,
                    'render_form_row' => false,
                ]);

            $builder
                ->add($builderCompanyUsersDataGroup);
        }
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(['user', 'domain_id', 'billingAddress'])
            ->setAllowedTypes('billingAddress', [BillingAddress::class, 'null']);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return CustomerFormType::class;
    }
}
