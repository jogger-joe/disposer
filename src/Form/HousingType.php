<?php

namespace App\Form;

use App\Entity\Furniture;
use App\Entity\Housing;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HousingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description', TextareaType::class, ['label' => 'Beschreibung'])
            ->add('status', ChoiceType ::class, [
                'label' => 'Status',
                'choices' => [
                    'frei' => 0,
                    'teilweise belegt' => 1,
                    'vollständig belegt' => 2,
                    'bezugsbereit' => 3,
                ],
                'multiple' => false,
                'expanded' => true,])
            ->add('furnitures', EntityType::class, [
                'label' => 'Einrichtungsgegenstände',
                'class' => Furniture::class,
                'by_reference' => false,
                'choice_label' => function (Furniture $furniture) {
                    return sprintf('%s (%s)', $furniture->getTitle(), $furniture->getType() == 1 ? 'Standard' : 'Sonstige');
                },
                'group_by' => function(Furniture $choice, $key, $value) {
                    return $choice->getType() == 0 ? 'Sonstige' : 'Standard';
                },
                'multiple' => true,
                'expanded' => false,
                'required' => false])
            ->add('save', SubmitType::class, ['label' => 'Speichern']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Housing::class,
        ]);
    }
}
