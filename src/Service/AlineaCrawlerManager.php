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

class AlineaCrawlerManager
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
            $category = $this->categoryRepository->findOneBy(['name' => $categoryName]);
            if (is_null($category)) {
                throw new Exception("No category found");
            }

            for ($i = 1; $i < +$numberOfPage; $i++) {
                try {
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

        foreach ($links as $link) {
            $data = $this->getProductData($link);
            if (empty($data)) {
                continue;
            }
            $productCrawler = $this->browser->click($link);
            $images = $productCrawler->filter('.css-13fgnt6')->images();
            $product['image'] = $this->getImageUri($images);
            $product = new Product();
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

            //Image
            $product['image'] = "favicon.png";

            //Price
            $productPrice = $productCrawler->filter('.css-2hheeo')->html();
            $productPrice = explode(' ', $productPrice);
            $product['price'] = intval($productPrice[0]);

            //Name
            $productName = $productCrawler->filter('.css-1jyj9ij')->html();
            $product['name'] = $productName;

            //Dimensions
            $productDimension = $productCrawler->filter('.css-59yddc')->html();
            $productDimension = explode("</ul>", $productDimension);
            $productDimension = explode('</li>', $productDimension[2]);

            //Clean
            $height = explode(" ", $productDimension[3]);
            $width = explode(" ", $productDimension[2]);
            $depth = explode(' ', $productDimension[4]);

            //Get data
            $product['height'] = intval($height[4]);
            $product['width'] = intval($width[4]);
            $product['depth'] = intval($depth[4]);

            return $product;
        } catch (Exception $exception) {
            var_dump($exception);
            return [];
        }
    }
}
