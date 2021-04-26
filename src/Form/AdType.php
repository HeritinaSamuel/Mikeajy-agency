<?php

namespace App\Form;

use App\Entity\Ad;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',
                TextType::class,
                $this->getConfiguration("Titre de l'annonce", "Villa Elisabeth",[
                    'help' => "Titre pour l'annonce *"
                ]))
            ->add('coverImage',
                FileType::class,
                $this->getConfiguration("Image de couverture", "villa_elisabeth.jpg",[
                    'help' => "Un image de couverture pour l'annonce *"
                ]))
            ->add('introduction',
                TextareaType::class,
                $this->getConfiguration("Introduction", "Le villa Elisabeth, avec une vue panoramique",[
                    'help' => 'Introduction de votre annonce, doit avoir au moins 20 caracteres *'
                ]))
            ->add('content',
                TextareaType::class,
                $this->getConfiguration("Contenu", "Villa confort de 200m carre, avec 2 garages, des salles ...",[
                    'help' => 'Contenu de votre annonce, doit avoir au moins 200 caracteres *'
                ]))
            ->add('price',
                MoneyType::class,
                $this->getConfiguration("Prix", "1000", [
                    'currency' => "MGA",
                    'help' => 'Devise en ariary Malagasy *'
                ]))
            ->add('rooms',
                NumberType::class,
                $this->getConfiguration("Nombre de chambre", "3",[
                    'help' => 'Nombre de chambre *'
                ]))
            // On ajoute le champ "images" dans le formulaire
            // Il n'est pas lié à la base de données (mapped à false)
            ->add('images', 
                FileType::class, [
                'label' => 'Images de l\'annonce',
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'help' => 'Ensemble des images pour votre annonce'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
            'attr' => [
                'novalidate' => 'novalidate', // comment me to reactivate the html5 validation!  🚥
            ]
        ]);
    }
}
