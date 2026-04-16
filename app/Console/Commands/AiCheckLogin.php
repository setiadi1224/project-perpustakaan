<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class AiCheckLogin extends Command
{
    protected $signature = 'ai:check-login
        {jam_login=2}
        {ip_luar=1}
        {device_baru=1}';

    protected $description = 'Test AI deteksi login anomali';

    public function handle()
    {
        $jam_login = $this->argument('jam_login');
        $ip_luar = $this->argument('ip_luar');
        $device_baru = $this->argument('device_baru');

        $this->info("🚨 Mengirim data ke AI...");

        $response = Http::post('http://127.0.0.1:8001/cek', [
            'jam_login' => $jam_login,
            'ip_luar' => $ip_luar,
            'device_baru' => $device_baru
        ]);

        if ($response->failed()) {
            $this->error("❌ AI tidak bisa diakses");
            return;
        }

        $data = $response->json();

        $this->line("📊 HASIL AI:");
        $this->line("Status : " . $data['status']);
        $this->line("Risk   : " . $data['risk_score']);
        $this->line("Level  : " . $data['level']);
        $this->line("Msg    : " . $data['message']);

        if ($data['status'] == 'anomali') {
            $this->warn("⚠️ LOGIN MENCURIGAKAN TERDETEKSI!");
        } else {
            $this->info("✅ Login aman");
        }
    }
}