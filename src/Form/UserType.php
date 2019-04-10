<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
        ;
        if ($options['route'] == 'register') {
            $builder->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options' => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat')
            ));
        }
        elseif ($options['route'] == 'security.edit') {
            $builder
                ->add('plainPassword', HiddenType::class, array( // Evite l'erreur plainPassword blank
                    'data' => "Lorem ipsum",
                ));
        }
        elseif ($options['route'] == 'user.edit') {
            $builder
                ->add('firstname', TextType::class)
                ->add('lastname', TextType::class)
                ->add('plainPassword', HiddenType::class, array( // Evite l'erreur plainPassword blank
                    'data' => "Lorem ipsum",
                ))
                ->add('company', EntityType::class, array(
                    'required' => false,
                    'class' => Company::class,
                    'choice_label' => 'name',
                ));
        } elseif ($options['route'] == 'security.password') {
            $builder
                ->remove('email')
                ->remove('firstname')
                ->remove('lastname')
                ->add('oldPassword', PasswordType::class, array(
                    'mapped' => false
                ))
                ->add('plainPassword', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'first_options' => array('label' => 'New password'),
                    'second_options' => array('label' => 'Repeat new password'),
                ));
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'route' => null,
        ]);
    }
}
