<?php

namespace App\Support;

use Illuminate\Pagination\LengthAwarePaginator;

class PaginationData
{
    public static function from(LengthAwarePaginator $paginator): array
    {
        return [
            'data'  => $paginator->items(),
            'meta'  => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
            ],
            'links' => [
                'next' => $paginator->nextPageUrl(),
                'prev' => $paginator->previousPageUrl(),
            ],
        ];
    }

    public static function empty(): array
    {
        return [
            'data'  => [],
            'meta'  => [
                'current_page' => 1,
                'last_page'    => 1,
                'per_page'     => 20,
                'total'        => 0,
            ],
            'links' => [
                'next' => null,
                'prev' => null,
            ],
        ];
    }
}
