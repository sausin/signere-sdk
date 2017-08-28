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
    protected $signature = 'signere:renew {--key= : Specify the key to be renewed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Renews your primary or secondary signere key';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $key = $this->option('key') ?: 'primary';
        $this->info("Trying to renew your {$key} key...");

        try {
            $apiKey = app()->make(ApiKey::class);

            if ($this->option('key') === 'secondary') {
                $apiKey->renewSecondary(config('signere.secondary_key'));
            } else {
                $apiKey->renewPrimary(config('signere.primary_key'));
            }

            $this->info("Your {$key} key was renewed!");
        } catch (Exception $e) {
            $this->error("Renewing failed because: {$e->getMessage()}.");

            return -1;
        }
    }
}
