<?php

namespace App\Form;

use App\Entity\Opportunity;
use App\Entity\OpportunityStage;
use App\Enum\Priority;
use App\Repository\OpportunityStageRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class OpportunityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
            ])
            ->add('amount', NumberType::class, [
                'required' => false,
                'empty_data' => null,
            ])
            ->add('priority', EnumType::class, [
                'class' => Priority::class,
                'choice_label' => fn(?Priority $choice) => $choice?->label() ?? '',
                'placeholder' => 'Priorité',
                'required' => true,
            ])

            ->add('content', TextareaType::class, [
                'required' => false
            ])

            ->add('expected_close_date', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'empty_data' => null,
            ])

            ->add('opportunityStage', EntityType::class, [
                'class' => OpportunityStage::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisir une étape',
                'query_builder' => function (OpportunityStageRepository $repo) {
                    return $repo->createQueryBuilder('s')
                        ->orderBy('s.position', 'ASC');
                },
            ]);
        /*  ->add('opportunityContacts', CollectionType::class, [
                'entry_type' => OpportunityContactType::class,
                'entry_options' => [
                    'account' => $options['account'],
                    'opportunity' => $options['opportunity'],
                    'edit_mode' => false,
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ]);*/
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Opportunity::class,
            'account' => null,
            'opportunity' => null,
        ]);
    }
}
