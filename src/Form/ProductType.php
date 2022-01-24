<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $class = "bg-gray-200 appearance-none border-2 border-gray-200
        rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white 
        focus:border-orange";

        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => $class], 'required' => true,
            ])
            ->add('price', IntegerType::class, [
                'attr' => ['class' => $class], 'required' => true,
            ])
            ->add('height', IntegerType::class, [
                'attr' => ['class' => $class], 'required' => true,
            ])
            ->add('width', IntegerType::class, [
                'attr' => ['class' => $class], 'required' => true,
            ])
            ->add('depth', IntegerType::class, [
                'attr' => ['class' => $class], 'required' => true,
            ])
            ->add('picture', TextareaType::class, [
                'attr' => ['class' => $class], 'required' => true,
            ])
            ->add('url', TextareaType::class, [
                'attr' => ['class' => $class], 'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
