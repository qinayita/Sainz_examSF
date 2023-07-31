<?php
// src/Form/UserType.php

namespace App\Form;

use App\Entity\User;
use App\Form\DataTransformer\StringToDateTimeTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use App\Validator\CdiDateSortie;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

class UserType extends AbstractType
{
    private $transformer;

    public function __construct(StringToDateTimeTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse e-mail',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo',
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Le type de fichier est invalide. Les types de fichiers autorisés sont : {{ types }}.',
                    ]),
                ],
            ])
            ->add('secteur', ChoiceType::class, [
                'label' => 'Secteur',
                'choices' => [
                    'RH' => 'RH',
                    'Informatique' => 'Informatique',
                    'Comptabilité' => 'Comptabilité',
                    'Direction' => 'Direction',
                ],
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('typeContrat', ChoiceType::class, [
                'label' => 'Type de contrat',
                'choices' => [
                    'CDI' => 'CDI',
                    'CDD' => 'CDD',
                    'Interim' => 'Interim',
                ],
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('dateSortie', DateType::class, [ // Utilisez DateType au lieu de TextType
                'label' => 'Date de sortie',
                'required' => false,
                'widget' => 'single_text', // Afficher le widget sous forme de champ de texte simple
                'attr' => ['class' => 'js-datepicker'],
                'constraints' => [
                    new NotNull([
                        'message' => 'La date de sortie est obligatoire pour les contrats CDD et Intérim.',
                        'groups' => ['CDD', 'Interim'],
                    ]),
                    new CdiDateSortie([
                        'message' => 'Vous ne pouvez pas mettre de date de sortie pour un CDI.',
                        'isCdi' => $options['data']->getTypeContrat() === 'CDI', // Passer l'option isCdi au validateur
                    ]),
                ],
            ]);

        // Ajoutez le transformateur de date ici pour le champ dateSortie
        $builder->get('dateSortie')->addModelTransformer($this->transformer);

        $builder
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'required' => $options['password_required'],
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'password_required' => true,
        ]);
    }
}
