<?php

namespace App\Tests;

use App\Entity\Tva;
use App\Service\Cart;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartTest extends KernelTestCase
{
    private EntityManagerInterface $manager;
    private Cart $cartService;
    private SessionInterface $session;
    
    protected function setUp(): void
    {
        $container = static::getContainer();
        $this->cartService = $container->get('App\Service\Cart');

        $this->manager = static::getContainer()->get('doctrine')->getManager();

        $this->session = static::getContainer()->get('session');

        $productRepository = $this->manager->getRepository(Product::class);
        $tvaRepository = $this->manager->getRepository(Tva::class);

        //Empty TVA and product table
        foreach ($tvaRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        foreach ($productRepository->findAll() as $object) {
            $this->manager->remove($object);
        }
   
    }

    public function testAdd(): void
    {
        // Create test product
        $tva = new Tva();
        $tva->setName('Tva 20%')->setValue(0.2);

        $this->manager->persist($tva);

        $product = new Product();
        $product->setName('Produit test')
        ->setPrice(149.99)
        ->setTva($tva);

        $this->manager->persist($product);
        $this->manager->flush();

        // Ajout 1 produit
        $this->cartService->add($product, 1);

        $sessionCart = $this->session->get('cart');
        
        self::assertNotEmpty($sessionCart);
        self::assertCount(1,$sessionCart['products']);
        self::assertEquals(1, $sessionCart['qty']);
    }
}
