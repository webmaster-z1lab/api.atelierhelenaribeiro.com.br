<?php

namespace Modules\Sales\Repositories;

use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Modules\Employee\Models\EmployeeTypes;
use Modules\Sales\Models\Information;
use Modules\Sales\Models\Packing;
use Modules\Sales\Models\PaymentMethod;
use Modules\Sales\Models\Payroll;
use Modules\Sales\Models\Sale;
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
        $visit->sale()->associate(new Information());
        $visit->payroll()->associate(new Information());
        $visit->payroll_sale()->associate(new Information());

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
        abort_if($visit->status === Visit::FINALIZED_STATUS, 400, 'A visita já foi finalizada.');

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
        abort_if($visit->status === Visit::FINALIZED_STATUS, 400, 'A visita já foi finalizada.');

        if (Sale::where('visit_id', $visit->id)->exists() ||
            Payroll::where('visit_id', $visit->id)->orWhere('completion_visit_id', $visit->id)->exists()) {
            abort(400, 'Já existem transações relacionadas a essa visita.');
        }

        return $visit->delete();
    }

    /**
     * @param  array                        $data
     * @param  \Modules\Sales\Models\Visit  $visit
     *
     * @return \Modules\Sales\Models\Visit
     * @throws \Illuminate\Validation\ValidationException
     */
    public function close(array $data, Visit $visit): Visit
    {
        abort_if($visit->status === Visit::FINALIZED_STATUS, 400, 'A visita já foi finalizada.');

        abort_if($visit->status === Visit::CLOSED_STATUS, 400, 'A visita já foi fechada.');

        $customer_credit = $visit->customer->credit ?? 0;

        if (!empty($data['payment_methods'])) {
            $methods = $this->createPaymentMethods($data['payment_methods'], $visit->total_price - $visit->discount - $customer_credit);

            foreach ($methods as $method) {
                $visit->payment_methods()->associate($method);
            }
        } elseif ($visit->total_price - $visit->discount - $customer_credit > 0) {
            throw ValidationException::withMessages(['payment_methods' => [trans('validation.required', ['attribute' => 'métodos de pagamentos'])]]);
        }

        $visit->update(['status' => Visit::CLOSED_STATUS]);

        return $visit;
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

    /**
     * @param  array  $methods
     * @param  int    $expected_total
     *
     * @return array
     */
    private function createPaymentMethods(array $methods, int $expected_total): array
    {
        $total = 0;
        $result = [];
        foreach ($methods as $method) {
            $method['value'] = (int) ((float) $method['value'] * 100);
            $result[] = new PaymentMethod($method);
            $total += $method['value'];
        }

        abort_if($expected_total !== $total, 400, 'Os valores de pagamentos informados não coincidem com o total da visita.');

        return  $result;
    }
}
