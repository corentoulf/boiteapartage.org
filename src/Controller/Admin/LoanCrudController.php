<?php

namespace App\Controller\Admin;

use App\Entity\Loan;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class LoanCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Loan::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('item'),
            AssociationField::new('borrower'),
            AssociationField::new('lender'),
            TextField::new('status'),
            DateTimeField::new('createdAt'),
            DateTimeField::new('requestedStartDate'),
            DateTimeField::new('requestedEndDate'),
            DateTimeField::new('startDate'),
            DateTimeField::new('endDate'),

        ];
    }
}
