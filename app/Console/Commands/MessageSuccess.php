<?php

namespace App\Console\Commands;

use App\Http\Controllers\ApiController;
use Illuminate\Console\Command;

class MessageSuccess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'message:success';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This message is for a successfull transaction';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        info('Your transaction was successful');
        $status = new ApiController();
        $response = $status->transactionStatus();
    }
}
