<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'label' => 'Nom du produit',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ecrire ici le nom...'  
                ]
            ])
            ->add('prix', MoneyType::class,[
                'label' => 'Prix du produit',
                'required' => false,
                'divisor' => 100,
            ])
            ->add('imagePath',TextType::class,[
                'label' => 'Image de la catégorie',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ajouter le chemin de l\'image ici'
                ]
            ])
            ->add('category', EntityType::class, [
                'label' => 'Image de la catégorie',
                'placeholder' => '-- Choisir --',
                'required' => false,
                'class' => Category::class
            ]);
    
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
