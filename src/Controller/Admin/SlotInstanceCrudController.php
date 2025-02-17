<?php

namespace App\Controller\Admin;

use App\Entity\SlotInstance;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SlotInstanceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SlotInstance::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(['id' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('type'),
            TextField::new('location'),
            DateTimeField::new('startDate')->setFormat('MMMM d, H:mm'),
            DateTimeField::new('endDate')->setFormat('MMMM d, H:mm'),
            IntegerField::new('minLevel'),
            IntegerField::new('maxLevel'),
            BooleanField::new('requiresEquipment'),
            BooleanField::new('allowRegistration'),
            AssociationField::new('registrations'),
            IntegerField::new('maxCapacity'),
            BooleanField::new('isCancelled'),
            TextField::new('cancelReason'),
        ];
    }
}
