<?php

namespace Modules\Sales\Repositories;

use Carbon\Carbon;
use Modules\Employee\Models\EmployeeTypes;
use Modules\Sales\Models\Packing;
use Modules\Sales\Models\Visit;

class VisitRepository
{
    /**
     * @param  bool  $paginate
     * @param  int   $items
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
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
        if (!isset($data['seller'])) {
            $data['seller'] = \Auth::id();
        }

        $packing = $this->getCurrentPacking($data['seller']);

        $data['date'] = Carbon::createFromFormat('d/m/Y', $data['date']);

        $visit = new Visit($data);

        $visit->seller()->associate($data['seller']);
        $visit->customer()->associate($data['customer']);
        $visit->packing()->associate($packing);

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
        if (!isset($data['seller'])) {
            $data['seller'] = \Auth::id();
        }

        $packing = $this->getCurrentPacking($data['seller']);

        $data['date'] = Carbon::createFromFormat('d/m/Y', $data['date']);

        $visit->seller()->associate($data['seller']);
        $visit->customer()->associate($data['customer']);
        $visit->packing()->associate($packing);

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
        if ($visit->sale()->exists() || $visit->payroll()->exists()) {
            abort(400, 'Já existem transações relacionadas a essa visita.');
        }

        return $visit->delete();
    }

    /**
     * @param  string  $seller
     *
     * @return \Modules\Sales\Models\Packing
     */
    private function getCurrentPacking(string $seller): Packing
    {
        $packing = Packing::where('seller_id', $seller)
            ->where(function ($query) {
                $query->where('checked_out_at', 'exists', FALSE)->orWhereNull('checked_out_at');
            })->first();

        abort_if(is_null($packing), 400, 'Não existe um romaneio registrado para o vendedor.');

        return $packing;
    }
}
