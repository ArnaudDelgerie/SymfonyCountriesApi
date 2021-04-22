<?php

namespace App\Serializer\Schema;

class LanguageSchema
{
    public function fetchLanguages()
    {
        return [
            "attributes" => [
                "name",
                "code"
            ]
        ];
    }
}