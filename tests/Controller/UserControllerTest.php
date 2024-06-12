<?php

namespace App\Test\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private UserPasswordHasherInterface $userPasswordHasher;
    private EntityRepository $repository;
    private string $path = '/admin/user/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->userPasswordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);
        $this->repository = $this->manager->getRepository(User::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('User index');

        // Use the $crawler to perform additional assertions e.g.
        //self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        //$this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('save', [
            'user[email]' => 'test@test.fr',
            'user[roles]' => ['ROLE_API'],
            'user[plainPassword][first]' => 'Testing',
            'user[plainPassword][second]' => 'Testing'
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }


    /**
     * Test error on password for create New
     */
    public function testNewErrorPassword(): void
    {
        //$this->markTestIncomplete();
        echo $this->path;
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $crawler = $this->client->submitForm('save', [
            'user[email]' => 'test@test.fr',
            'user[roles]' => ['ROLE_API'],
            'user[plainPassword][first]' => 'Testing',
            'user[plainPassword][second]' => '123456'
        ]);

        // error 422 : donnée non traité
        self::assertResponseStatusCodeSame(422);

        // div.invalid-feedback
        self::assertSame('The password fields must match.', $crawler->filter('div.invalid-feedback')->first()->innerText());
        self::assertNotEmpty($crawler->filter('div.invalid-feedback')->first()->innerText());

    }

    public function testShow(): void
    {
  
        $fixture = new User();
        $fixture->setEmail('My Title');
        //$fixture->setRoles(['ROLE_ADMIN']);
        $fixture->setPassword('password');
        //$fixture->setCustomer(null);

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('User');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        //$this->markTestIncomplete();
        $fixture = new User();
        $fixture->setEmail('email@email.fr');
        $fixture->setRoles(['ROLE_API']);
        $fixture->setPassword('password');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('save', [
            'user[email]' => 'email@email2.fr',
            'user[roles]' => ['ROLE_API'],
            'user[plainPassword][first]' => '123456',
            'user[plainPassword][second]' => '123456'
        ]);

        self::assertResponseRedirects($this->path);

        $fixture = $this->repository->findAll();

    
        self::assertSame('email@email2.fr', $fixture[0]->getEmail());
        self::assertSame(['ROLE_API', 'ROLE_USER'], $fixture[0]->getRoles());
        self::assertTrue($this->userPasswordHasher->isPasswordValid($fixture[0], '123456'));
        //self::assertSame('123456', $fixture[0]->getPassword());
        self::assertEmpty($fixture[0]->getModifiedAt());
    }

    public function testRemove(): void
    {
        //$this->markTestIncomplete();
        $fixture = new User();
        $fixture->setEmail('Value');
        $fixture->setRoles(['ROLE_USER']);
        $fixture->setPassword('test');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/admin/user/');
        self::assertSame(0, $this->repository->count([]));
    }
}
