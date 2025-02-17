<?php

namespace App\Controller\Admin;

use App\Entity\RecurringSlot;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;

class RecurringSlotCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RecurringSlot::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(['id' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addColumn(4),
            IdField::new('id')->hideOnForm(),
            TextField::new('type'),
            ChoiceField::new('dayOfWeek')->setChoices([
                'Lundi'    => '1',
                'Mardi'    => '2',
                'Mercredi' => '3',
                'Jeudi'    => '4',
                'Vendredi' => '5',
                'Samedi'   => '6',
                'Dimanche' => '7',
            ]),
            TimeField::new('startTime')->setFormat('H:mm'),
            TimeField::new('endTime')->setFormat('H:mm'),
            FormField::addColumn(4),
            IntegerField::new('maxCapacity'),
            IntegerField::new('minLevel'),
            IntegerField::new('maxLevel'),
            TextField::new('location'),
            BooleanField::new('requiresEquipment'),
            BooleanField::new('allowRegistration'),
            BooleanField::new('enabled'),
            FormField::addColumn(4),
            AssociationField::new('permanentRegistrations')
                ->setCrudController(UserCrudController::class)
                ->setSortProperty('nickname')
            ,
        ];
    }
}
