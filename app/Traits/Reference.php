<?php

namespace App\Traits;

trait Reference
{
    protected function getNewReference(string $class, int $length, string $field = 'reference'): string
    {
        do {
            $reference = strtoupper(\Str::random($length));
        } while ($class::where($field, $reference)->exists());

        return $reference;
    }

    protected function getNewNumericReference(string $class, int $length, string $field = 'reference'): string
    {
        do {
            $reference = '';
            for ($i = 0; $i < $length; $i++) {
                $reference .= rand(0, 9);
            }
        } while ($class::where($field, $reference)->exists());

        return $reference;
    }
}
