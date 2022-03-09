<?php

namespace App\Form;

use App\Entity\Furniture;
use App\Entity\Housing;
use App\Entity\Service;
use App\Service\FurnitureTypeResolver;
use App\Service\HousingStatusResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HousingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('description', TextareaType::class, ['label' => 'Beschreibung'])
            ->add('status', ChoiceType ::class, [
                'label' => 'Status',
                'choices' => HousingStatusResolver::getHousingStatusChoices(),
                'multiple' => false,
                'expanded' => false])
            ->add('missingFurnitures', EntityType::class, [
                'label' => 'benötigte Einrichtungsgegenstände',
                'class' => Furniture::class,
                'by_reference' => false,
                'choice_label' => function (Furniture $furniture) {
                    return $furniture->getTitle();
                },
                'group_by' => function(Furniture $furniture) {
                    return FurnitureTypeResolver::getFurnitureTypeLabel($furniture->getType());
                },
                'multiple' => true,
                'expanded' => false,
                'required' => false])
            ->add('missingServices', EntityType::class, [
                'label' => 'benötigte Dienstleistungen',
                'class' => Service::class,
                'by_reference' => false,
                'choice_label' => function (Service $service) {
                    return $service->getTitle();
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
