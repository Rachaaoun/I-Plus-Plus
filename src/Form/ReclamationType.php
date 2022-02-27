<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sujet_rec')
            ->add('user_id')
            ->add('enregistrer',SubmitType::class)
            ->add('typereclamations')
            ->add('niveau', ChoiceType::class, [
                'choices'  => [
                    'faible'=>0,
                    'moyenne' => 1,
                    'eleve'=>2,
                    
                ],
            ])
            ->add('enregistrer',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
