<?php

namespace App\Core;

class Pagination
{
    private int $currentPage;
    private int $perPage;
    private int $total;
    private int $lastPage;
    private string $baseUrl;

    public function __construct(int $currentPage, int $perPage, int $total, string $baseUrl)
    {
        $this->currentPage = max(1, $currentPage);
        $this->perPage = max(1, $perPage);
        $this->total = max(0, $total);
        $this->lastPage = max(1, (int) ceil($this->total / $this->perPage));
        $this->currentPage = min($this->currentPage, $this->lastPage);
        $this->baseUrl = $baseUrl;
    }

    public static function paginate(string $table, string $where, array $params, int $page = 1, int $perPage = 15, string $baseUrl = ''): array
    {
        $db = Database::getInstance();

        // Total
        $countSql = "SELECT COUNT(*) as total FROM {$table} WHERE {$where}";
        $countStmt = $db->prepare($countSql);
        $countStmt->execute($params);
        $total = (int) $countStmt->fetch()['total'];

        $pagination = new self($page, $perPage, $total, $baseUrl);

        // Data
        $offset = $pagination->offset();
        $sql = "SELECT * FROM {$table} WHERE {$where} ORDER BY date_creation DESC LIMIT :limit OFFSET :offset";
        $stmt = $db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(is_string($key) ? ":{$key}" : ($key + 1), $value);
        }
        $stmt->bindValue(':limit', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        $items = $stmt->fetchAll();

        return [
            'items' => $items,
            'pagination' => $pagination,
        ];
    }

    public function offset(): int
    {
        return ($this->currentPage - 1) * $this->perPage;
    }

    public function currentPage(): int
    {
        return $this->currentPage;
    }

    public function lastPage(): int
    {
        return $this->lastPage;
    }

    public function total(): int
    {
        return $this->total;
    }

    public function perPage(): int
    {
        return $this->perPage;
    }

    public function hasPages(): bool
    {
        return $this->lastPage > 1;
    }

    public function hasMorePages(): bool
    {
        return $this->currentPage < $this->lastPage;
    }

    public function previousPage(): ?int
    {
        return $this->currentPage > 1 ? $this->currentPage - 1 : null;
    }

    public function nextPage(): ?int
    {
        return $this->hasMorePages() ? $this->currentPage + 1 : null;
    }

    public function url(int $page): string
    {
        $separator = str_contains($this->baseUrl, '?') ? '&' : '?';
        return "{$this->baseUrl}{$separator}page={$page}";
    }

    public function render(): string
    {
        if (!$this->hasPages()) {
            return '';
        }

        $html = '<nav class="flex items-center justify-center gap-1 mt-8" aria-label="Pagination">';

        // Previous
        if ($prev = $this->previousPage()) {
            $html .= '<a href="' . $this->url($prev) . '" class="px-3 py-2 rounded-lg text-sm font-medium text-gray-500 hover:bg-violet-50 hover:text-violet-700 transition">← Précédent</a>';
        }

        // Pages
        for ($i = 1; $i <= $this->lastPage; $i++) {
            if ($i === $this->currentPage) {
                $html .= '<span class="px-3 py-2 rounded-lg text-sm font-medium bg-violet-600 text-white">' . $i . '</span>';
            } else {
                $html .= '<a href="' . $this->url($i) . '" class="px-3 py-2 rounded-lg text-sm font-medium text-gray-500 hover:bg-violet-50 hover:text-violet-700 transition">' . $i . '</a>';
            }
        }

        // Next
        if ($next = $this->nextPage()) {
            $html .= '<a href="' . $this->url($next) . '" class="px-3 py-2 rounded-lg text-sm font-medium text-gray-500 hover:bg-violet-50 hover:text-violet-700 transition">Suivant →</a>';
        }

        $html .= '</nav>';
        return $html;
    }
}
