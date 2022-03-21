<?php

namespace App\Form;

use App\Entity\Service;
use App\Entity\Supporter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SupporterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Name', 'attr' => ['placeholder' => 'Vorname, Nachname']])
            ->add('contact', TextType::class, ['label' => 'Kontakt', 'attr' => ['placeholder' => 'Telefonnummer oder EMail']])
            ->add('information', TextareaType::class, ['label' => 'Informationen', 'attr' => ['placeholder' => 'z.B. Transport, Aufbau, Anpacken, Verfügbarkeit von bis o.ä.']])
            ->add('availableServices', EntityType::class, [
                'label' => 'leistbare Hilfen',
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
            'data_class' => Supporter::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'supporter'
        ]);
    }
}
