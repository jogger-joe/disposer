<?php

namespace App\Form;

use App\Entity\Furniture;
use App\Entity\Housing;
use App\Entity\Service;
use App\Entity\Tag;
use App\Entity\User;
use App\Service\FurnitureTypeResolver;
use App\Service\HousingStatusResolver;
use Doctrine\ORM\EntityRepository;
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
            ->add('tags', EntityType::class, [
                'label' => 'Zusatzinformationen',
                'class' => Tag::class,
                'by_reference' => false,
                'choice_label' => function (Tag $tag) {
                    return $tag->getTitle();
                },
                'attr' => [
                    'class' => 'select2'],
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->orderBy('t.title', 'ASC');
                },
                'multiple' => true,
                'expanded' => false,
                'required' => false])
            ->add('maintainer', EntityType::class, [
                'label' => 'für die Pflege der Daten zuständiger User/Helfer',
                'class' => User::class,
                'required' => false,
                'choice_label' => function (User $user) {
                    return $user->getName();
                },
                'placeholder' => '<< kein Benutzer zugeordnet >>',
                'multiple' => false,
                'expanded' => false
            ])
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
                'label' => false,
                'class' => Service::class,
                'by_reference' => false,
                'choice_label' => function (Service $service) {
                    return $service->getTitle();
                },
                'group_by' => function () {
                    return 'Hilfe';
                },
                'attr' => [
                    'class' => 'tag-mode'],
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
