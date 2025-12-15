<?php

/**
 * PaginationService
 *
 * What this file should do:
 * - Provide simple helpers for pagination calculations.
 * - Calculate limit and offset for SQL queries.
 * - Optionally generate basic pagination link data.
 */

/**
 * Calculate limit and offset for pagination.
 *
 * @param int $page      Current page number (1-based).
 * @param int $perPage   Items per page.
 * @return array [limit, offset]
 */
function pagination_limit_offset($page, $perPage = 10)
{
    $page    = max(1, (int)$page);
    $perPage = max(1, (int)$perPage);

    $limit  = $perPage;
    $offset = ($page - 1) * $perPage;

    return [$limit, $offset];
}

/**
 * Create simple pagination info array.
 *
 * @param int $totalItems
 * @param int $currentPage
 * @param int $perPage
 * @return array
 */
function pagination_info($totalItems, $currentPage, $perPage = 10)
{
    $totalItems  = max(0, (int)$totalItems);
    $perPage     = max(1, (int)$perPage);
    $totalPages  = (int)ceil($totalItems / $perPage);
    $currentPage = max(1, min((int)$currentPage, $totalPages > 0 ? $totalPages : 1));

    return [
        'total_items'  => $totalItems,
        'per_page'     => $perPage,
        'total_pages'  => $totalPages,
        'current_page' => $currentPage,
    ];
}
