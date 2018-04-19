<?php

namespace App\Form\Admin;

use App\Entity\Quiz;
use App\Entity\Section;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditQuizType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quizText', TextType::class, [
                'label' => '問題文',
            ])
            ->add('answerText', TextType::class, [
                'label' => '答え',
            ])
            ->add('page', TextType::class, [
                'label' => 'ページ',
            ])
            ->add('section', EntityType::class, [
                'label' => false,
                'class' => Section::class
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Quiz::class,
        ]);
    }
}
