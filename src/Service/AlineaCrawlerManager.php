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
    private const ARTICLE_PER_PAGE = 35;
    private const URI = "https://www.alinea.com/fr-fr/";
    private HttpBrowser $browser;
    private EntityManagerInterface $entityManager;
    private PartnerRepository $partnerRepository;
    private CategoryRepository $categoryRepository;
    private ?Partner $partner;
    private Slugify $slugify;

    private array $links = [
        'canapé' => self::URI . 'canapes/?page=',
        'fauteuil' => self::URI . 'fauteuils/?page=',
        'table' => self::URI . 'tables-fixes/?page=',
        'bureau' => self::URI . 'bureaux/?page=',
        'étagère' => self::URI . 'bibliotheques-etageres/?page=',
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
        $this->partner = $this->partnerRepository->findOneBy(['name' => 'Ikea']);
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
        $productCount = $crawler->filter('.nb-results')->html();
        $productCount = explode(' ', $productCount)[0];
        return intval($productCount);
    }

    public function getProducts(Category $category, string $url): void
    {
        $crawler = $this->browse->request('GET', $url);
        $links = $crawler->filter('.name-link')->links();

        foreach ($links as $link) {
            $data = $this->getProductData($link);
            if (empty($data)) {
                continue;
            }

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

            $this->manager->persist($product);
        }
        $this->manager->flush();
    }

    public function getProductData(Link $link): array
    {
        $product = [];

        try {
            $productCrawler = $this->browser->click($link);
        } catch (Exception $exception) {
            var_dump($exception);
            die();
            return [];
        }

        $product['uri'] = $productCrawler->getUri();
        $product['image'] = "favicon.png";
        $product['price'] = 3;

        //Name
        $productName = $productCrawler->filter('.product-name-title')->html();
        $productName = explode(' ', $productName);
        $product['name'] = $productName[3];

        //Dimensions
        $productDimension = $productCrawler->filter('.bloc-txt dimension')->html();

        //Clean
        $productDimension = explode('<li>', $productDimension);
        $height = explode(' ', $productDimension[1]);
        $width = explode(' ', $productDimension[2]);
        $depth = explode(' ', $productDimension[3]);

        //Get data
        $product['height'] = $height[3];
        $product['width'] = $width[3];
        $product['depth'] = $depth[3];

        return $product;
    }
}
