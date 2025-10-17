<?php

namespace App\Traits;

trait HasCustomPagination
{
    public function splitDataAndPagination($items, $perPage, $url): array
    {
        $results = $items
            ->paginate($perPage)
            ->toArray();
        $data = $results['data'];

        $pagination = [
            'atual' => $results['current_page'],
            'ultima' => $results['last_page'],
            'url_base' => "$url&page=",
            'total' => $results['total'],
            'paginacao' => $results['per_page'],
            'ordinalPrimeiro' => ($results['current_page'] - 1) * $results['per_page'] + 1,
            'ordinalUltimo' => (
                $results['current_page'] === $results['last_page']
                    ? $results['total']
                    : $results['per_page'] * $results['current_page']
            )
        ];
        if ($results['current_page'] !== $results['last_page']) {
            $pagination['proxima'] = $results['current_page'] + 1;
            $pagination['url_proxima'] = $pagination['url_base'] . $pagination['proxima'];
        }
        if ($results['current_page'] !== 1) {
            $pagination['anterior'] = $results['current_page'] - 1;
            $pagination['url_anterior'] = $pagination['url_base'] . $pagination['anterior'];
        }

        if ($pagination['ultima'] > 1) {
            $pagination['paginas'] = [1];
            for($i = 2; $i < $pagination['ultima']; ++$i) {
                if($pagination['ultima'] > 10 && abs($i - $pagination['atual']) < 3) {
                    $pagination['paginas'][] = $i;
                }
                if ($pagination['ultima'] <= 10) {
                    $pagination['paginas'][] = $i;
                }
            }
            $pagination['paginas'][] = $pagination['ultima'];
        }

        return [
            'data' => $data,
            'pagination' => $pagination
        ];
    }
}
