<?php
namespace R64\NovaPassportAccessTokens;

use Illuminate\Database\Eloquent\Collection;

interface NovaIssuableToken
{
    public static function getForNova(): Collection;
}