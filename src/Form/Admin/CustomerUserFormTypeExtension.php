<?php

declare(strict_types=1);

namespace App\Form\Admin;

use Shopsys\FrameworkBundle\Form\Admin\Customer\User\CustomerUserFormType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;

class CustomerUserFormTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builderSystemDataGroup = $builder->get('systemData');

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
        if ($options['customerUser'] !== null) {
            $builderRegistrationDateField = $builderSystemDataGroup->get('createdAt');
            $builderSystemDataGroup->remove('createdAt');
            $builderSystemDataGroup->add($builderRegistrationDateField);
        }
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(['customerUser', 'domain_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedTypes(): iterable
    {
        yield CustomerUserFormType::class;
    }
}
