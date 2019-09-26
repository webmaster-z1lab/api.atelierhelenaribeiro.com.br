<?php

namespace Modules\Stock\Repositories;

use App\Traits\Reference;
use Modules\Stock\Models\Size;

class SizeRepository
{
    use Reference;

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        if (\Request::filled('search')) {
            return Size::where('name', 'like', '%' . \Request::query('search') . '%')->latest()->get();
        }

        return Size::latest()->get();
    }

    /**
     * @param  array  $data
     *
     * @return \Modules\Stock\Models\Size
     */
    public function create(array $data): Size
    {
        $data['reference'] =  $this->getNewNumericReference(Size::class, Size::REFERENCE_LENGTH);

        return Size::create($data);
    }

    /**
     * @param  array                       $data
     * @param  \Modules\Stock\Models\Size  $size
     *
     * @return \Modules\Stock\Models\Size
     */
    public function update(array $data, Size $size): Size
    {
        $size->update($data);

        return $size;
    }

    /**
     * @param  \Modules\Stock\Models\Size  $size
     *
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Size $size)
    {
        return $size->delete();
    }
}
