<?php

namespace App\Console\Commands;

use App\Models\Alert;
use App\Models\AlertUser;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AlertUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:alert-user-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status alert for user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        AlertUser::where([
            [
                'send_at', '<', Carbon::now()->timestamp,
            ],
            [
                'status', '=', 'pending'
            ]
        ])->update([
            'status' => 'active'
        ]);
    }
}
