<?php


namespace Modules\Stock\Repositories;


use Modules\Stock\Models\Color;

class ColorRepository
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function all()
    {
        if (\Request::filled('search')) {
            return Color::where('name', 'like', '%' . \Request::query('search') . '%')
                ->orderBy('name')
                ->get();
        }

        return Color::orderBy('name')
            ->get();
    }

    /**
     * @param  array  $data
     *
     * @return \Modules\Stock\Models\Color
     */
    public function create(array $data): Color
    {
        return Color::create($data);
    }

    /**
     * @param  array                        $data
     * @param  \Modules\Stock\Models\Color  $color
     *
     * @return \Modules\Stock\Models\Color
     */
    public function update(array $data, Color $color): Color
    {
        $color->update($data);

        return  $color;
    }

    /**
     * @param  \Modules\Stock\Models\Color  $color
     *
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Color $color)
    {
        return $color->delete();
    }
}
