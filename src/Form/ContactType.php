<?php

namespace App\Form;

use App\Entity\Contact;
use App\Form\FormListenerFactory;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Enum\Title;
use Symfony\Component\Form\Extension\Core\Type\EnumType;

class ContactType extends AbstractType
{
    public function __construct(private FormListenerFactory $listenerFactory) {}

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
                'setter' => function ($contact, $value) {
                    $contact->setFirstName($value);
                }
            ])
            ->add('lastName', TextType::class, [
                'required' => true,
                'setter' => function ($contact, $value) {
                    $contact->setLastName($value);
                }
            ])
            ->add('jobtitle', TextType::class, [
                'required' => false,
            ])
            ->add('phoneNumber', TextType::class, [
                'required' => false
            ])
            ->add('mobile', TextType::class, [
                'required' => false
            ])
            ->add('email', EmailType::class, [
                'required' => false
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
