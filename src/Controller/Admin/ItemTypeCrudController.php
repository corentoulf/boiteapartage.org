<?php

namespace App\Controller\Admin;

use App\Entity\ItemType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ItemTypeCrudController extends AbstractCrudController
{
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
}
