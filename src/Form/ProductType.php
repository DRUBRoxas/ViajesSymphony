<?php

namespace App\Form;

use App\Entity\Product;
use Doctrine\Common\Annotations\Annotation\Enum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Factory\Cache\PreferredChoice;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tipo', ChoiceType::class, [
                'choices' => [
                    'Viajes' => 'viajes',
                    'Hoteles' => 'hoteles',
                    'Actividades' => 'actividades',
                ],   
            ])
            ->add('nombre', TextType::class, [
                'label' => 'Nombre',
            ])
            ->add('descripcion', null, [
                'label' => 'Descripción',
            ])
            ->add('FicheroImagen', FileType::class, [
                'label' => "Imagen ",
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Sólo se admite ',
                    ])
                ]
            ])
            ->add('precio', NumberType::class, [
                'label' => 'Precio',
            ])
            ->add('descuento', IntegerType::class, [
                'label' => 'Descuento (no porcentaje)',
            ])
            ->add('fecha')
            ->add('hora')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
