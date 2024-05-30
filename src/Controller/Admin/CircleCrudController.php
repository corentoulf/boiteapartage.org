<?php

namespace App\Controller\Admin;

use App\Entity\Circle;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CircleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Circle::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            TextField::new('short_id'),
            TextField::new('address'),
            TextField::new('postcode'),
            TextField::new('city'),
            TextField::new('country'),
            AssociationField::new('created_by'),
            DateTimeField::new('createdAt'),


        ];
    }
}
