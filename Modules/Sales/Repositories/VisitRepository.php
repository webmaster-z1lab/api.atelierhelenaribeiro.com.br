<?php

namespace Modules\Sales\Repositories;

use Carbon\Carbon;
use Modules\Employee\Models\EmployeeTypes;
use Modules\Sales\Models\Visit;

class VisitRepository
{
    public function all(bool $paginate = TRUE, int $items = 10)
    {
        if (\Auth::user()->type === EmployeeTypes::TYPE_ADMIN) {
            return Visit::orderBy('date', 'desc')->take(30)->get();
        }

        return Visit::where('seller_id', \Auth::id())->orderBy('date', 'desc')->take(30)->get();
    }

    /**
     * @param  array  $data
     *
     * @return \Modules\Sales\Models\Visit
     */
    public function create(array $data): Visit
    {
        $data['date'] = Carbon::createFromFormat('d/m/Y', $data['date']);

        $visit = new Visit($data);

        $visit->seller()->associate(\Auth::id());
        $visit->customer()->associate($data['customer']);

        $visit->save();

        return $visit;
    }

    /**
     * @param  array                        $data
     * @param  \Modules\Sales\Models\Visit  $visit
     *
     * @return \Modules\Sales\Models\Visit
     */
    public function update(array $data, Visit $visit): Visit
    {
        $data['date'] = Carbon::createFromFormat('d/m/Y', $data['date']);

        $visit->seller()->associate(\Auth::id());
        $visit->customer()->associate($data['customer']);

        $visit->update($data);

        return $visit;
    }

    /**
     * @param  \Modules\Sales\Models\Visit  $visit
     *
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Visit $visit)
    {
        if ($visit->sales()->exists() || $visit->payrolls()->exists()) {
            abort(400, 'Já existem transações relacionadas a essa visita.');
        }

        return $visit->delete();
    }
}
