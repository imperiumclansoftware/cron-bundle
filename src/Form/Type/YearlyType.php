<?php

namespace ICS\CronBundle\Form\Type;

use ICS\CronBundle\Entity\Type\YearlyType as TypeYearlyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
//Type d'extentions de formulaire
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class YearlyType extends MonthlyType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $months = [
            'January' => TypeYearlyType::MONTH_JANUARY,
            'Febuary' => TypeYearlyType::MONTH_FEBUARY,
            'March' => TypeYearlyType::MONTH_MARCH,
            'April' => TypeYearlyType::MONTH_APRIL,
            'May' => TypeYearlyType::MONTH_MAY,
            'June' => TypeYearlyType::MONTH_JUNE,
            'July' => TypeYearlyType::MONTH_JULY,
            'August' => TypeYearlyType::MONTH_AUGUST,
            'September' => TypeYearlyType::MONTH_SEPTEMBER,
            'October' => TypeYearlyType::MONTH_OCTOBER,
            'November' => TypeYearlyType::MONTH_NOVEMBER,
            'December' => TypeYearlyType::MONTH_DECEMBER,
        ];

        $builder->add('months', ChoiceType::class, [
            'label' => 'Months',
            'choices' => $months,
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
            'data_class' => TypeYearlyType::class,
        ]);
    }
}
