<?php

namespace App\Form;

use App\Entity\Furniture;
use App\Service\FurnitureTypeResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FurnitureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('amount', NumberType::class, ['label' => 'verfügbar', 'required' => false])
            ->add('type', ChoiceType::class, [
                'label' => 'Typ',
                'choices' => FurnitureTypeResolver::getFurnitureTypeChoices(),
                'multiple' => false,
                'expanded' => false])
            ->add('save', SubmitType::class, ['label' => 'Speichern'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Furniture::class,
        ]);
    }
}
