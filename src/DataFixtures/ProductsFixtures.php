<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Product;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductsFixtures extends Fixture
{
    protected $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new \Liior\Faker\Prices($faker));
        $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));
        $faker->addProvider(new \Bluemmb\Faker\PicsumPhotosProvider($faker));
        
        for($c = 0; $c < 3; $c++){
            $category = new Category;
            $category->setName($faker->department)
                ->setSlug(strtolower($this->slugger->slug($category->getName())));
            
            $manager->persist($category);

            for($p = 0; $p < mt_rand(12, 24); $p++){
                $product = new Product();
                $product
                    ->setName($faker->productName())
                    ->setPrice($faker->price(4000, 20000))
                    ->setMainPicture($faker->imageUrl(400, 400, true))
                    ->setShortDescription($faker->paragraph(mt_rand(1,2)))
                    ->setSlug(strtolower($this->slugger->slug($product->getName())))
                    ->setCategory($category)
                    ;
                $manager->persist($product);
            }
        }

        $manager->flush();
    }
}
