<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Form\Admin;

use Shopsys\FrameworkBundle\Form\Admin\Customer\CustomerFormType;
use Shopsys\FrameworkBundle\Form\GroupType;
use Shopsys\ShopBundle\Model\Customer\BillingAddress;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;

class CustomerFormTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builderUserDataGroup = $builder->get('userData');
        $builderSystemDataGroup = $builderUserDataGroup->get('systemData');

        $builderSystemDataGroup->add('discount', IntegerType::class, [
            'constraints' => [
                new Constraints\NotBlank([
                    'message' => 'Please enter discount percentage',
                ]),
                new Constraints\Range([
                    'min' => 0,
                    'max' => 100,
                    'maxMessage' => 'Discount percentage should be {{ limit }} or less.',
                    'minMessage' => 'Discount percentage should be {{ limit }} or more.',
                    'invalidMessage' => 'Discount percentage needs to be valid number with range between 0 and 100.',
                ]),
            ],
            'label' => t('Discount'),
        ]);

        // remove field and add again to sort it after new discount field
        if ($options['user'] !== null) {
            $builderRegistrationDateField = $builderSystemDataGroup->get('createdAt');
            $builderSystemDataGroup->remove('createdAt');
            $builderSystemDataGroup->add($builderRegistrationDateField);
        }

        if ($options['billingAddress'] !== null && $options['billingAddress']->isCompanyWithMultipleUsers()) {
            $builder
                ->remove('orders')
                ->remove('deliveryAddressData');

            $builderUserDataGroup
                ->remove('personalData')
                ->remove('registeredCustomer');

            $builderSystemDataGroup->remove('formId');

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
