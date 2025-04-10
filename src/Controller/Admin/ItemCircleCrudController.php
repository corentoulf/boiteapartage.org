<?php

namespace App\Controller\Admin;

use App\Entity\ItemCircle;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ItemCircleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ItemCircle::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            AssociationField::new('item'),
            AssociationField::new('circle'),
        ];
    }
}
