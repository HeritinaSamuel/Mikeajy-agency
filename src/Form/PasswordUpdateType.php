<?php

namespace App\Form;

use App\Entity\PasswordUpdate;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordUpdateType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword',
                PasswordType::class,
                $this->getConfiguration("Ancien mot de passe", "Renseigner votre mot de passe actuel",[
                    'help' => "Renseigner ici votre ancien mot de passe"
                ]))
            ->add('newPassword',
                PasswordType::class,
                $this->getConfiguration("Nouveau mot de passe","Nouveau mot de passe",[
                    'help' => "Renseigner ici votre nouveau mot de passe"
                ]))
            ->add('confirmPassword',
                PasswordType::class,
                $this->getConfiguration("confirmer le nouveau mot de passe","Confirmer nouveau mot de passe",[
                    'help' => "Confirmer ici votre nouveau mot de passe"
                ]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // 'data_class' => PasswordUpdate::class,
            'attr' => [
                'novalidate' => 'novalidate', // comment me to reactivate the html5 validation!  🚥
            ]
        ]);
    }
}
