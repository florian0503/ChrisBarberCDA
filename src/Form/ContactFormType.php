<?php
// src/Form/ContactFormType.php
namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label'       => 'Prénom*',
                'constraints' => [new Assert\NotBlank()],
            ])
            ->add('lastName', TextType::class, [
                'label'       => 'Nom*',
                'constraints' => [new Assert\NotBlank()],
            ])
            ->add('email', EmailType::class, [
                'label'       => 'E-mail*',
                'constraints' => [new Assert\NotBlank(), new Assert\Email()],
            ])
            ->add('phone', TelType::class, [
                'label'       => 'Numéro de téléphone*',
                'constraints' => [new Assert\NotBlank()],
            ])
            ->add('objectif', ChoiceType::class, [
                'label'       => 'Ton objectif*',
                'choices'     => [
                    'Apprendre un métier' => 'apprendre',
                    'Perfectionnement'     => 'perfectionnement',
                    'Découvrir'           => 'decouvrir',
                ],
                'placeholder' => 'Sélectionner',
                'constraints' => [new Assert\NotBlank()],
            ])
            ->add('niveau', ChoiceType::class, [
                'label'       => 'Ton niveau*',
                'choices'     => [
                    'Débutant'      => 'debutant',
                    'Intermédiaire' => 'intermediaire',
                    'Avancé'        => 'avance',
                ],
                'placeholder' => 'Sélectionner',
                'constraints' => [new Assert\NotBlank()],
            ])
            ->add('source', TextareaType::class, [
                'label'       => 'Comment nous as-tu connu ?*',
                'constraints' => [new Assert\NotBlank()],
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
