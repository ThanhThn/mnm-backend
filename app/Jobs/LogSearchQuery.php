<?php

namespace App\Jobs;

use App\Models\SearchLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LogSearchQuery implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $query;
    public $type;
    /**
     * Create a new job instance.
     */
    public function __construct($query, $type)
    {
        $this->query = $query;
        $this->type = $type;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        SearchLog::create([
            'query' => $this->query,
            'type' => $this->type,
        ]);
    }
}
