<?php

namespace App\Services\Utility;


class GenerateRandomLetters
{
    public function randomLetters($length = 8)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $generatedLetters = '';
        for ($i = 0; $i < $length; $i++) {
            $generatedLetters .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $generatedLetters;
    }

}