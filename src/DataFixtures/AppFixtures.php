<?php

namespace App\DataFixtures;

use App\Entity\Tva;
use App\Entity\User;
use App\Entity\Review;
use DateTimeImmutable;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Customer;
use App\Entity\ProductImage;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function __construct(Private UserPasswordHasherInterface $passwordHasher)
    {
        
    }

    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        // Création d'un utilisateur ADMIN
        $user = new User();
        $user->setRoles(['ROLE_ADMIN'])
        ->setEmail('admin@admin.fr')
        ->setPassword($this->passwordHasher->hashPassword($user,'123456'))
        ->setVerified(true);

        $manager->persist($user);


        // Création des catégories
        $categories = [];

        for ($j = 0; $j < 150; $j++) {
            $category = new Category();

            $category->setName($faker->realTextBetween(10, 20));

            $categories[] = $category;

            $manager->persist($category);
        }

        // TVA 2 valeurs
        $tva = new Tva();
        $tva->setName('Tva 20%')->setValue(0.2);

        $manager->persist($tva);

        $tva2 = new Tva();
        $tva2->setName('Tva 10%')->setValue(0.1);

        $manager->persist($tva2);

        //Création des produits
        for ($i = 0; $i < 150; $i++) {
            $product = new Product();

            $product->setName($faker->realTextBetween(10, 30))
                ->setDescription($faker->realTextBetween(150, 600))
                ->setPrice($faker->randomFloat(2, 10, 300))
                ->setStock($faker->randomDigit())
                ->setTva($tva);

            $nbCategory = random_int(1, 10);
            for ($k = 0; $k < $nbCategory; $k++) {
                shuffle($categories);
                $product->addCategory($categories[0]);
            }


            for ($l = 0; $l < 10; $l++) {
                $image = new ProductImage();
                $image->setName($faker->realTextBetween(10, 30))
                ->setFile("https://picsum.photos/id/". $faker->numberBetween(1, 1080)."/1024" )
                ->setProduct($product);

                $manager->persist($image);
            }

            for ($m = 0; $m < random_int(3, 20); $m++) {

                //Create Customer/User for Review
                $user = new User();
                // désactivation du hash... fixture trop longue a éxecuter sinon
                //$user->setEmail($faker->email())->setPassword($this->passwordHasher->hashPassword($user, $faker->password()))->setRoles(['ROLE_CUSTOMER']);
                $user->setEmail($faker->email())->setPassword($faker->password())->setRoles(['ROLE_CUSTOMER']);

                $manager->persist($user);

                $customer = new Customer();
                $customer->setFirstname($faker->firstName())
                    ->setLastname($faker->lastName())
                    ->setBithdateAt(new \DateTimeImmutable($faker->date()))
                    ->setPhone($faker->phoneNumber())
                    ->setUser($user);

                $manager->persist($customer);

                // création Review
                $review = new Review();
                $review->setContent($faker->realTextBetween(50, 300))
                ->setNote($faker->numberBetween(1, 5))
                ->setProduct($product)
                ->setCustomer($customer);

                $manager->persist($review);


            }

            $manager->persist($product);
        }

        $manager->flush();

    }
}
