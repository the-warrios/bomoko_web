<?php

namespace App\Controller\Admin;

use App\Entity\Fine;
use App\Entity\Report;
use App\Entity\ReportCategory;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class FineCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Fine::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
            ->hideOnForm(),
            IntegerField::new('amount')
            ->setRequired(true),
            DateField::new('date_closed'),

            // Champ d'association pour ReportCategory
            AssociationField::new('report', 'Catégorie')
                ->setCrudController(ReportCategoryCrudController::class)
                ->setRequired(true)
                ->setFormTypeOption('choice_label', function (Report $entity) {
                    //dd($entity->getReportCategory()->getLabel());
                    return $entity->getReportCategory()->getLabel(); // Accède à label ici
                })
                ->formatValue(function ($value, Fine $entity) {
                    $report = $entity->getReport();
                    return $report && $report->getReportCategory()
                        ? $report->getReportCategory()->getLabel()
                        : 'N/A'; // Si aucune catégorie
                }),
        ];
    }

}
