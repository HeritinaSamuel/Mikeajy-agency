<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',
                TextType::class,
                $this->getConfiguration("Prenom","Dupond",[
                    'help'=>"Prenom, doit avoir au moins 3 caracteres"
                ]))
            ->add('lastName',
                TextType::class,
                $this->getConfiguration("Nom", "La Riviere",[
                    'help'=>"Nom de famille, doit avoir au moins 3 caracteres"
                ]))
            ->add('email',
                EmailType::class,
                $this->getConfiguration("Adresse email", "lariviere.dupond@gmail.com",[
                    'help' => "Mentionner votre email"
                ]))
            ->add('avatar',
                FileType::class, [
                    'label' => 'Photo de profil',
                    'multiple' => false,
                    'mapped' => false,
                    'required' => false,
                    "help" => "Votre photo de profil doit etre moins de 100Ko, et de extension (jpeg, jpg, png)"
                ])
            ->add('biographie',
                TextareaType::class,
                $this->getConfiguration("Introduction", "Dupon La Riviere expert commercant",[
                    'help' => "Presentez vous "
                ]))
            ->add('description',
                TextareaType::class,
                $this->getConfiguration("Description", "Commercant plus de 20 ans d'experience en e.marketing",[
                    'help' => "Decriviez vous, votre expertise, votre experience"
                ]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => [
                'novalidate' => 'novalidate', // comment me to reactivate the html5 validation!  🚥
            ]
        ]);
    }
}
