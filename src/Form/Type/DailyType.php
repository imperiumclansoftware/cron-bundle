<?php

namespace ICS\CronBundle\Form\Type;

use ICS\CronBundle\Entity\Type\DailyType as TypeDailyType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
//Type d'extentions de formulaire
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DailyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('hour', NumberType::class, [
            'label' => 'Hour',
            'attr' => [
                'min' => 0,
                'max' => 23,
                'step' => 1,
                'class' => 'custom-range range-with-value',
                'unite' => 'heures',
            ],
        ]);

        $builder->add('minute', NumberType::class, [
            'label' => 'Minute',
            'attr' => [
                'min' => 0,
                'max' => 59,
                'step' => 1,
                'class' => 'custom-range',
                'unite' => 'minutes',
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TypeDailyType::class,
        ]);
    }
}
