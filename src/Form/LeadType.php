<?php

namespace App\Form;

use App\Entity\Lead;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LeadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('firstName')
            ->add('lastName')
            ->add('email')
            ->add('phoneNumber')
            ->add('companyName')
            ->add('jobtitle')
            ->add('source')
            ->add('subject')
            ->add('comment')
            ->add('converted_at', null, [
                'widget' => 'single_text'
            ])
            ->add('status')
            ->add('createdAt', null, [
                'widget' => 'single_text'
            ])
            ->add('lastModifiedAt', null, [
                'widget' => 'single_text'
            ])
            ->add('deletedAt', null, [
                'widget' => 'single_text'
            ])
            ->add('slug')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lead::class,
        ]);
    }
}
