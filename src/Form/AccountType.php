<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Industry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\AccountRepository;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;


class AccountType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ownerUserId', IntegerType::class, [
                'required' => false,
                'attr' => ['min' => 1]
            ])

            ->add('siret', TextType::class, [
                'required' => false
            ])

            ->add('accountName', TextType::class, [
                'required' => true,
                'mapped' => true,
                'setter' => function ($account, $value) {
                    $account->setAccountName($value);
                }
            ])

            ->add('parentAccount', EntityType::class, [
                'class' => Account::class,
                'choice_label' => 'accountName',
                'required' => false,
                'placeholder' => 'Aucun parent',
                'query_builder' => function (AccountRepository $repo) {
                    return $repo->createQueryBuilder('a')
                        ->orderBy('a.accountName', 'ASC');
                },
            ])

            ->add('addressline1', TextType::class, [
                'required' => false
            ])

            ->add('addressline2', TextType::class, [
                'required' => false
            ])

            ->add('city', TextType::class, [
                'required' => false
            ])

            ->add('postalcode', TextType::class, [
                'required' => false,
                'setter' => function ($account, $value) {
                    $account->setPostalcode($value);
                }
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

            ->add('website', UrlType::class, [
                'required' => false
            ])

            ->add('phoneNumber', TextType::class, [
                'required' => false
            ])

            ->add('comment', TextareaType::class, [
                'required' => false
            ])

            ->add('industry', EntityType::class, [
                'class' => Industry::class,
                'choice_label' => 'name',
                'placeholder' => '— Sélectionner —'
            ])

            ->add('riskScoring', NumberType::class, [
                'required' => false
            ])

            ->add('priorityScoring', NumberType::class, [
                'required' => false
            ])

            ->add('turnover', NumberType::class, [
                'required' => false
            ])

            ->add('numberOfEmployees', IntegerType::class, [
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Account::class,
        ]);
    }
}
