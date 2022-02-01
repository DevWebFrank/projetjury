<?php

namespace App\Twig;

use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;

class PriceEuroExtension extends AbstractExtension{

    public function getFilters()
    {
        return [
            new TwigFilter('priceEuro', [
                $this, 'priceEuro'
            ])
        ];
    }

    public function priceEuro($value)
    {
        $finalValue = $value / 100;

        $finalValue = number_format($finalValue, 2, ',', ' ');

        return $finalValue . ' €';
    }
}
