<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ArticleService;

class ScrapeArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:articles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(ArticleService $articleService)
    {
        $articleService->scrape();
        return Command::SUCCESS;
    }
}
