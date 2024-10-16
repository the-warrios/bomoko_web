<?php

namespace App\Controller\Admin;

use App\Entity\Report;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
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
            IdField::new('id')->hideOnDetail()->hideOnForm(),
            TextEditorField::new('description', 'Commentaire'),
            ImageField::new('image', 'Photo')
                ->setUploadDir('public/uploads/images')
                ->setBasePath('uploads/images')
                ->setRequired(false),
            ImageField::new('video', 'Video')
                ->setFormType(VichFileType::class)
                ->setUploadDir("public/uploads/videos")
                ->setLabel('Upload Vidéo')
                ->setFormTypeOptions(['required' => false])
                ->onlyOnForms(),

        ];
    }

}
