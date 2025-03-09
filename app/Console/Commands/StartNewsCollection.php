<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsCollectorService\TheGuardianNewsCollector;
use App\Services\NewsCollectorService\NyTimesNewsCollector;

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
    
    function __construct(
        TheGuardianNewsCollector $theGuardianNewsCollector,
        NyTimesNewsCollector $nyTimesNewsCollector,
    ) {
        parent::__construct();
        $this->theGuardianNewsCollector = $theGuardianNewsCollector;
        $this->nyTimesNewsCollector = $nyTimesNewsCollector;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->theGuardianNewsCollector->run();
        $this->nyTimesNewsCollector->run();
    }
}
