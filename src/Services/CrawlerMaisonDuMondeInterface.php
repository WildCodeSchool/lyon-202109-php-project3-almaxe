<?php

namespace App\Services;

use App\Entity\Product;
use App\Repository\PartnerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\DomCrawler\Link;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CrawlerMaisonDuMondeInterface
{
    private HttpBrowser $browser;
    private EntityManagerInterface $manager;
    private PartnerRepository $partnerRepository;
    private int $number = 5;
    private array $links = [
        'canapé' => 'https://www.maisonsdumonde.com/FR/fr/c/canapes-n4dc562d7bb509362a3a2102db4cbf90b6454f1f?page='
    ];

    public function __construct(EntityManagerInterface $manager, PartnerRepository $partnerRepository)
    {
        $this->browser = new HttpBrowser(HttpClient::create());
        $this->manager = $manager;
        $this->partnerRepository = $partnerRepository;
    }

    public function main(): void
    {
        for ($i = 1; $i < $this->number; $i++) {
            $this->getProducts($this->links['canapé'] . $i);
        }
    }

    public function getProducts(string $url): void
    {
        $crawler = $this->browser->request('GET', $url);

        $links = $crawler->filter('.link')->links();

        foreach ($links as $link) {
            // Scrap data for each link
            $data = $this->getProductData($link);

            // create new product
            $product = new Product();
            $product->setName($data['name']);
            $product->setPrice($data['price']);
            $product->setPriceCurrency('€');
            $product->setHeight($data['height']);
            $product->setWidth($data['width']);
            $product->setDepth($data['depth']);
            $product->setPicture($data['image']);
            $product->setUrl($data['uri']);
            $product->setPartnerProductId('1');
            $partner = $this->partnerRepository->findOneBy(['name' => 'Maison du monde']);
            if (is_null($partner)) {
                break;
            }
            $product->setPartner($partner);

            $this->manager->persist($product);
        }
        $this->manager->flush();
    }

    public function getProductData(Link $link): array
    {

        $product = [];

        // get Product's page
        $productCrawler = $this->browser->click($link);

        // get product's url
        $product['uri'] = $productCrawler->getUri();

        // get image
        $images = $productCrawler->filter('.square-image')->images();
        $product['image'] = $this->getImageUri($images);


        //get product's name
        $product['name'] = $productCrawler->filter('.product-title')->innerText();

        //get product's price
        $price = $productCrawler->filter('.base-price')->innerText();
        $product['price'] = (float)substr($price, 0, -2);


        //get dimensions node
        $productDimension = $productCrawler->filter('[data-v-887dabba]')->html();

        // clean data
        $productDimension =  explode('<span data-v-887dabba>H', $productDimension)[1];
        $productDimension = explode('<', $productDimension)[0];
        $productDimension = explode(' ', $productDimension);

        // store data
        $product['height'] = $productDimension[0];
        $product['width'] = substr($productDimension[2], 1);
        $product['depth'] = substr($productDimension[4], 2);

        return $product;
    }



    public function getImageUri(array $images): string
    {
        $uri = '';
        foreach ($images as $img) {
            if (strlen($uri) < strlen($img->getUri())) {
                $uri = $img->getUri();
            }
        }

        return $uri;
    }
}
