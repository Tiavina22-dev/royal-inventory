<?php

declare(strict_types=1);

final class Auth
{
    private Database $db;
    private array $config;

    public function __construct(Database $db, array $config)
    {
        $this->db = $db;
        $this->config = $config;
    }

    public function login(string $username, string $password): bool
    {
        try {
            $user = $this->db->fetch('SELECT * FROM user WHERE User_Name = ?', [$username]);
        } catch (Throwable $e) {
            if ($this->config['allow_offline_login'] && $username === 'admin' && $password === 'admin123') {
                $this->storeSession([
                    'Id_User' => 0,
                    'Full_Name' => 'Administrateur',
                    'User_Name' => 'admin',
                    'Permission' => 'Y',
                    'Password' => 'admin123',
                    'img_path' => '',
                    'Note' => '',
                    'Departement' => 'admin',
                ]);
                return true;
            }
            throw $e;
        }

        if (!$user || (string) ($user['Password'] ?? '') !== $password) {
            return false;
        }

        if (($user['Permission'] ?? 'N') !== 'Y') {
            flash('warning', 'Compte trouve, mais permission non accordee.');
            return false;
        }

        $this->storeSession($user);
        return true;
    }

    private function storeSession(array $user): void
    {
        $_SESSION['user'] = [
            'id' => (int) ($user['Id_User'] ?? 0),
            'full_name' => (string) ($user['Full_Name'] ?? ''),
            'username' => (string) ($user['User_Name'] ?? ''),
            'permission' => (string) ($user['Permission'] ?? 'N'),
            'department' => (string) ($user['Departement'] ?? ''),
            'note' => (string) ($user['Note'] ?? ''),
            'img_path' => (string) ($user['img_path'] ?? ''),
        ];
    }

    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();
    }

    public function check(): bool
    {
        return isset($_SESSION['user']['username']);
    }

    public function user(): array
    {
        return $_SESSION['user'] ?? [];
    }

    public function username(): string
    {
        return (string) ($_SESSION['user']['username'] ?? '');
    }

    public function requireAuth(): void
    {
        if (!$this->check()) {
            redirect('login.php');
        }
    }

    public function canWrite(): bool
    {
        $user = $this->user();
        return ($user['permission'] ?? 'N') === 'Y';
    }

    public function requireWrite(): void
    {
        if (!$this->canWrite()) {
            http_response_code(403);
            exit('Action reservee aux utilisateurs autorises.');
        }
    }
}

