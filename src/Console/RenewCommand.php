<?php

namespace Sausin\Signere\Console;

use Exception;
use Sausin\Signere\ApiKey;
use Illuminate\Console\Command;

class RenewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'signere:renew 
                            {--key= : Specify the key to be renewed}
                            {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Renews your primary or secondary signere key';

    /**
     * The ApiKey instance.
     *
     * @var ApiKey
     */
    protected $apiKey;

    /**
     * Create a new command instance.
     *
     * @param ApiKey $apiKey
     */
    public function __construct(ApiKey $apiKey)
    {
        parent::__construct();

        $this->apiKey = $apiKey;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $all = $this->option('all');

        try {
            if ($all) {
                $this->info('Trying to renew both your keys...');

                $this->apiKey->renewPrimary(config('signere.primary_key'));
                $this->apiKey->renewSecondary(config('signere.secondary_key'));

                $this->info('Both your keys were renewed!');

                return;
            }

            $key = $this->option('key') ?: 'primary';
            $this->info("Trying to renew your {$key} key...");

            if ($key === 'secondary') {
                $this->apiKey->renewSecondary(config('signere.secondary_key'));
            } else {
                $this->apiKey->renewPrimary(config('signere.primary_key'));
            }

            $this->info("Your {$key} key was renewed!");

            return;
        } catch (Exception $e) {
            $this->error("Renewing failed because: {$e->getMessage()}.");

            return -1;
        }
    }
}
