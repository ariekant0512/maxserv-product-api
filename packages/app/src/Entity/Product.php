<?php

declare(strict_types=1);

namespace MaxServ\App\Entity;

/**
 * Eén product, precies zoals hij in onze eigen database staat.
 *
 * Dit is bewust een "dom" object: het bevat alleen data en een paar
 * simpele berekeningen. Alle logica om producten op te halen of te
 * bewaren hoort in de ProductRepository, niet hier.
 */
readonly class Product
{
    public function __construct(
        public int $id,
        public int $externalId,
        public string $title,
        public ?string $description,
        public string $category,
        public ?string $brand,
        public float $price,
        public float $discountPercentage,
        public ?string $thumbnail,
    ) {
    }

    /**
     * Bouwt een Product op vanuit een database-rij (associatieve array).
     */
    public static function fromDatabaseRow(array $row): self
    {
        return new self(
            id: (int) $row['id'],
            externalId: (int) $row['external_id'],
            title: (string) $row['title'],
            description: $row['description'] !== null ? (string) $row['description'] : null,
            category: (string) $row['category'],
            brand: $row['brand'] !== null ? (string) $row['brand'] : null,
            price: (float) $row['price'],
            discountPercentage: (float) $row['discount_percentage'],
            thumbnail: $row['thumbnail'] !== null ? (string) $row['thumbnail'] : null,
        );
    }

    /**
     * De prijs na aftrek van de korting.
     */
    public function getDiscountedPrice(): float
    {
        $korting = $this->price * ($this->discountPercentage / 100);

        return round($this->price - $korting, 2);
    }
}
