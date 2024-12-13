<?php

namespace App\Form;

use App\Entity\Adverts;
use App\Entity\Categories;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class AdvertsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('price', NumberType::class, [
                'label' => 'Price',
                'html5' => true, // Enables HTML5 validation (optional)
                'attr' => ['min' => 0, 'step' => '0.01'], // Additional HTML attributes
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Price cannot be empty.',
                    ]),
                    new Assert\Positive([
                        'message' => 'Price must be a positive number.',
                    ]),
                ],
            ])
            ->add('location')
            ->add('image', FileType::class, [
                'label' => 'Image (JPEG, PNG)',
                'mapped' => false, // This is important to handle the file separately
                'required' => false,
            ])
            ->add('category', EntityType::class, [
                'class' => Categories::class,
                'choice_label' => 'title', // Assuming 'name' is the field you want to display
                'multiple' => true, // Set to true if you want to allow multiple selections
                'expanded' => false, // Set to true if you want checkboxes instead of a dropdown
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adverts::class,
        ]);
    }
}
