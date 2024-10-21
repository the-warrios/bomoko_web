<?php

namespace App\Controller\Admin;

use App\Entity\Report;
use App\Entity\ReportCategory;
use App\Entity\Vehicule;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ReportCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Report::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->setLabel("Identifiant")
                ->hideOnDetail()
                ->hideOnForm(),
            AssociationField::new('reportCategory', 'Categories')
                ->setCrudController(ReportCategoryCrudController::class)
                ->setRequired(true)
                ->setFormTypeOption('choice_label', 'label')
                ->formatValue(function ($value, Report $entity) {
                    $reportCategory = $entity->getReportCategory();
                    return $reportCategory->getLabel(); // Utilisez la méthode qui retourne le nom de la catégorie
                }),
            TextEditorField::new('description', 'Commentaire'),
            ImageField::new('image', 'Photo')
                ->setUploadDir('public/uploads/images')
                ->setBasePath('uploads/images')
                ->setRequired(false),
            Field::new('videoFile', 'Upload Video')
                ->setFormType(VichFileType::class)
                ->setFormTypeOptions(['required' => false])
                ->onlyOnForms(),
            UrlField::new('video', 'Vidéo')
                ->formatValue(function ($value, $entity) {
                    return sprintf('<a href="/uploads/videos/%s" target="_blank">Voir la Video</a>', $value);
                })
                ->hideOnForm(),
            AssociationField::new('reportVehicule', 'Plaque')
                ->setCrudController(VehiculeCrudController::class)
                ->setRequired(true)
                ->setFormTypeOption('choice_label', 'plate')
                ->formatValue(function ($value, Report $entity) {
                    $vehicule = $entity->getReportVehicule();
                    return $vehicule->getPlate(); // Utilisez la méthode qui retourne le nom de la catégorie
                }),
            TextField::new('status', 'Status')
            ->setRequired(false),
        ];
    }

}
