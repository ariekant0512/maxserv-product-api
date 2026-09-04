<?php

declare(strict_types=1);

namespace MaxServ\App\Service;

use GuzzleHttp\ClientInterface;
use MaxServ\App\Repository\ProductRepository;

readonly class ProductImporter
{
    // dummyjson.com geeft standaard maar 30 producten terug. Met "limit"
    // vragen we er in één keer meer op, de opdracht vraagt om minimaal 100.
    private const IMPORT_LIMIT = 100;

    public function __construct(
        private ClientInterface $httpClient,
        private ProductRepository $productRepository,
    ) {
    }

    /**
     * Haalt de producten op bij de externe API en slaat ze één voor één op
     * in onze eigen database. Geeft het aantal geïmporteerde producten terug.
     */
    public function import(): int
    {
        $response = $this->httpClient->request('GET', 'https://dummyjson.com/products', [
            'query' => [
                'limit' => self::IMPORT_LIMIT,
            ],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
        $products = $data['products'] ?? [];

        foreach ($products as $productData) {
            $this->productRepository->upsertFromApiData($productData);
        }

        return count($products);
    }
}
