<?php

declare(strict_types=1);

namespace App\Form\Admin;

use App\Model\Transport\Transport;
use Shopsys\FrameworkBundle\Form\Admin\Transport\TransportFormType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;

class TransportFormTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builderTransportDataGroup = $builder->get('basicInformation');
        $builderTransportDataGroup->add('type', ChoiceType::class, [
            'required' => true,
            'choices' => [
                t('Basic') => Transport::TYPE_DEFAULT,
                t('Zásilkovna') => Transport::TYPE_ZASILKOVNA,
            ],
            'constraints' => [
                new Constraints\NotBlank(['message' => 'Please check the correctness of all data filled.']),
            ],
            'label' => t('Shipping type'),
        ]);
        $builder->add($builderTransportDataGroup);
    }

    /**
     * {@inheritdoc}
     */
    public static function getExtendedTypes(): iterable
    {
        yield TransportFormType::class;
    }
}
