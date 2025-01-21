<?php 

namespace App\Form;

use App\Entity\Course;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pointDepart', TextType::class, [
                'label' => 'Point de départ'
            ])
            ->add('pointArrivee', TextType::class, [
                'label' => 'Point d\'arrivée'
            ])
            ->add('dateHeure', DateTimeType::class, [
                'label' => 'Date et Heure',
                'widget' => 'single_text', // Pour avoir un champ de type datetime-local
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Course::class,  // L'entité associée
        ]);
    }
}


