<?php

namespace ICS\CronBundle\Form\Type;

use ICS\CronBundle\Entity\Type\WeeklyType as TypeWeeklyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
//Type d'extentions de formulaire
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WeeklyType extends DailyType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $weekDays = [
            'Monday' => TypeWeeklyType::DAY_MONDAY,
            'Thuesday' => TypeWeeklyType::DAY_THUESDAY,
            'Wednesday' => TypeWeeklyType::DAY_WEDNESDAY,
            'Thursday' => TypeWeeklyType::DAY_THURSDAY,
            'Friday' => TypeWeeklyType::DAY_FRIDAY,
            'Saturday' => TypeWeeklyType::DAY_SATURDAY,
            'Sunday' => TypeWeeklyType::DAY_SUNDAY,
        ];

        $builder->add('weekdays', ChoiceType::class, [
            'label' => 'Week days',
            'choices' => $weekDays,
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
            'data_class' => TypeWeeklyType::class,
        ]);
    }
}
