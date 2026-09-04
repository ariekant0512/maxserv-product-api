<?php

declare(strict_types=1);

namespace MaxServ\App\Repository;

use MaxServ\App\Entity\Product;
use MaxServ\Core\Database\Connection;

readonly class ProductRepository
{
    public function __construct(
        private Connection $connection,
    ) {
    }

    /**
     * Haalt alle producten op uit onze eigen database.
     *
     * @return Product[]
     */
    public function findAll(): array
    {
        $statement = $this->connection->getConnection()->query(
            'SELECT * FROM products ORDER BY title ASC'
        );

        $products = [];
        foreach ($statement->fetchAll() as $row) {
            $products[] = Product::fromDatabaseRow($row);
        }

        return $products;
    }

    /**
     * Haalt één product op via zijn id, of null als het niet bestaat.
     */
    public function findById(int $id): ?Product
    {
        $statement = $this->connection->getConnection()->prepare(
            'SELECT * FROM products WHERE id = :id'
        );
        $statement->execute(['id' => $id]);

        $row = $statement->fetch();

        return $row !== false ? Product::fromDatabaseRow($row) : null;
    }

    /**
     * Slaat een product op vanuit de ruwe data van de externe API.
     *
     * Bestaat het product (op basis van external_id) al? Dan werken we hem
     * bij. Bestaat hij nog niet? Dan voegen we hem toe. Zo kun je de import
     * gerust meerdere keren draaien zonder dubbele producten te krijgen.
     */
    public function upsertFromApiData(array $data): void
    {
        $statement = $this->connection->getConnection()->prepare(
            'INSERT INTO products
                (external_id, title, description, category, brand, price, discount_percentage, thumbnail)
            VALUES
                (:external_id, :title, :description, :category, :brand, :price, :discount_percentage, :thumbnail)
            ON DUPLICATE KEY UPDATE
                title = VALUES(title),
                description = VALUES(description),
                category = VALUES(category),
                brand = VALUES(brand),
                price = VALUES(price),
                discount_percentage = VALUES(discount_percentage),
                thumbnail = VALUES(thumbnail)'
        );

        $statement->execute([
            'external_id' => $data['id'],
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'category' => $data['category'],
            'brand' => $data['brand'] ?? null,
            'price' => $data['price'],
            'discount_percentage' => $data['discountPercentage'] ?? 0,
            'thumbnail' => $data['thumbnail'] ?? null,
        ]);
    }
}
