<?php

namespace App\Telegram\Executors;

use Telegram\Bot\Laravel\Facades\Telegram;

class StartCreateDocumentExecutor
{
    public function execute(int $chatId): void
    {
        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => 'Чтобы создать документ, напишите - велес 120 3100 21.01.2024',
        ]);
    }
}
