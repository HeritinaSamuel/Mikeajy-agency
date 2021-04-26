<?php

namespace App\Form;

use App\Entity\Booking;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate',
                DateType::class,
                $this->getConfiguration("Date d'arrivee", "La date a la quelle vous compter arriver"))
            ->add('endDate',
                DateType::class,
                $this->getConfiguration("Date de depart", "LA date a la quelle vous quite le lieux"))
            ->add('comment',
                TextareaType::class,
                $this->getConfiguration("Commentaire", "Entrer ici votre commentaire"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
