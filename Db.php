<?php

class Db
{
    private static ?Db $instance = null;
    private ?PDO $connection = null;

    public function __construct($host = null, $dbname = null, $username = null, $password = null, $port = null)
    {
        $configFile = __DIR__ . '/config/database.php';
        $config = file_exists($configFile) ? require $configFile : [];

        $host     = $host     ?? ($config['host']     ?? '127.0.0.1');
        $dbname   = $dbname   ?? ($config['dbname']   ?? 'automatch');
        $username = $username ?? ($config['username'] ?? 'root');
        $password = $password ?? ($config['password'] ?? 'root');
        $port     = $port     ?? ($config['port']     ?? 8889);

        // Tentative de connexion MySQL
        try {
            $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
            $this->connection = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            // Tentative port 3306 standard
            try {
                $dsn = "mysql:host={$host};port=3306;dbname={$dbname};charset=utf8mb4";
                $this->connection = new PDO($dsn, $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
            } catch (PDOException $e2) {
                // Fallback SQLite pour résilience totale (en cas d'absence de serveur MySQL)
                $sqlitePath = __DIR__ . '/database.sqlite';
                $this->connection = new PDO("sqlite:" . $sqlitePath, null, null, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
                $this->initSqlite();
            }
        }
    }

    public static function getInstance(): Db
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    private function initSqlite(): void
    {
        $this->connection->exec("
            CREATE TABLE IF NOT EXISTS vehicles (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                brand TEXT NOT NULL,
                model TEXT NOT NULL,
                version TEXT NOT NULL,
                year INTEGER NOT NULL,
                mileage INTEGER NOT NULL,
                fuel TEXT NOT NULL,
                price INTEGER NOT NULL,
                image TEXT NOT NULL,
                description TEXT,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS contact_requests (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                vehicle_id INTEGER NOT NULL,
                name TEXT NOT NULL,
                email TEXT NOT NULL,
                phone TEXT,
                message TEXT NOT NULL,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS reservations (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                vehicle_id INTEGER NOT NULL,
                user_id INTEGER,
                customer_name TEXT,
                customer_email TEXT,
                customer_phone TEXT,
                status TEXT DEFAULT 'confirmée',
                created_at TEXT DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                email TEXT UNIQUE NOT NULL,
                phone TEXT,
                password TEXT NOT NULL,
                role TEXT DEFAULT 'client',
                created_at TEXT DEFAULT CURRENT_TIMESTAMP
            );
        ");

        $count = $this->connection->query("SELECT count(*) FROM vehicles")->fetchColumn();
        if ($count == 0) {
            $stmt = $this->connection->prepare("
                INSERT INTO vehicles (id, brand, model, version, year, mileage, fuel, price, image, description)
                VALUES 
                (1, 'BMW', 'Série 2', 'Active - 216i M Sport 5p', 2018, 74540, 'Essence', 18490, 'public/images/bmw-serie-2.jpg', 'BMW Série 2 Active Tourer finition M Sport avec boîte manuelle, motorisation 216i essence de 2018 avec 74 540 km. Véhicule en excellent état, révisé et garanti.'),
                (2, 'Peugeot', '3008', 'GT Line - 1.2 PureTech 130ch', 2019, 71821, 'Diesel', 19490, 'public/images/peugeot-3008.jpg', 'Peugeot 3008 finition haut de gamme GT Line motorisation 1.2 PureTech 130 chevaux, année 2019, 71 821 km. Confort exceptionnel et design dynamique.'),
                (3, 'Honda', 'Civic', 'Executive - 1.0 i-VTEC 126', 2019, 93350, 'Essence', 14890, 'public/images/honda-civic.jpg', 'Honda Civic 1.0 i-VTEC finition Executive, année 2019, 93 350 km. Berline compacte spacieuse et fiable avec feux LED, régulateur adaptatif et caméra de recul.')
            ");
            $stmt->execute();
        }

        $userCount = $this->connection->query("SELECT count(*) FROM users")->fetchColumn();
        if ($userCount == 0) {
            $stmtUser = $this->connection->prepare("
                INSERT INTO users (id, name, email, phone, password, role)
                VALUES (1, 'Camille Martin', 'camille.martin@exemple.fr', '06 12 34 56 78', :password, 'client')
            ");
            $stmtUser->execute(['password' => password_hash('password123', PASSWORD_DEFAULT)]);
        }
    }
}
