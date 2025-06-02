<?php

namespace App\Tests\Controller\Back;

use App\Entity\Back\Tickets;
use App\Repository\Back\TicketsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class TicketsControllerTest extends WebTestCase{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $ticketRepository;
    private string $path = '/admin/back/tickets/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->ticketRepository = $this->manager->getRepository(Tickets::class);

        foreach ($this->ticketRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Ticket index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'ticket[token]' => 'Testing',
            'ticket[titre]' => 'Testing',
            'ticket[description]' => 'Testing',
            'ticket[createdAt]' => 'Testing',
            'ticket[updatedAt]' => 'Testing',
            'ticket[createdBy]' => 'Testing',
            'ticket[statut]' => 'Testing',
            'ticket[severite]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->ticketRepository->count([]));
    }

    public function testShow(): void
    {
        $fixture = new Tickets();
        $fixture->setToken('My Title');
        $fixture->setTitre('My Title');
        $fixture->setDescription('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setUpdatedAt('My Title');
        $fixture->setCreatedBy('My Title');
        $fixture->setStatut('My Title');
        $fixture->setSeverite('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Ticket');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $fixture = new Tickets();
        $fixture->setToken('Value');
        $fixture->setTitre('Value');
        $fixture->setDescription('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setUpdatedAt('Value');
        $fixture->setCreatedBy('Value');
        $fixture->setStatut('Value');
        $fixture->setSeverite('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'ticket[token]' => 'Something New',
            'ticket[titre]' => 'Something New',
            'ticket[description]' => 'Something New',
            'ticket[createdAt]' => 'Something New',
            'ticket[updatedAt]' => 'Something New',
            'ticket[createdBy]' => 'Something New',
            'ticket[statut]' => 'Something New',
            'ticket[severite]' => 'Something New',
        ]);

        self::assertResponseRedirects('/back/tickets/');

        $fixture = $this->ticketRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getToken());
        self::assertSame('Something New', $fixture[0]->getTitre());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getCreatedAt());
        self::assertSame('Something New', $fixture[0]->getUpdatedAt());
        self::assertSame('Something New', $fixture[0]->getCreatedBy());
        self::assertSame('Something New', $fixture[0]->getStatut());
        self::assertSame('Something New', $fixture[0]->getSeverite());
    }

    public function testRemove(): void
    {
        $fixture = new Tickets();
        $fixture->setToken('Value');
        $fixture->setTitre('Value');
        $fixture->setDescription('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setUpdatedAt('Value');
        $fixture->setCreatedBy('Value');
        $fixture->setStatut('Value');
        $fixture->setSeverite('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/back/tickets/');
        self::assertSame(0, $this->ticketRepository->count([]));
    }
}
