<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('category', EntityType::class, [
                'class' => Category::class, // Je suis obligé de lui donner des options ! Je lui dit que je parle de l'entité Catégory // use App\Entity\Category;
                // Maintenant je dois expliquer à mon champ ce qu'il doit présenter comme information. Je vais avoir une select list avec les catégories, et ben que'est-ce que je veux ? je veux les titres des catégories pour pouvoir choisir celle qui me plait. Je vais donc appeler l'option qui s'appelle choice_label qui à pour label title.
                'choice_label' => 'title'])
            ->add('content')
            ->add('image')
            /* ->add('createdAt') */
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
