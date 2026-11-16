<?php
require_once __DIR__ . '/../Db.php';

class User
{
    protected ?int $id;
    protected ?string $name;
    protected ?string $email;
    protected ?string $phone;
    protected ?string $password;
    protected string $role;
    private PDO $connection;

    public function __construct(
        ?string $email = null,
        ?string $password = null,
        ?string $name = null,
        ?string $phone = null,
        string $role = 'client',
        ?int $id = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->password = $password;
        $this->role = $role;
        $this->connection = Db::getInstance()->getConnection();
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(?string $name): self { $this->name = $name; return $this; }
    public function getEmail(): ?string { return $this->email; }
    public function setEmail(?string $email): self { $this->email = $email; return $this; }
    public function getPhone(): ?string { return $this->phone; }
    public function setPhone(?string $phone): self { $this->phone = $phone; return $this; }
    public function getPassword(): ?string { return $this->password; }
    public function setPassword(?string $password): self { $this->password = $password; return $this; }
    public function getRole(): string { return $this->role; }
    public function setRole(string $role): self { $this->role = $role; return $this; }

    public function register(): bool
    {
        $stmt = $this->connection->prepare("
            INSERT INTO users (name, email, phone, password, role)
            VALUES (:name, :email, :phone, :password, :role)
        ");

        $hashed = password_hash($this->password, PASSWORD_DEFAULT);

        $success = $stmt->execute([
            'name'     => $this->name,
            'email'    => $this->email,
            'phone'    => $this->phone,
            'password' => $hashed,
            'role'     => $this->role
        ]);

        if ($success) {
            $this->id = (int)$this->connection->lastInsertId();
        }

        return $success;
    }

    public function login(): bool
    {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $this->email]);
        $userData = $stmt->fetch();

        if ($userData) {
            // Vérification mot de passe avec password_verify ou sha1 pour compatibilité TP1
            if (password_verify($this->password, $userData['password']) || sha1($this->password) === $userData['password'] || $this->password === $userData['password']) {
                $this->id = (int)$userData['id'];
                $this->name = $userData['name'];
                $this->phone = $userData['phone'];
                $this->role = $userData['role'] ?? 'client';

                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['user_id'] = $this->id;
                $_SESSION['user_name'] = $this->name;
                $_SESSION['email'] = $this->email;
                $_SESSION['role'] = $this->role;
                return true;
            }
        }
        return false;
    }

    public static function findByEmail(string $email): ?self
    {
        $db = Db::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();

        if (!$row) return null;

        return new self(
            $row['email'],
            $row['password'],
            $row['name'],
            $row['phone'],
            $row['role'] ?? 'client',
            (int)$row['id']
        );
    }
}
