<?php

namespace App\Form;

use App\Entity\Furniture;
use App\Entity\Housing;
use App\Entity\Service;
use App\Service\FurnitureTypeResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecordHousingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['disabled' => true])
            ->add('description', TextareaType::class, ['label' => 'Beschreibung','disabled' => true])
            ->add('missingFurnitures', EntityType::class, [
                'label' => false,
                'class' => Furniture::class,
                'by_reference' => false,
                'choice_label' => function (Furniture $furniture) {
                    return $furniture->getTitle();
                },
                'group_by' => function (Furniture $furniture) {
                    return FurnitureTypeResolver::getFurnitureTypeLabel($furniture->getType());
                },
                'attr' => [
                    'class' => 'tag-mode'],
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('f')
                        ->orderBy('f.title', 'ASC');
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
                'attr' => [
                    'class' => 'select2'],
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
