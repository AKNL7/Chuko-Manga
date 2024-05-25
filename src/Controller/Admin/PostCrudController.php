<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\BatchActionDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Configurator\ImageConfigurator;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Post::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            TextField::new('postTitle'),
            TextField::new('postEdition'),
            TextField::new('postCondition'),
            TextField::new('postResume'),
            MoneyField::new('postPrice')->setCurrency('EUR'),
            BooleanField::new('isValid')->setLabel('Is Valid'),
            AssociationField::new('user', 'User')
                ->formatValue(function ($value, $entity) {
                    if ($entity->getUser()) {
                        return $entity->getUser()->getLastName();
                    } else {
                        return 'Unknown';
                    }
                })
                ->hideOnForm(),
            AssociationField::new('postCategory', 'Category')->autocomplete(),
            DateTimeField::new('createdAt')->hideOnForm(),
            CollectionField::new('postImages', 'Image')
                ->setTemplatePath('admin\image.html.twig')
                ->onlyOnIndex(), // Affichez les images uniquement dans la vue d'index

            ImageField::new('postImages', 'Image')
                ->setBasePath('/uploads') // Spécifiez le chemin de base où se trouvent les images
                ->onlyOnDetail(),

        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
        
            ->addBatchAction(Action::new('approve', 'Approve Post')
                ->linkToCrudAction('approvePost')
                ->addCssClass('btn btn-primary')
                ->setIcon('fa fa-user-check'));

            // ->addBatchAction(Action::new('reject', 'Reject Post')
            //     ->linkToCrudAction('rejectPost')
            //     ->addCssClass('btn btn-danger')
            //     ->setIcon('fa fa-user-times'));
    }
    public function approvePost(BatchActionDto $batchActionDto)
    {
        $className = $batchActionDto->getEntityFqcn();
        $entityManager = $this->container->get('doctrine')->getManagerForClass($className);
        foreach ($batchActionDto->getEntityIds() as $id) {
            $user = $entityManager->find($className, $id);
            $user->approve();
        }

        $entityManager->flush();

        return $this->redirect($batchActionDto->getReferrerUrl());
    }
   

    }

