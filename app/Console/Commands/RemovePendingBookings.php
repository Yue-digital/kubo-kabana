<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Carbon\Carbon;

class RemovePendingBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:remove-pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove pending bookings that are older than 3 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $threeDaysAgo = Carbon::now()->subDays(3);
        
        $pendingBookings = Booking::where('status', 'pending')
            ->where('created_at', '<', $threeDaysAgo)
            ->get();

        $count = $pendingBookings->count();

        if ($count > 0) {
            foreach ($pendingBookings as $booking) {
                $booking->delete();
            }
            $this->info("Successfully removed {$count} pending bookings older than 3 days.");
        } else {
            $this->info('No pending bookings found older than 3 days.');
        }
    }
}
