<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Post;
use App\Repository\CategoryRepository;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
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
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostCrudController extends AbstractCrudController
{
    private EntityManagerInterface $entityManager;
    private AdminUrlGenerator $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
        $this->entityManager = $entityManager;
    }

    public static function getEntityFqcn(): string
    {
        return Post::class;
    }

    public function configureFields(string $pageName): iterable
    {
        // $categories = $this->entityManager->getRepository(Category::class)->findAll();
        // dump($categories);
        // // Create an array of choices for the AssociationField
        // $choices = [];
        // foreach ($categories as $category) {
        //     $choices[$category->getName()] = $category;

        return [
            IdField::new('id')->hideOnForm(),

            TextField::new('postTitle'),
            TextField::new('postEdition'),
            TextField::new('postCondition'),
            TextField::new('postResume'),
            MoneyField::new('formattedPrice', 'postPrice')->setCurrency('EUR'),
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
            AssociationField::new('postCategory'),
           
        
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
        $approvePost = Action::new('approvePost', 'Approve', 'fa fa-check')
        ->linkToCrudAction('approvePost')
        ->addCssClass('btn btn-success');

        $rejectPost = Action::new('rejectPost', 'Reject', 'fa fa-ban')
        ->linkToCrudAction('rejectPost')
        ->addCssClass('btn btn-danger');

        return $actions
            ->add(Crud::PAGE_INDEX, $approvePost)
            ->add(Crud::PAGE_INDEX, $rejectPost);
    }

    public function approvePost(AdminContext $context, EntityManagerInterface $entityManager): RedirectResponse
    {
        /** @var Post $post */
        $post = $context->getEntity()->getInstance();
        $post->setIsValid(true);
        $entityManager->persist($post);
        $entityManager->flush();

        $this->addFlash('success', 'Post approved successfully!');

        $url = $context->getReferrer() ?? $this->adminUrlGenerator->setController(self::class)->setAction(Crud::PAGE_INDEX)->generateUrl();
        return $this->redirect($url);
    }

    public function rejectPost(AdminContext $context, EntityManagerInterface $entityManager)
    {
        try {
        /** @var Post $post */
        $post = $context->getEntity()->getInstance();
        $entityManager->remove($post);
        $entityManager->flush();

        $this->addFlash('success', 'Post rejected successfully!');

           } catch (\Exception $e) {
        $this->addFlash('error', 'An error occurred while rejecting the post.');
        // Log the error or perform additional error handling if needed
           }
$url = $this->container->get(AdminUrlGenerator::class)
->setController(PostCrudController::class)
->setAction(Action::INDEX)
->unset('entityId')
->generateUrl();

        return $this->redirect($url);
        // $url = $this->adminUrlGenerator->setController(self::class)->setAction(Crud::PAGE_INDEX)->generateUrl();
        // return $this->redirect($url);
    }

  
    }

