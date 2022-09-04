<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class StatusName implements CastsAttributes
{
    private const CODES = [
        "1"=>"authorized",
        "2"=>"decline",
        "3"=> "refunded"
    ];

    public function get($model, string $key, $value, array $attributes)
    {
        return self::CODES[$value]??"undefined";
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return $value;
    }
}
