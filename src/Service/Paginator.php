<?php

namespace Hardkode\Service;

/**
 * Class Paginator
 * @package Hardkode\Service
 */
class Paginator
{

    /** @var array */
    private $items;

    /** @var int */
    private $itemsPerPage;

    /**
     * Paginator constructor.
     * @param array $items
     * @param int   $itemsPerPage
     */
    public function __construct(array $items, $itemsPerPage = 5)
    {
        $this->items        = $items;
        $this->itemsPerPage = $itemsPerPage;
    }

    /**
     * @param int $page
     * @return array
     */
    public function getPage(int $page): array
    {
        return $this->getPages()[$page - 1] ?? [];
    }

    /**
     * @return array
     */
    public function getPages(): array
    {
        return array_chunk($this->items, $this->itemsPerPage);
    }

    /**
     * @param int $currentPage
     *
     * @return string
     */
    public function getPagination($currentPage = 0)
    {
        $itemCount = 0;

        $items = implode('', array_map(function($item) use (&$itemCount, $currentPage) {
            $itemCount++;
            return '<a href="?page=' . $itemCount . '" class="item' . ($currentPage === $itemCount ? ' active' : '') . '">' . $itemCount . '</a>';
        }, $this->getPages()));

        $currentPage = ($currentPage === 0) ? 1 : $currentPage;
        $previousPage = ($currentPage > 0) ? $currentPage - 1 : 1;
        $nextPage = ($currentPage < count($this->getPages())) ? $currentPage + 1 : $currentPage;

        return <<<HTML
            <div class="pagination">
            <a href="?page={$previousPage}" class="item"><i class="icon icon-arrow-left"></i></a>
            {$items}
            <a href="?page={$nextPage}" class="item"><i class="icon icon-arrow-right"></i></a>
            </div>
HTML;

    }

}