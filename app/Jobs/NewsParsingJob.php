<?php

namespace App\Jobs;

use App\Services\Interfaces\Parser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NewsParsingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $link;

    /**
     * Create a new job instance.
     */
    public function __construct(string $link)
    {
        $this->link = $link;
    }

    /**
     * Execute the job.
     */
    public function handle(Parser $parser): void
    {
        $parser->setLink($this->link)->saveParseData();
    }
}
