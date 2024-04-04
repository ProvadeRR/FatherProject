<?php

namespace App\Telegram\Executors;

use App\Services\InvoiceGenerationService;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Laravel\Facades\Telegram;

class CreateDocumentExecutor
{
    public function execute(int $chatId, string $text): void
    {
        $result = explode(' ', $text);
        $template = (string) $result[0];
        $number = (int)$result[1];
        $amount = (int)$result[2];
        $date = $result[3];

        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => 'Документ создан',
        ]);

        $paths = app(InvoiceGenerationService::class)->generate(
            $number,
            $amount,
            $date,
            $template
        );

        foreach ($paths as $path) {
            Telegram::sendDocument([
                'chat_id' => $chatId,
                'document' => InputFile::create(storage_path($path))
            ]);
        }
    }
}
