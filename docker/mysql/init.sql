-- Deze tabel slaat de producten op die we ophalen bij de externe API (dummyjson.com).
-- MySQL voert dit bestand automatisch uit de eerste keer dat de database wordt aangemaakt.

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,

    -- Het originele id van het product bij dummyjson.com. Uniek, zodat we
    -- bij een nieuwe import een bestaand product kunnen bijwerken in plaats
    -- van hem dubbel op te slaan.
    external_id INT NOT NULL,

    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    category VARCHAR(100) NOT NULL,
    brand VARCHAR(100) NULL,

    -- DECIMAL in plaats van FLOAT, want bij geld wil je geen afrondingsfoutjes.
    price DECIMAL(10, 2) NOT NULL,
    discount_percentage DECIMAL(5, 2) NOT NULL DEFAULT 0,

    thumbnail VARCHAR(500) NULL,

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY uniq_external_id (external_id)
);
