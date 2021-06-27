<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\Models;
use App\Entity\Marks;
class ModelFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mark_name',EntityType::class,array(
				'label' => 'Марка',
				'class' => Marks::class,
				'choice_label' => 'name'
			))
			->add('model_name',TextType::class,array('label' => 'Модель'))
			->add('typerul', ChoiceType::class, [
				'choices' => [
					'Левый руль' => true,
					'Правый руль' => false,
				],
				'label'	=> 'Расположение руля'
			])
			->add('save', SubmitType::class, array('label' => 'Сохранить'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
