<?php

namespace Modules\Paycheck\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Paycheck\Models\Paycheck;
use Modules\Sales\Models\Visit;

class CreatePaychecks implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;
    /**
     * @var \Modules\Sales\Models\Visit
     */
    public $visit;

    /**
     * @var array
     */
    public $data;

    /**
     * Create a new job instance.
     *
     * @param  \Modules\Sales\Models\Visit  $visit
     * @param  array                        $data
     */
    public function __construct(Visit $visit, array $data)
    {
        $this->visit = $visit->fresh();
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Paycheck::where('visit_id', $this->visit->id)->delete();

        foreach ($this->data as $datum) {
            $datum['received_at'] = $this->visit->date;
            $datum['pay_date'] = Carbon::createFromFormat('d/m/Y', $datum['pay_date']);
            $datum['received_by'] = $this->visit->seller->name;

            $paycheck = new Paycheck($datum);

            $paycheck->visit()->associate($this->visit);

            $paycheck->save();
        }
    }
}
