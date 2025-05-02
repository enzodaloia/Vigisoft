<?php

namespace App\Tests\Controller\Back;

use App\Entity\Back\Contribution;
use App\Repository\Back\ContributionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ContributionControllerTest extends WebTestCase{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $contributionRepository;
    private string $path = '/back/contribution/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->contributionRepository = $this->manager->getRepository(Contribution::class);

        foreach ($this->contributionRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Contribution index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'contribution[token]' => 'Testing',
            'contribution[titre]' => 'Testing',
            'contribution[file]' => 'Testing',
            'contribution[createdAt]' => 'Testing',
            'contribution[corpshtml]' => 'Testing',
            'contribution[createdBy]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->contributionRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Contribution();
        $fixture->setToken('My Title');
        $fixture->setTitre('My Title');
        $fixture->setFile('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setCorpshtml('My Title');
        $fixture->setCreatedBy('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Contribution');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Contribution();
        $fixture->setToken('Value');
        $fixture->setTitre('Value');
        $fixture->setFile('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setCorpshtml('Value');
        $fixture->setCreatedBy('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'contribution[token]' => 'Something New',
            'contribution[titre]' => 'Something New',
            'contribution[file]' => 'Something New',
            'contribution[createdAt]' => 'Something New',
            'contribution[corpshtml]' => 'Something New',
            'contribution[createdBy]' => 'Something New',
        ]);

        self::assertResponseRedirects('/back/contribution/');

        $fixture = $this->contributionRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getToken());
        self::assertSame('Something New', $fixture[0]->getTitre());
        self::assertSame('Something New', $fixture[0]->getFile());
        self::assertSame('Something New', $fixture[0]->getCreatedAt());
        self::assertSame('Something New', $fixture[0]->getCorpshtml());
        self::assertSame('Something New', $fixture[0]->getCreatedBy());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Contribution();
        $fixture->setToken('Value');
        $fixture->setTitre('Value');
        $fixture->setFile('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setCorpshtml('Value');
        $fixture->setCreatedBy('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/back/contribution/');
        self::assertSame(0, $this->contributionRepository->count([]));
    }
}
