<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
  protected $signature = 'clean:storage';

    /**
     * The console command description.
     *
     * @var string
     */
   protected $description = 'Membersihkan file bukti yang tidak terpakai di storage';

    /**
     * Execute the console command.
     */
  public function handle()
{
    $files = Storage::disk('public')->files('bukti');

    foreach ($files as $file) {

        $exists = \App\Models\Peminjaman::where('bukti_pembayaran', $file)->exists();

        if (!$exists) {
            Storage::disk('public')->delete($file);
            $this->info("Deleted: $file");
        }
    }

    $this->info('Selesai bersihin storage');
}
}
