<?php

declare(strict_types=1);

namespace App;

use PDO;

class StockService {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function reduceStock(string $sku): void {
        $stmt = $this->pdo->prepare("UPDATE inventory SET quantity = quantity - 1 WHERE sku = :sku AND quantity > 0");
        $stmt->execute(['sku' => $sku]);

        if ($stmt->rowCount() === 0) {
            throw new \Exception("Stock could not be reduced (not in stock or SKU unknown).");
        }
    }

    public function prepare(string $sku): bool {
        $this->pdo->beginTransaction();
        $this->reduceStock($sku);

        return true;
    }

    public function commit(): void {
        $this->pdo->commit();
    }
}
