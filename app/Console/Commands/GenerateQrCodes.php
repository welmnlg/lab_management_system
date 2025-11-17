<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Room;
use App\Services\QrCodeService;

class GenerateQrCodes extends Command
{
    /**
     * Signature command (nama command yang akan dijalankan)
     */
    protected $signature = 'qr:generate 
                            {--room= : Generate QR untuk room_id tertentu saja}
                            {--all : Generate QR untuk semua ruangan}';

    /**
     * Deskripsi command
     */
    protected $description = 'Generate QR Codes untuk ruangan lab';

    /**
     * QR Code Service
     */
    protected $qrCodeService;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->qrCodeService = app(\App\Services\QrCodeService::class);
    }

    /**
     * Execute the console command
     */
    public function handle()
    {
        $this->info('🚀 Memulai generate QR Codes...');
        $this->newLine();

        // Option 1: Generate untuk room tertentu
        if ($roomId = $this->option('room')) {
            return $this->generateForRoom($roomId);
        }

        // Option 2: Generate untuk semua ruangan
        if ($this->option('all')) {
            return $this->generateForAllRooms();
        }

        // Jika tidak ada option, tanyakan interaktif
        return $this->interactiveGenerate();
    }

    /**
     * Generate QR untuk satu ruangan
     */
    protected function generateForRoom($roomId)
    {
        $room = Room::find($roomId);

        if (!$room) {
            $this->error("❌ Ruangan dengan ID {$roomId} tidak ditemukan!");
            return 1;
        }

        $this->info("📍 Generating QR Code untuk: {$room->room_name}");

        try {
            $filePath = $this->qrCodeService->generateQrCode($room);
            $fullPath = storage_path("app/public/{$filePath}");

            $this->info("✅ QR Code berhasil di-generate!");
            $this->line("   📁 File: {$fullPath}");
            $this->line("   🌐 URL: " . asset("storage/{$filePath}"));
            
            return 0;
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * Generate QR untuk semua ruangan
     */
    protected function generateForAllRooms()
    {
        $rooms = Room::all();

        if ($rooms->isEmpty()) {
            $this->error('❌ Tidak ada ruangan di database!');
            return 1;
        }

        $this->info("📦 Ditemukan {$rooms->count()} ruangan");
        $this->newLine();

        $bar = $this->output->createProgressBar($rooms->count());
        $bar->start();

        $success = 0;
        $failed = 0;

        foreach ($rooms as $room) {
            try {
                $this->qrCodeService->generateQrCode($room);
                $success++;
            } catch (\Exception $e) {
                $failed++;
                $this->newLine();
                $this->error("❌ Gagal generate untuk {$room->room_name}: " . $e->getMessage());
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("✅ Selesai!");
        $this->line("   Berhasil: {$success}");
        if ($failed > 0) {
            $this->line("   Gagal: {$failed}");
        }
        $this->newLine();
        $this->line("📁 Lokasi file: storage/app/public/qrcodes/");
        $this->line("🌐 Akses via URL: http://localhost:8000/storage/qrcodes/");

        return 0;
    }

    /**
     * Mode interaktif
     */
    protected function interactiveGenerate()
    {
        $this->info('🎯 Pilih mode generate:');
        $this->newLine();

        $choice = $this->choice(
            'Apa yang ingin Anda lakukan?',
            [
                'all' => 'Generate QR untuk SEMUA ruangan',
                'one' => 'Generate QR untuk ruangan tertentu',
                'list' => 'Lihat daftar ruangan yang sudah ada QR',
            ],
            'all'
        );

        switch ($choice) {
            case 'Generate QR untuk SEMUA ruangan':
                return $this->generateForAllRooms();

            case 'Generate QR untuk ruangan tertentu':
                $rooms = Room::all();
                if ($rooms->isEmpty()) {
                    $this->error('❌ Tidak ada ruangan di database!');
                    return 1;
                }

                $options = $rooms->mapWithKeys(function ($room) {
                    return [$room->room_id => "{$room->room_name} ({$room->location})"];
                })->toArray();

                $roomId = $this->choice('Pilih ruangan:', $options);
                return $this->generateForRoom($roomId);

            case 'Lihat daftar ruangan yang sudah ada QR':
                return $this->listQrCodes();
        }

        return 0;
    }

    /**
     * Lihat daftar QR yang sudah di-generate
     */
    protected function listQrCodes()
    {
        $rooms = Room::with('qrCode')->get();

        $this->table(
            ['ID', 'Room Name', 'Location', 'QR Generated', 'File Path'],
            $rooms->map(function ($room) {
                return [
                    $room->room_id,
                    $room->room_name,
                    $room->location,
                    $room->qrCode ? '✅ Yes' : '❌ No',
                    $room->qrCode ? $room->qrCode->qr_image_path : '-'
                ];
            })
        );

        return 0;
    }
}
