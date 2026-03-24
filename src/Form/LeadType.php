<?php

namespace App\Form;

use App\Entity\Lead;
use App\Enum\Status;
use App\Enum\Title;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class LeadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', EnumType::class, [
                'class' => Title::class,
                'choice_label' => fn(Title $choice) => $choice->name,
                'placeholder' => 'Civilité',
                'required' => false,
            ])
            ->add('firstName', TextType::class, [
                'required' => false,
                'setter' => function ($lead, $value) {
                    $lead->setFirstName($value);
                }
            ])
            ->add('lastName', TextType::class, [
                'required' => true,
                'setter' => function ($lead, $value) {
                    $lead->setLastName($value);
                }
            ])
            ->add('email', EmailType::class, [
                'required' => false
            ])
            ->add('phoneNumber', TextType::class, [
                'required' => false
            ])
            ->add('companyName', TextType::class, [
                'required' => true,
                'mapped' => true,
                'setter' => function ($lead, $value) {
                    $lead->setCompanyName($value);
                }
            ])
            ->add('jobtitle', TextType::class, [
                'required' => false,
            ])
            ->add('source', TextType::class, [
                'required' => false,
            ])
            ->add('subject', TextType::class, [
                'required' => true,
            ])
            ->add('comment', TextareaType::class, [
                'required' => false,
            ])

            ->add('country', CountryType::class, [
                'required' => true,
                'label' => 'Pays',
                'data' => 'FR',
                'preferred_choices' => ['FR', 'BE', 'CH', 'LU'],
                'setter' => function ($account, $value) {
                    $account->setCountry($value);
                }
            ])

            ->add('converted_at', null, [
                'widget' => 'single_text'
            ])
            ->add('status', EnumType::class, [
                'class' => Status::class,
                'choice_label' => fn(Status $choice) => $choice->name,
                'placeholder' => 'Statut du lead',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lead::class,
        ]);
    }
}
