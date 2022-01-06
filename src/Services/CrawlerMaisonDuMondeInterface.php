<?php

namespace App\Services;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\CssSelector\CssSelectorConverter;

class CrawlerMaisonDuMondeInterface
{
    private const SUCCES_CODE = 200;
    private HttpClientInterface $client;
    private array $links = [
        'canapé' => 'https://www.maisonsdumonde.com/FR/fr/c/canapes-n4dc562d7bb509362a3a2102db4cbf90b6454f1f?page=',
    ];

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->client = $httpClient;
    }

    public function getProductLinks(string $html): array
    {
        // $productsLink = [];
        $crawler = new Crawler($html, 'https://www.maisonsdumonde.com/');

        $links = $crawler->filter('.link')->links();
        var_dump(count($links));
        foreach ($links as $link) {
            var_dump($link);
            // var_dump($link);
        }
        die();
    }

    public function getHtml(string $url): string
    {
        $response = $this->client->request('GET', $url);

        if ($response->getStatusCode() != self::SUCCES_CODE) {
            throw new NotFoundHttpException((string)$response->getStatusCode());
        }
        return $response->getContent();
    }

    public function getData(): void
    {
        $this->getProductLinks($this->getHtml($this->links['canapé']));
    }
}
