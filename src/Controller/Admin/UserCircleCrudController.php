<?php

namespace App\Controller\Admin;

use App\Entity\UserCircle;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class UserCircleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UserCircle::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('user_id'),
            AssociationField::new('circle'),
            DateTimeField::new('created_at')
        ];
    }
}
