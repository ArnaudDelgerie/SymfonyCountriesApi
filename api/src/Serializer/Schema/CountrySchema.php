<?php

namespace App\Serializer\Schema;

class CountrySchema
{
    public function fetchCountries()
    {
        return [
            "attributes" => [
                "name",
                "country" => [
                    "alpha2",
                    "alpha3",
                    "unCode",
                    "flag"
                ]
            ]
        ];
    }
}