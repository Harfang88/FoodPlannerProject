<?php

namespace App\Utils;

class IngredientSerializer
{
    /**
     * @return String
     */
    public function serializer($ingredientToConvert) : string
    {
        $result = ucfirst(trim($ingredientToConvert));

        return $result;
    }
}