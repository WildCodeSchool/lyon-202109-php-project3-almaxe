<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Partner;
use App\Repository\CategoryRepository;
use App\Repository\PartnerRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Link;
use Symfony\Component\HttpClient\HttpClient;

class MaisonDuMondeCrawlerManager
{
    private const ARTICLE_PER_PAGE = 40;
    private const URI = "https://www.maisonsdumonde.com/FR/fr/c/";
    private HttpBrowser $browser;
    private EntityManagerInterface $manager;
    private PartnerRepository $partnerRepository;
    private Slugify $slugify;
    private CategoryRepository $categoryRepository;
    private ?Partner $partner;

    private array $links = [
        'canapé' => self::URI . 'canapes-n4dc562d7bb509362a3a2102db4cbf90b6454f1f?page=',
        'fauteuil' => self::URI . 'fauteuils-poufs-et-repose-pieds-ncc34bdf43910454d7efcf6df9b845f014c3540f?page=',
        'table' => self::URI . 'tables-a-diner-22483ed22fab4cdf82dae2057687c73a?page=',
        'bureau' => self::URI . 'bureaux-et-meubles-secretaires-3ecf71c8798d34e8c3d78d47e0313270?page=',
        'étagère' => self::URI . 'etageres-d1281f70e11032066ad0cb5e24e5a4f2?page='
    ];

    public function __construct(
        EntityManagerInterface $manager,
        PartnerRepository $partnerRepository,
        CategoryRepository $categoryRepository,
        Slugify $slugify
    ) {
        $this->browser = new HttpBrowser(HttpClient::create());
        $this->manager = $manager;
        $this->partnerRepository = $partnerRepository;
        $this->slugify = $slugify;
        $this->categoryRepository = $categoryRepository;
        $this->partner = $this->partnerRepository->findOneBy(['name' => 'Maison du monde']);
        if (is_null($this->partner)) {
            throw new Exception('No partner Found');
        }
    }

    public function main(): void
    {
        foreach ($this->links as $categoryName => $link) {
            // Get number of product for the current category
            $numberOfProduct = $this->getProductCount($link);
            //Determine number of page we need to browse
            $numberOfPage = ceil($numberOfProduct / self::ARTICLE_PER_PAGE);

            $category = $this->categoryRepository->findOneBy(['name' => $categoryName]);
            if (is_null($category)) {
                throw new Exception('No category Found');
            }

            for ($i = 1; $i <= $numberOfPage; $i++) {
                try {
                    $this->getProducts($category, $link . $i);
                } catch (Exception $exception) {
                    continue;
                }
            }
        }
    }

    public function getProductCount(string $url): int
    {
        $crawler = $this->browser->request('GET', $url);
        $productCount = $crawler->filter('.count')->html();
        $productCount = explode(' ', $productCount)[0];
        return intval($productCount);
    }

    public function getProducts(Category $category, string $url): void
    {
        $crawler = $this->browser->request('GET', $url);

        $links = $crawler->filter('.link')->links();

        foreach ($links as $link) {
            // Scrap data for each link
            $data = $this->getProductData($link);
            if (empty($data)) {
                continue;
            }

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

        // get Product's page
        try {
            $productCrawler = $this->browser->click($link);
        } catch (Exception $exception) {
            return [];
        }

        try {
            // get product's url
            $product['uri'] = $productCrawler->getUri();

            // get image
            $images = $productCrawler->filter('.square-image')->images();
            $product['image'] = $this->getImageUri($images);

            //get product's name
            $product['name'] = $productCrawler->filter('.product-title')->html();

            //get product's price
            // TODO : get rid of spaces on price
            $price = $productCrawler->filter('.base-price')->html();
            $product['price'] = floatval(str_replace([' ', ','], ['', '.'], substr($price, 0, -2)));

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
        } catch (Exception $exception) {
            return [];
        }
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
