<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $products = [
            [
                'name' => 'Shampoing Premium Homme',
                'description' => 'Un shampoing de qualité supérieure spécialement formulé pour les cheveux masculins. Nettoie en profondeur tout en préservant les huiles naturelles du cuir chevelu. Enrichi en extraits botaniques et vitamines pour fortifier les cheveux.',
                'price' => 24.99,
                'category' => 'shampoing',
                'stock' => 50,
                'image' => 'shampoing-1.jpg',
                'images' => ['shampoing-1.jpg', 'shampoing-1-alt.jpg']
            ],
            [
                'name' => 'Cire Coiffante Extra Forte',
                'description' => 'Cire professionnelle à tenue extra forte pour un style impeccable toute la journée. Texture non grasse, facilement remodelable. Parfaite pour les coiffures structurées et les finitions texturées.',
                'price' => 18.99,
                'category' => 'cire',
                'stock' => 35,
                'image' => 'cire-1.jpg',
                'images' => ['cire-1.jpg', 'cire-1-alt.jpg']
            ],
            [
                'name' => 'Huile de Barbe Argan Bio',
                'description' => 'Huile nourrissante 100% naturelle à base d\'argan bio. Hydrate, adoucit et fait briller la barbe. Pénètre rapidement sans effet gras. Senteur boisée et masculine subtile.',
                'price' => 29.99,
                'category' => 'huile',
                'stock' => 25,
                'image' => 'huile-1.jpg',
                'images' => ['huile-1.jpg', 'huile-1-alt.jpg']
            ],
            [
                'name' => 'Peigne en Bois de Santal',
                'description' => 'Peigne artisanal en bois de santal authentique. Dents larges parfaites pour démêler sans casser les cheveux. Antistatique naturel, idéal pour tous types de cheveux. Design élégant et durable.',
                'price' => 15.99,
                'category' => 'accessoire',
                'stock' => 20,
                'image' => 'peigne-1.jpg',
                'images' => ['peigne-1.jpg', 'peigne-1-alt.jpg']
            ],
            [
                'name' => 'Shampoing Sec Volumisant',
                'description' => 'Shampoing sec innovant qui rafraîchit et donne du volume instantanément. Absorbe l\'excès de sébum, prolonge la coiffure et apporte texture et corps aux cheveux. Spray pratique à utilisation rapide.',
                'price' => 16.99,
                'category' => 'shampoing',
                'stock' => 40,
                'image' => 'shampoing-2.jpg',
                'images' => ['shampoing-2.jpg', 'shampoing-2-alt.jpg']
            ],
            [
                'name' => 'Gel Coiffant Effet Wet',
                'description' => 'Gel professionnel pour un effet mouillé longue durée. Fixation forte avec brillance intense. Résiste à l\'humidité et aux intempéries. Parfait pour les looks sleek et sophistiqués.',
                'price' => 14.99,
                'category' => 'cire',
                'stock' => 30,
                'image' => 'gel-1.jpg',
                'images' => ['gel-1.jpg', 'gel-1-alt.jpg']
            ],
            [
                'name' => 'Sérum Anti-Chute Premium',
                'description' => 'Traitement intensif anti-chute aux actifs scientifiquement prouvés. Stimule la croissance, renforce les racines et densifie la chevelure. Application quotidienne pour des résultats visibles en 4 semaines.',
                'price' => 45.99,
                'category' => 'huile',
                'stock' => 15,
                'image' => 'serum-1.jpg',
                'images' => ['serum-1.jpg', 'serum-1-alt.jpg']
            ],
            [
                'name' => 'Brosse Pneumatique Pro',
                'description' => 'Brosse pneumatique professionnelle avec coussin d\'air. Poils en sanglier naturel pour un brossage respectueux. Démêle sans tirer, masse le cuir chevelu et répartit le sébum naturel.',
                'price' => 32.99,
                'category' => 'accessoire',
                'stock' => 12,
                'image' => 'brosse-1.jpg',
                'images' => ['brosse-1.jpg', 'brosse-1-alt.jpg']
            ],
            [
                'name' => 'Pommade Vintage Brillantine',
                'description' => 'Pommade classique à l\'ancienne pour un style rétro authentique. Brillance intense et tenue souple. Se réactive à l\'eau, parfaite pour recoiffer en cours de journée. Senteur barbershop traditionnelle.',
                'price' => 22.99,
                'category' => 'cire',
                'stock' => 28,
                'image' => 'pommade-1.jpg',
                'images' => ['pommade-1.jpg', 'pommade-1-alt.jpg']
            ],
            [
                'name' => 'Kit Entretien Barbe Complet',
                'description' => 'Kit complet pour l\'entretien de la barbe : huile, baume, peigne en bois et ciseaux de précision. Tout le nécessaire pour une barbe impeccable. Présenté dans une trousse de voyage élégante.',
                'price' => 69.99,
                'category' => 'accessoire',
                'stock' => 8,
                'image' => 'kit-barbe-1.jpg',
                'images' => ['kit-barbe-1.jpg', 'kit-barbe-1-alt.jpg']
            ],
            [
                'name' => 'Spray Texturisant Marin',
                'description' => 'Spray texturisant aux sels marins pour un effet plage naturel. Apporte texture, volume et un fini mat. Idéal pour les cheveux fins et les styles décontractés. Parfum frais océanique.',
                'price' => 19.99,
                'category' => 'cire',
                'stock' => 3,
                'image' => 'spray-1.jpg',
                'images' => ['spray-1.jpg', 'spray-1-alt.jpg']
            ],
            [
                'name' => 'Après-Shampoing Réparateur',
                'description' => 'Soin démêlant et réparateur intensif. Formule enrichie en kératine et protéines de soie. Répare les cheveux abîmés, facilite le coiffage et apporte brillance et souplesse exceptionnelles.',
                'price' => 21.99,
                'category' => 'shampoing',
                'stock' => 0,
                'image' => 'apres-shampoing-1.jpg',
                'images' => ['apres-shampoing-1.jpg', 'apres-shampoing-1-alt.jpg']
            ]
        ];

        foreach ($products as $productData) {
            $product = new Product();
            $product->setName($productData['name'])
                ->setDescription($productData['description'])
                ->setPrice((string) $productData['price'])
                ->setCategory($productData['category'])
                ->setStock($productData['stock'])
                ->setImage($productData['image'])
                ->setImages($productData['images'])
                ->setActive(true);

            $manager->persist($product);
        }

        $manager->flush();
    }
}