<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsCollectorService\TheGuardianNewsCollector;
use App\Services\NewsCollectorService\NyTimesNewsCollector;
use App\Services\NewsCollectorService\OpenNewsCollector;

class StartNewsCollection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:collect';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Collect news from configured sources';

    /**
     * Service provider objects
     */
    protected $theGuardianNewsCollector;
    protected $nyTimesNewsCollector;
    protected $openNewsCollector;
    
    function __construct(
        TheGuardianNewsCollector $theGuardianNewsCollector,
        NyTimesNewsCollector $nyTimesNewsCollector,
        OpenNewsCollector $openNewsCollector,
    ) {
        parent::__construct();
        $this->theGuardianNewsCollector = $theGuardianNewsCollector;
        $this->nyTimesNewsCollector = $nyTimesNewsCollector;
        $this->openNewsCollector = $openNewsCollector;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->theGuardianNewsCollector->run();
        $this->nyTimesNewsCollector->run();
        $this->openNewsCollector->run();
    }
}
