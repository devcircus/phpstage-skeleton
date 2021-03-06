<?php

namespace App\Macros;

use Illuminate\Support\Arr;
use Illuminate\Pagination\LengthAwarePaginator;

class Collection
{
    /**
     * Macro helper that will return all of the given keys from the collection.
     * If keys are passed, return those as well, even if they don't exist.
     *
     * @return \Illuminate\Support\Collection
     */
    public function obtain()
    {
        return function ($keys) {
            $items = $this->items;

            if (! $keys) {
                return $items;
            }

            $results = [];

            foreach (is_array($keys) ? $keys : func_get_args() as $key) {
                Arr::set($results, $key, Arr::get($items, $key));
            }

            return collect($results);
        };
    }

    /**
     * Paginate a Collection.
     *
     * @param  int  $perpage
     *
     * @return array
     */
    public function paginate()
    {
        return function (int $perPage = 5) {
            $items = $this->all();
            $page = (int) request()->input('page') ?: 1;
            $offSet = ($page * $perPage) - $perPage;
            $itemsForCurrentPage = array_slice($items, $offSet, $perPage, true);

            $result = app()->make(LengthAwarePaginator::class, [
                'items' => $itemsForCurrentPage,
                'total' => count($items),
                'perPage' => $perPage,
                'currentPage' => $page,
                [
                    'path'  => '/home',
                ],
            ]);

            return $result->toArray();
        };
    }
}
