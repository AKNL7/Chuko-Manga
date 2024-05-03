<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubmitPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('postTitle', TextType::class, [
                'label' => false,
             
            ])
            ->add('postEdition')
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
            ])
            ->add('postImage', FileType::class, [
                'label' => false,
               'multiple' => true,
            ])
            // ->add('createdAt', null, [
            //     'widget' => 'single_text',
            // ])
            ->add('postPrice');
            // ->add('isValid')
    //         ->add('postCategory', EntityType::class, [
    //             'class' => Category::class,
    //             'choice_label' => 'id',
    //         ])
    //         ->add('user', EntityType::class, [
    //             'class' => User::class,
    //             'choice_label' => 'id',
            // ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
