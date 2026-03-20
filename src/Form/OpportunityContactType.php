<?php

namespace App\Form;

use App\Entity\Contact;
use App\Entity\ContactRole;
use App\Entity\Opportunity;
use App\Entity\OpportunityContact;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class OpportunityContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', HiddenType::class)
            ->add('contact', EntityType::class, [
                'class' => Contact::class,
                'choice_label' => 'displayName',
                'placeholder' => 'Choisir un contact',
                'disabled' => false,
                'query_builder' => !$options['edit_mode']
                    ? function (EntityRepository $er) use ($options) {
                        return $er->createQueryBuilder('c')
                            ->where('c.account = :account')
                            ->andWhere('c.id NOT IN (
                            SELECT IDENTITY(oc.contact) FROM App\Entity\OpportunityContact oc
                            WHERE oc.opportunity = :opportunity
                        )')
                            ->setParameter('account', $options['account'])
                            ->setParameter('opportunity', $options['opportunity']);
                    }
                    : null,
            ])
            ->add('contact_role', EntityType::class, [
                'class' => ContactRole::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisir un rôle',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OpportunityContact::class,
            'account' => null,
            'opportunity' => null,
            'edit_mode' => false,
        ]);
    }
}
