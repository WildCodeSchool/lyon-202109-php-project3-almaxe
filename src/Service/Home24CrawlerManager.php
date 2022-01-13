<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Partner;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\PartnerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Link;
use Symfony\Component\HttpClient\HttpClient;

class Home24CrawlerManager
{
    private const ARTICLE_PER_PAGE = 30;
    private const URI = "https://www.home24.fr";
    private HttpBrowser $browser;
    private EntityManagerInterface $entityManager;
    private PartnerRepository $partnerRepository;
    private CategoryRepository $categoryRepository;
    private ?Partner $partner;
    private Slugify $slugify;

    private array $links = [
        'table' => self::URI . '/tables-salle-manger/?page=',
        'bureau' => self::URI . '/bureaux/?page=',
        'étagère' => self::URI . '/etagere/?page=',
        'fauteuil' => self::URI . '/tous-fauteuils/?page=',
        'canapé' => self::URI . '/canape/?page='

    ];

    public function __construct(
        EntityManagerInterface $entityManager,
        PartnerRepository $partnerRepository,
        Slugify $slugify,
        CategoryRepository $categoryRepository
    ) {
        $this->browser = new HttpBrowser(HttpClient::create());
        $this->entityManager = $entityManager;
        $this->partnerRepository = $partnerRepository;
        $this->slugify = $slugify;
        $this->categoryRepository = $categoryRepository;
        $this->partner = $this->partnerRepository->findOneBy(['name' => 'Home24']);
        if (is_null($this->partner)) {
            throw new Exception("No partner found");
        }
    }

    public function main(): void
    {
        foreach ($this->links as $categoryName => $link) {
            $numberOfProduct = $this->getProductCount($link);
            $numberOfPage = ceil($numberOfProduct / self::ARTICLE_PER_PAGE);

            echo ("$numberOfProduct products found for category '$categoryName'\n");
            echo ("$numberOfPage pages to scrap \n");
            echo ("\n===============\n\n");

            $category = $this->categoryRepository->findOneBy(['name' => $categoryName]);
            if (is_null($category)) {
                throw new Exception("No category found");
            }

            for ($i = 1; $i < +$numberOfPage; $i++) {
                try {
                    echo ("Start scrapping on page $i/$numberOfPage \n");
                    $this->getProducts($category, $link . $i);
                } catch (Exception $exception) {
                    var_dump($exception);
                }
            }
        }
    }

    public function getProductCount(string $url): int
    {
        $crawler = $this->browser->request('GET', $url);
        $productCount = $crawler->filter('.css-mos0wc')->html();
        $productCount = explode(' ', $productCount)[0];
        $productCount = substr($productCount, 1);
        return intval($productCount);
    }

    public function getProducts(Category $category, string $url): void
    {
        $crawler = $this->browser->request('GET', $url);
        $links = $crawler->filter('.css-xl0ay8')->links();
        $images = $crawler->filter('.css-13fgnt6')->images();
        $dataImage = [];
        for ($i = 0; $i < count($images); $i++) {
            $dataImage[$images[$i]->getUri()] = $links[$i];
        }
        foreach ($dataImage as $image => $link) {
            $data = $this->getProductData($link);
            if (empty($data)) {
                continue;
            }
            $images = $crawler->filter('.css-13fgnt6')->images();
            $product = new Product();
            $data['image'] = $image;
            $product->setName($data['name']);
            $product->setPrice($data['price']);
            $product->setPriceCurrency('€');
            $product->setHeight($data['height']);
            $product->setWidth($data['width']);
            $product->setDepth($data['depth']);
            $product->setPicture($data['image']);
            $product->setUrl($data['uri']);
            $product->setCategory($category);
            $product->setPartnerProductId(uniqid());
            $partner = $this->partner;
            if (is_null($partner)) {
                break;
            }
            $product->setPartner($partner);
            $product->setSlug($this->slugify->generate($product->getName()));

            $this->entityManager->persist($product);
            echo "Product Save \n";
        }
        $this->entityManager->flush();
    }

    public function getProductData(Link $link): array
    {
        $product = [];

        try {
            $productCrawler = $this->browser->click($link);
            //URI
            $product['uri'] = $productCrawler->getUri();

            //Price
            $productPrice = $productCrawler->filter('.css-2hheeo')->html();
            $productPrice = explode(' ', $productPrice);
            $product['price'] = floatval($productPrice[0]);

            //Name
            $productName = $productCrawler->filter('.css-1jyj9ij')->html();
            $product['name'] = $productName;


            //Dimensions
            $productDimension = $productCrawler->filter('.css-59yddc')->html();
            // $productDimension = explode('</li>', $productDimension[2]);
            $height = explode("Hauteur : ", $productDimension)[1];
            $height = explode(" ", $height)[0];
            $width = explode("Largeur : ", $productDimension)[1];
            $width = explode(" ", $width)[0];
            $depth = explode("Profondeur : ", $productDimension)[1];
            $depth = explode(" ", $depth)[0];

            //Get data
            $product['height'] = floatval($height);
            $product['width'] = floatval($width);
            $product['depth'] = floatval($depth);

            return $product;
        } catch (Exception $exception) {
            return [];
        }
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
