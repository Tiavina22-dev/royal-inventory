<?php

declare(strict_types=1);

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function url(string $path = ''): string
{
    $base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
    return ($base === '' ? '' : $base) . '/' . ltrim($path, '/');
}

function redirect(string $path): void
{
    header('Location: ' . url($path));
    exit;
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function flashes(): array
{
    $messages = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $messages;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(): void
{
    $token = $_POST['_token'] ?? '';
    if (!is_string($token) || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(419);
        exit('Jeton de securite invalide.');
    }
}

function money($value): string
{
    return number_format((float) $value, 0, '', ' ');
}

function operation_badge(array $row): array
{
    $type = strtolower(trim((string) ($row['type_de_mvt'] ?? $row['type'] ?? '')));
    $status = strtolower(trim((string) ($row['status'] ?? '')));
    $description = strtolower(trim((string) ($row['description_date'] ?? $row['description'] ?? '')));
    $qty = (float) ($row['qt'] ?? $row['quantite'] ?? 0);

    if (str_contains($status, 'general_inventory') || str_contains($description, 'general_inventory')) {
        return ['General Inventory', 'operation-badge--control'];
    }
    if (str_contains($description, 'abandon') || str_contains($description, 'retour') || $type === 'retour') {
        return ['Retour', 'operation-badge--return'];
    }
    if ($type === 'vente') {
        return ['Vente', 'operation-badge--sale'];
    }
    if ($type === 'facture') {
        return ['Facture', 'operation-badge--invoice'];
    }
    if ($type === 'stock') {
        return $qty < 0 ? ['Sortie', 'operation-badge--exit'] : ['Entree', 'operation-badge--entry'];
    }
    if ($qty < 0) {
        return ['Sortie', 'operation-badge--exit'];
    }
    if ($qty > 0) {
        return ['Entree', 'operation-badge--entry'];
    }

    return [$row['type_de_mvt'] ?? $row['type'] ?? 'Operation', 'operation-badge--info'];
}

function today_description(?string $date, string $prefix): string
{
    $date = $date ?: date('Y-m-d');
    $dt = DateTime::createFromFormat('Y-m-d', $date);
    return $prefix . ' du ' . ($dt ? $dt->format('d/m/y') : date('d/m/y'));
}

function legacy_url(array $legacyRoutes, string $key, string $base): string
{
    $target = $legacyRoutes[$key] ?? '';
    return rtrim($base, '/') . '/' . ltrim($target, '/');
}

function current_page(): string
{
    return preg_replace('/[^a-z0-9_-]/i', '', $_GET['page'] ?? 'dashboard') ?: 'dashboard';
}

function require_post(): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
        http_response_code(405);
        exit('Methode non autorisee.');
    }
}
