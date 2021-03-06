<?php

namespace App\Queries;

use \Illuminate\Database\Eloquent\Builder;

trait KeywordSearchTrait
{
    /**
     * Builds search functionality to query
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $keyword
     * @param array $fields
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function keywordSearch(Builder $query, string $keyword, array $fields = []): Builder
    {
        if (!count($fields)) return $query;

        // parse string to remove suspicious characters
        $keyword = preg_replace(array('#[\\s-]+#', '#[^A-Za-z0-9. -]+#'), array('-', ''), urldecode(trim($keyword)));

        $keywordParts = array_filter(explode("-", $keyword), fn ($part) => !!trim($part));

        $query = $query->where(function ($query) use ($fields, $keywordParts) {
            if (count($keywordParts)) {
                foreach ($fields as $field) {
                    $query = $query->orWhere(function ($query) use ($keywordParts, $field) {
                        foreach ($keywordParts as $part) {
                            $query = $query->where($field, 'LIKE', '%'.$part.'%');
                        }
                    });
                }
            }
        });

        return $query;
    }
}
