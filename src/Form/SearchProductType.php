<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class SearchProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
            ])
            ->add('minWidth', IntegerType::class, [
                'required' => false,
            ])
            ->add('minHeight', IntegerType::class, [
                'required' => false,
            ])
            ->add('minDepth', IntegerType::class, [
                'required' => false,
            ])
            ->add('maxWidth', IntegerType::class, [
                'required' => false,
            ])
            ->add('maxHeight', IntegerType::class, [
                'required' => false,
            ])
            ->add('maxDepth', IntegerType::class, [
                'required' => false,
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
