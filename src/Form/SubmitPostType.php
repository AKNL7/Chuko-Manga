<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Files;
use App\Entity\Post;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubmitPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('postTitle', TextType::class, [
                'label' => 'Titre',
                'attr' => ['class' => 'box'],

            ])
            ->add('postEdition', TextType::class, [
                'label' => 'Edition',
                'attr' => ['class' => 'box'],
            ])
            ->add('postCondition', ChoiceType::class, [

                'choices' => [
                    'Neuf' => 'Neuf',
                    'Comme Neuf' => 'Comme Neuf',
                    'Bon Etat' => 'Bonne Etat',
                    'Usé' => 'Usé',
                    'Quelques pages déchirés' => 'Quelques    pages déchirés',
                    'Mauvais Etat' => 'Mauvais Etat',
                ],
                'label' => false,
                'attr' => ['class' => 'box'],

            ])
            ->add('postImages', FileType::class, [

                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'attr' => ['class' => 'box'],
            ])

            ->add(
                'postResume',
                TextareaType::class,
                [
                    'label' => 'Résumé, n`hesitez pas à écrire le résumé qui se trouve en quatriéme de couverture :) ',
                    'attr' => ['class' => 'box'],

                ]
            )
            // ->add('createdAt', null, [
            //     'widget' => 'single_text',
            // ])
            ->add('postPrice', IntegerType::class, [
                'label' => 'Prix',
                'attr' => ['class' => 'box'],
            ])
            // ->add('isValid')
            // ->add('postCategory', EntityType::class, [
            //     'class' => Category::class,
            //     'choice_label' => 'name',
            // ])
            // ->add('user', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'id',
            // ]);

            ->add('submit', SubmitType::class, [
            'attr' => ['class' => 'btn'],
            'label' => 'Soumettre mon anonce',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,


        ]);
    }
}
