<?php

namespace App\Services;

use App\Models\Room;
use App\Models\QrCode as QrCodeModel;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Endroid\QrCode\QrCode as EndroidQrCode;
use Endroid\QrCode\Writer\PngWriter;

class QrCodeService
{
    public function generateQrCode(Room $room)
    {
        // 1. Prepare data
        $data = [
            'room_id' => $room->room_id,
            'room_name' => $room->room_name,
            'location' => $room->location,
            'token' => Str::random(32),
            'generated_at' => now()->toIso8601String(),
            'version' => '1'
        ];

        // 2. Convert to JSON
        $jsonData = json_encode($data);

        // 3. Encrypt
        $encryptedToken = Crypt::encryptString($jsonData);

        // 4. Build URL
        $scanUrl = route('scanqr') . '?token=' . urlencode($encryptedToken);

        // 5. Generate filename
        $filename = "qrcode_room_{$room->room_id}_" . time() . '.png';
        $filepath = storage_path("app/public/qrcodes/$filename");

        // 6. Create folder
        if (!file_exists(dirname($filepath))) {
            mkdir(dirname($filepath), 0755, true);
        }

        // 7. Generate QR Code - SIMPLIFIED
        try {
            $qrCode = new EndroidQrCode($scanUrl);
            $writer = new PngWriter();
            $result = $writer->write($qrCode);
            $result->saveToFile($filepath);
        } catch (\Exception $e) {
            throw new \Exception("Gagal generate QR Code: " . $e->getMessage());
        }

        // 8. Save to database
        QrCodeModel::updateOrCreate(
            ['room_id' => $room->room_id],
            [
                'encrypted_token' => $encryptedToken,
                'qr_image_path' => "qrcodes/$filename",
                'is_active' => true
            ]
        );

        return "qrcodes/$filename";
    }

    public function regenerateQrCode(Room $room)
    {
        $oldQrCode = QrCodeModel::where('room_id', $room->room_id)->first();
        
        if ($oldQrCode && file_exists(storage_path("app/public/{$oldQrCode->qr_image_path}"))) {
            unlink(storage_path("app/public/{$oldQrCode->qr_image_path}"));
        }

        return $this->generateQrCode($room);
    }

    public function deleteQrCode(Room $room)
    {
        $qrCode = QrCodeModel::where('room_id', $room->room_id)->first();
        
        if (!$qrCode) {
            return false;
        }

        $filepath = storage_path("app/public/{$qrCode->qr_image_path}");
        if (file_exists($filepath)) {
            unlink($filepath);
        }

        $qrCode->delete();
        return true;
    }

    public function getQrCodeUrl(Room $room)
    {
        $qrCode = QrCodeModel::where('room_id', $room->room_id)->first();
        
        if (!$qrCode) {
            return null;
        }

        return asset("storage/{$qrCode->qr_image_path}");
    }
}
