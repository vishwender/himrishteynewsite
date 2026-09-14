<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HashExistingPasswords extends Command
{
    protected $signature = 'members:hash-passwords
        {--connection= : Database connection to process (defaults to the configured connection)}
        {--dry-run : Count plaintext passwords without changing them}';

    protected $description = 'Hash existing plain text member passwords';

    public function handle(): int
    {
        $connection = DB::connection($this->option('connection') ?: null);
        $dryRun = (bool) $this->option('dry-run');
        $processed = 0;
        $skipped = 0;

        $this->info('Connection: '.$connection->getName());

        $connection->table('members')
            ->select('id', 'password')
            ->whereNotNull('password')
            ->where('password', '!=', '')
            ->chunkById(500, function ($members) use ($connection, $dryRun, &$processed, &$skipped) {
                foreach ($members as $member) {
                    if ($this->isAlreadyHashed($member->password)) {
                        $skipped++;
                        continue;
                    }

                    if ($dryRun) {
                        $processed++;
                        continue;
                    }

                    // Do not overwrite a password reset since this batch was read.
                    $processed += $connection->table('members')
                        ->where('id', $member->id)
                        ->where('password', $member->password)
                        ->update(['password' => Hash::make($member->password)]);
                }
            });

        $this->info($dryRun
            ? "Would hash {$processed} passwords; skipped {$skipped} existing hashes."
            : "Hashed {$processed} passwords; skipped {$skipped} existing hashes.");

        return self::SUCCESS;
    }

    private function isAlreadyHashed(string $password): bool
    {
        // PHP recognizes current bcrypt/Argon hashes. Also preserve complete
        // bcrypt hashes from implementations using the 2a or 2b identifiers.
        return Hash::isHashed($password)
            || preg_match('/^\\$2[aby]\\$[0-9]{2}\\$[.\\/A-Za-z0-9]{53}$/D', $password) === 1;
    }
}
