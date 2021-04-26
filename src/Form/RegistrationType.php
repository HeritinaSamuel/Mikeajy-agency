<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends ApplicationType
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
            ->add('email',
                EmailType::class,
                $this->getConfiguration("Adresse email", "lariviere.dupond@gmail.com",[
                    'help' => "Mentionner votre email"
                ]))
            ->add('hash',
                PasswordType::class,
                $this->getConfiguration("Mot de passe", "Mot de passe",[
                    'help' => "Renseigner ici le mot de passe"
                ]))
            ->add('passwordConfirm',
                PasswordType::class,
                $this->getConfiguration("Confirmation de mot de passe", "Confirmation de mot de passe",[
                    'help' => "Confirmer ici votre mot de passe"
                ]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
