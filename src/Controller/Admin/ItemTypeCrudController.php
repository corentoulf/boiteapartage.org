<?php

namespace App\Controller\Admin;

use App\Entity\ItemType;
use Doctrine\ORM\Mapping\Entity;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class ItemTypeCrudController extends AbstractCrudController
{
    /**
     * @var \EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator
     */
    public $adminUrlGenerator;
    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return ItemType::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('code'),
            TextField::new('label'),
            AssociationField::new('category'),
            TextField::new('property_1_label'),
            TextField::new('property_2_label'),
            TextField::new('property_3_label'),
            TextField::new('property_4_label'),
            TextField::new('property_5_label'),
            
        ];
    }

    // public function edit(AdminContext $context)
    // {
    //     if ($context->getRequest()->query->has('duplicate')) {
    //         $entity = $context->getEntity()->getInstance();
    //         /** @var Entity $cloned */
    //         $cloned = clone $entity;
    //         // $cloned->setCreatedAt(new \DateTime('now'));
    //         $context->getEntity()->setInstance($cloned);
    //     }

    //     return parent::edit($context);
    // }

    // public function configureActions(Actions $actions): Actions
    // {
    //     $duplicate = Action::new('duplicate', 'Dupliquer')
    //         ->setIcon('fa fa-copy')
    //         ->linkToUrl(
    //             fn (ItemType $entity) => $this->adminUrlGenerator
    //                 ->setAction(Action::EDIT)
    //                 ->generateUrl()
    //         );

    //     return $actions
    //         ->add(Crud::PAGE_INDEX, $duplicate);
    // }

    public function edit(AdminContext $context)
    {
        if ($context->getRequest()->query->has('duplicate')) {
            $entity = $context->getEntity()->getInstance();
            /** @var Entity $cloned */
            $cloned = clone $entity;
            $context->getEntity()->setInstance($cloned);
        }

        return parent::edit($context);
    }

    public function configureActions(Actions $actions): Actions
    {
        $duplicate = Action::new('duplicate', 'Dupliquer')
            ->setIcon('fa fa-copy')
            ->linkToUrl(
                fn (ItemType $entity) => $this->adminUrlGenerator
                    ->setAction(Action::EDIT)
                    ->setEntityId($entity->getId())
                    ->set('duplicate', '1')
                    ->generateUrl()
            );

      $actions
            ->add(Crud::PAGE_INDEX, $duplicate);

      return parent::configureActions($actions);
        }

}