<?php

namespace App\Console\Commands;

use App\Http\Controllers\EmployeeCoeController;
use Illuminate\Console\Command;

class NotifyCoeReminder extends Command
{
    protected $signature = 'coe:remind-pending';
    protected $description = 'Notify approvers of COE requests pending for 7+ days';

    public function handle()
    {
        $controller = app(EmployeeCoeController::class);
        $result = $controller->notifyHr();
        $this->info($result);
    }
}