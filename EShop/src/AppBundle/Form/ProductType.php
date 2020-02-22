<?php

namespace AppBundle\Form;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')
                ->add('price')
                ->add('description', TextareaType::class)
                ->add('category', EntityType::class,
                    [
                        'class' => Category::class,
                        'choice_label' => 'name',
                        'placeholder' => 'Choose category'
                    ])
                ->add('tags', EntityType::class,
                    [
                        'class' => Tag::class,
                        'choice_label' => 'name',
                        'multiple' => true //множествен
                    ])
                ->add('country', TextType::class)
                ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', Product::class);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
