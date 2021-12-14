<?php

namespace App\Form;

use App\Entity\Horaires;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

class CreerHorairesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('heureMatinDebut',TimeType::class,[
                'widget' => 'single_text',
                'required' => false,
                'attr' => [
                    'type' => 'date'
                ]])
            ->add('heureMatinFin',TimeType::class,[
                'widget' => 'single_text',
                'required' => false,
                'attr' => [
                    'type' => 'date'
                ]])
            ->add('heureApremDebut',TimeType::class,[
                'widget' => 'single_text',
                'required' => false,
                'attr' => [
                    'type' => 'date'
                ]])
            ->add('heureApremFin',TimeType::class,[
                'widget' => 'single_text',
                'required' => false,
                'attr' => [
                    'type' => 'date'
                ]])
            ->add('jourNonTravaille',CheckboxType::class,[
                'mapped' => false,
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Horaires::class,
        ]);
    }
}
