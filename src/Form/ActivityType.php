<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Activity;
use App\Entity\Contact;
use App\Entity\Lead;
use App\Entity\Opportunity;
use App\Entity\User;
use App\Enum\ActivityStatusEnum;
use App\Enum\ActivityTypeEnum;
use App\Enum\Direction;
use App\Enum\Priority;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActivityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //validation métier de l'activité via le custom validator
            ->add('lead', EntityType::class, [
                'class' => Lead::class,
                'required' => false,
            ])
            ->add('account', EntityType::class, [
                'class' => Account::class,
                'required' => false,
            ])
            ->add('opportunity', EntityType::class, [
                'class' => Opportunity::class,
                'required' => false
            ])
            ->add('subject', TextType::class, [
                'required' => true,
            ])
            ->add('activityTypeEnum', EnumType::class, [
                'class' => ActivityTypeEnum::class,
                'choice_label' => fn(?ActivityTypeEnum $choice) => $choice?->label() ?? '',
                'placeholder' => 'Type d\'activité',
                'required' => true,
            ])
            ->add('activityStatusEnum', EnumType::class, [
                'class' => ActivityStatusEnum::class,
                'choice_label' => fn(?ActivityStatusEnum $choice) => $choice?->label() ?? '',
                'placeholder' => 'Status',
                'required' => true,
            ])

            ->add(
                'priority',
                EnumType::class,
                [
                    'class' => Priority::class,
                    'choice_label' => fn(?Priority $choice) => $choice?->label() ?? '',
                    'placeholder' => 'Status',
                    'required' => true,
                ]
            )
            ->add(
                'direction',
                EnumType::class,
                [
                    'class' => Direction::class,
                    'choice_label' => fn(?Direction $choice) => $choice?->label() ?? '',
                    'placeholder' => 'Direction',
                    'required' => true,
                ]
            )
            ->add('scheduled_at', DateTimeType::class, [
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'required' => false,
            ])
            ->add('due_date', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('lead', EntityType::class, [
                'class' => Lead::class,
                'choice_label' => 'id',
            ])
            ->add('opportunity', EntityType::class, [
                'class' => Opportunity::class,
                'choice_label' => 'id',
            ])
            ->add('account', EntityType::class, [
                'class' => Account::class,
                'choice_label' => 'id',
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('contact', EntityType::class, [
                'class' => Contact::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Activity::class,
        ]);
    }
}
