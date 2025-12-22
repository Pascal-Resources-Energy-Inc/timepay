<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ForfeitExpiredPurchases extends Command
{
    protected $signature = 'purchases:forfeit-expired';
    protected $description = 'Forfeit purchases that have not been claimed within 3 business days (excluding weekends)';

    public function handle()
    {
        $expiredCount = 0;
        
        $purchases = DB::table('purchases')
            ->where('status', 'Processing')
            ->get();
        
        foreach ($purchases as $purchase) {
            $createdAt = new \DateTime($purchase->created_at);
            $expiresAt = $this->addBusinessDays($createdAt, 3);
            $now = new \DateTime();
            
            if ($now > $expiresAt) {
                DB::table('purchases')
                    ->where('id', $purchase->id)
                    ->update([
                        'status' => 'Forfeited',
                        'updated_at' => now()
                    ]);
                
                $expiredCount++;
            }
        }
        
        $this->info("Forfeited {$expiredCount} expired purchase(s).");
        
        return 0;
    }
    
    private function addBusinessDays($date, $days)
    {
        $currentDate = clone $date;
        $addedDays = 0;
        
        while ($addedDays < $days) {
            $currentDate->modify('+1 day');
            $dayOfWeek = (int)$currentDate->format('N');
            if ($dayOfWeek < 6) {
                $addedDays++;
            }
        }
        
        return $currentDate;
    }
}