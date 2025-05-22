<?php
// src/Controller/Admin/BarberCrudController.php

namespace App\Controller\Admin;

use App\Entity\Barber;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class BarberCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Barber::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('firstName', 'Prénom'),
            ImageField::new('photo', 'Photo')
                ->setUploadDir('public/uploads/barbers')      // dossier physique pour l'upload
                ->setBasePath('uploads/barbers')             // chemin public pour afficher l'image
                ->setRequired(false),                        // photo optionnelle
        ];
    }
}
