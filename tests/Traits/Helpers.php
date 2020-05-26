<?php

namespace Tests\Traits;

use DB;
use Exception;
use Illuminate\Database\Query\Expression;
use Psy\Util\Json;
use RuntimeException;

trait Helpers
{
    /**
     * Generate a raw DB query to search for a JSON field.
     *
     * @param  array|json  $json
     *
     * @return Expression
     *
     */
    public function castToJson($json): Expression
    {
        if (is_array($json)) {
            $json = addslashes(json_encode($json));
        } elseif ($json === null || json_decode($json) === null) {
            return null;
        }
        return DB::raw("CAST('{$json}' AS JSON)");
    }
}
