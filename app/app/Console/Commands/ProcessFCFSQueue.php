<?php

namespace App\Console\Commands;

use App\Services\FCFSScheduler;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ProcessFCFSQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fcfs:process {--date= : The date to process (Y-m-d format)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process FCFS (First Come First Served) queue for reservations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = $this->option('date');
        
        if ($date) {
            // Validate date format
            try {
                Carbon::parse($date)->format('Y-m-d');
            } catch (\Exception $e) {
                $this->error('Invalid date format. Please use Y-m-d format (e.g., 2025-12-09)');
                return 1;
            }
        } else {
            $date = Carbon::today()->format('Y-m-d');
            $this->info("No date specified, using today: {$date}");
        }
        
        $this->info("🚀 Processing FCFS queue for date: {$date}");
        $this->newLine();
        
        $scheduler = new FCFSScheduler();
        
        try {
            $stats = $scheduler->processQueue($date);
            
            $this->info('✅ FCFS Processing Complete');
            $this->newLine();
            
            $this->table(
                ['Metric', 'Value'],
                [
                    ['Date', $stats['date']],
                    ['Processed Reservations', $stats['processed']],
                    ['Average Waiting Time', $stats['avg_waiting_time'] . ' minutes'],
                    ['Average Turnaround Time', $stats['avg_turnaround_time'] . ' minutes'],
                ]
            );
            
            if ($stats['processed'] === 0) {
                $this->warn('⚠️  No reservations found to process for this date.');
            } else {
                $this->info("📊 Successfully processed {$stats['processed']} reservations!");
            }
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('❌ Failed to process FCFS queue: ' . $e->getMessage());
            return 1;
        }
    }
}
