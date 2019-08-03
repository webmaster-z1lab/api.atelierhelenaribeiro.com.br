<?php

namespace App\Auth\Console\Commands;

use Illuminate\Console\Command;
use phpseclib\Crypt\RSA;

class CreateAuthKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth:key-generate {--force : Overwrite keys they already exist} {--length=4096 : The length of the private key}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the encryption keys for API authentication';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(RSA $rsa)
    {
        if (file_exists(storage_path('public.key')) || file_exists(storage_path('private.key')) && !$this->option('force')) {
            $this->error('Encryption keys already exist. Use the --force option to overwrite them.');
        } else {
            $keys = $rsa->createKey($this->input ? (int) $this->option('length') : 4096);

            file_put_contents(storage_path('public.key'), \Arr::get($keys, 'publickey'));
            file_put_contents(storage_path('private.key'), \Arr::get($keys, 'privatekey'));

            $this->info('Encryption keys generated successfully.');
        }
    }
}
