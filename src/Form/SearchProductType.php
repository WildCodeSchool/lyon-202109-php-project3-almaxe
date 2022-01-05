<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;

class SearchProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
            ])
            ->add('width', IntegerType::class, [
                'required' => false,
            ])
            ->add('height', IntegerType::class, [
                'required' => false,
            ])
            ->add('depth', IntegerType::class, [
                'required' => false,
            ])
            ->add('criteriaWidth', ChoiceType::class, [
                'choices' => ['Minimum' => 'min', 'Maximum' => 'max'],
                'required' => true,
            ])
            ->add('criteriaHeight', ChoiceType::class, [
                'choices' => ['Minimum' => 'min', 'Maximum' => 'max'],
                'required' => true,
            ])
            ->add('criteriaDepth', ChoiceType::class, [
                'choices' => ['Minimum' => 'min', 'Maximum' => 'max'],
                'required' => true,
            ])
            ->add('price', IntegerType::class, [
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
