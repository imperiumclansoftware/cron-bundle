<?php

namespace ICS\CronBundle\Form\Type;

use ICS\CronBundle\Entity\Type\MonthlyType as TypeMonthlyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
//Type d'extentions de formulaire
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MonthlyType extends DailyType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $monthDays = [];

        for ($i = 1; $i <= 28; ++$i) {
            $monthDays[$i] = $i;
        }

        $monthDays['Last Day'] = 'lastday';

        $builder->add('monthDays', ChoiceType::class, [
            'label' => 'Month days',
            'choices' => $monthDays,
            'attr' => [
                'class' => 'form-inline',
            ],
            'choice_attr' => function ($choice, $key, $value) {
                return ['class' => 'ml-3'];
            },
            'multiple' => true,
            'expanded' => true,
            'choice_translation_domain' => 'cron',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TypeMonthlyType::class,
        ]);
    }
}
