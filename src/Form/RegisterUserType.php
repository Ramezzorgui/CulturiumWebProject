<?php

// src/Form/RegisterUserType.php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class RegisterUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullname', TextType::class)
            ->add('email', TextType::class)
            ->add('mdp', TextType::class)
            ->add('tel', TextType::class)
            ->add('image', FileType::class, [
                'label' => 'Profile Picture',
                'mapped' => false, // This ensures that Symfony does not try to map this field to a property on your entity
                'required' => false, // Make this field optional
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
