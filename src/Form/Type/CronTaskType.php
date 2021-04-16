<?php

namespace ICS\CronBundle\Form\Type;

use ICS\CronBundle\Entity\AbstractCronTask;
use ICS\CronBundle\Entity\CronGroup;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
//Type d'extentions de formulaire
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CronTaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label' => 'Name',
        ]);

        $builder->add('description', TextareaType::class, [
            'label' => 'Description',
            'required' => false,
        ]);

        $builder->add('group', EntityType::class, [
            'label' => 'Group',
            'class' => CronGroup::class,
        ]);

        $builder->add('taskType', HiddenType::class, [
            'mapped' => false,
            'attr' => [
                'value' => $options['cronTask'],
            ],
        ]);

        $builder->add('taskFormType', HiddenType::class, [
            'mapped' => false,
            'attr' => [
                'value' => $options['cronTypeClass'],
            ],
        ]);

        $builder->add('final', HiddenType::class, [
            'mapped' => false,
            'attr' => [
                'value' => true,
            ],
        ]);

        if (null != $options['cronTypeForm']) {
            $builder->add('cronType', $options['cronTypeForm']);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
           'cronTypeClass',
           'cronTask',
           'cronTypeForm',
        ]);

        $resolver->setDefaults([
            'data_class' => AbstractCronTask::class,
            'cronTypeClass' => null,
            'cronTask' => null,
            'cronTypeForm' => null,
        ]);
    }
}
