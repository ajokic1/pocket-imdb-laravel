<?php

namespace App\Console\Commands;

use App\Movie;
use Illuminate\Console\Command;

class IndexMovies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'movies:index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add movies to the Elasticsearch index';

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
     * @return mixed
     */
    public function handle()
    {
        $this->info('Creating movie index...');
        Movie::createIndex($shards = null, $replicas = null);
        Movie::addAllToIndex();
        $this->info('Index created.');
    }
}
