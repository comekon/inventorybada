<?php

namespace App\Models;

use PDO;

class RawDb
{
    private PDO $pdo;

    public function __construct()
    {
        // Ambil PDO default dari Laravel – tanpa Query Builder/Eloquent
        $this->pdo = app('db')->getPdo();
    }

    /** Ambil semua baris dari query sederhana (tanpa input user) */
    public function fetchAll(string $sql): array
    {
        $stmt = $this->pdo->query($sql);
        return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    /** Ambil satu nilai (mis. COUNT(*)) */
    public function fetchScalar(string $sql)
    {
        $stmt = $this->pdo->query($sql);
        return $stmt ? $stmt->fetchColumn() : null;
    }

    /** Ambil data dengan parameter (prepared statement) */
    public function fetchAllPrepared(string $sql, array $params = []): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
