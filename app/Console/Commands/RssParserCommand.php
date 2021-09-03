<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\RssParserController;
use App\Http\Controllers\RssConverterController;
use App\Models\DataForRssNewsRequest;
use Resolute\PseudoDaemon\IsPseudoDaemon;

class RssParserCommand extends Command
{

    use IsPseudoDaemon;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rss:parser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'News Rss Parser';


    protected RssParserController $parser;


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
        $this->runAsPseudoDaemon();

    }


    public function process()
    {

        $settings = new DataForRssNewsRequest();
        $converter = new RssConverterController($settings);
        $parser = new RssParserController($converter);
        $parser->StartScrapping();

    }
}
