<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\SalesTarget;
use Carbon\Carbon;
use DB;

class GenerateMonthlySalesTarget extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected $signature = 'sales:generate-monthly';
    protected $description = 'Generate monthly sales target for Existing employees';

    public function handle()
    {
        $currentMonth = Carbon::now()->format('Y-m');
        $lastMonth = Carbon::now()->subMonth()->format('Y-m');

        DB::beginTransaction();

        try {

            // Get all EXISTING employees from last month
            $targets = SalesTarget::where('type', 'Existing')
                ->where('month', $lastMonth)
                ->whereHas('user', function ($query) {
                    $query->where('status', 'Active'); 
                })
                ->get();

            foreach ($targets as $target) {

                // Prevent duplicate
                $exists = SalesTarget::where('user_id', $target->user_id)
                    ->where('month', $currentMonth)
                    ->exists();

                if (!$exists) {

                    SalesTarget::create([
                        'user_id' => $target->user_id,
                        'type' => 'Existing',
                        'month' => $currentMonth,
                        'target_amount' => $target->target_amount,
                        'notes' => $target->notes,
                    ]);

                    $this->info("Generated target for User ID: {$target->user_id}");
                }
            }

            DB::commit();
            $this->info('Monthly Sales Target Generation Completed.');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error($e->getMessage());
        }
    }

}
