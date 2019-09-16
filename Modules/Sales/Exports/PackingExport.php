<?php

namespace Modules\Sales\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PackingExport implements FromArray, WithHeadings
{
    /**
     * @var array
     */
    private $data;

    /**
     * PackingExport constructor.
     *
     * @param  array  $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function array(): array
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Referência',
            'Tamanho',
            'Cor',
            'Preço unitário',
            'Quantidade',
            'Total'
        ];
    }
}
