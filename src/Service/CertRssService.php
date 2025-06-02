<?php
namespace App\Service;

use FeedIo\FeedIo;
use FeedIo\Adapter\Http\Client as FeedIoClient;
use GuzzleHttp\Client as GuzzleClient;
use Http\Adapter\Guzzle7\Client as GuzzleAdapter;
use Nyholm\Psr7\Factory\Psr17Factory;

class CertRssService
{
    private FeedIo $feedIo;

    public function __construct()
    {
        // Usine pour les objets PSR-7
        $psr17Factory = new Psr17Factory();

        // Guzzle HTTP client
        $guzzleClient = new GuzzleClient();

        // Adaptateur HTTPlug (PSR-18)
        $guzzleAdapter = new GuzzleAdapter($guzzleClient);

        // Adaptateur compatible FeedIo
        $client = new FeedIoClient($guzzleAdapter, $psr17Factory, $psr17Factory);

        // Création de FeedIo
        $this->feedIo = new FeedIo($client);
    }

    public function getAlerts(int $limit = 5): array
    {
        $rssUrl = 'https://www.cert.ssi.gouv.fr/feed/';
        $result = $this->feedIo->read($rssUrl);

        $alerts = [];

        foreach ($result->getFeed() as $item) {
            $alerts[] = [
                'title' => $item->getTitle(),
                'link' => $item->getLink(),
                'publishedAt' => $item->getLastModified(),
                'summary' => $item->getContent(),
            ];
        }

        return $alerts;
    }
}
