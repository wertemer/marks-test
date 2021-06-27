<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Roles;
class RegisterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$builder
			->add('login',TextType::class,array('label'=>'Логин'))
			->add('username',TextType::class,array('label'=>'ФИО'))
			->add('user_type',EntityType::class,array(
				'label'	=> 'Тип пользователя',
				'class'	=> Roles::class,
				'choice_label'	=> 'name'
			))
			->add('password',PasswordType::class,array('label'=>'Пароль'))
			->add('pass_text',PasswordType::class,array('label'=>'Подтведите пароль'))
			->add('save', SubmitType::class,array('label'=>'Регистрация'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
