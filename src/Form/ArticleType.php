<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;


class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Titre
        $builder->add('title', TextType::class, [
            'label' => 'Titre*',
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champ ne peut être vide'
                ])
            ]
        ]);

        // Contenu
        $builder->add('content', TextareaType::class, [
            'label' => 'Corps de l\'article'
        ]);

        $builder->add('publishedAt');

        // Statut
        $builder->add('isPublished', CheckboxType::class, [
            'label' => 'Publier l\'article'
        ]);

        $builder->add('author');

        // Bouton Envoyer
        $builder->add('submit', SubmitType::class, array(
            'label' => 'Enregistrer'
        ));      

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
