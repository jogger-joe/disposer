<?php

namespace App\Form;

use App\Entity\Supporter;
use App\Entity\User;
use App\Service\RoleResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Name'])
            ->add('email', TextType::class, ['label' => 'Email'])
            ->add('roles', ChoiceType ::class, [
                'label' => 'Rollen/Rechte',
                'choices' => RoleResolver::getRoleChoices(),
                'multiple' => true,
                'expanded' => true])->add('save', SubmitType::class, ['label' => 'Speichern']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'user'
        ]);
    }
}
