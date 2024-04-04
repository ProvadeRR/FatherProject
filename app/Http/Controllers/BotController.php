<?php

namespace App\Http\Controllers;

use App\Telegram\Executors\CreateDocumentExecutor;
use App\Telegram\Executors\StartCreateDocumentExecutor;
use Illuminate\Support\Str;
use Telegram\Bot\Api;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Update;

class BotController extends Controller
{
    protected Api $telegram;

    /**
     * Create a new controller instance.
     *
     * @param Api $telegram
     */
    public function __construct(Api $telegram)
    {
        $this->telegram = $telegram;
    }


    public function webhook(): string
    {
        Telegram::commandsHandler(true);
        /** @var Update $update */
        $update = Telegram::getWebhookUpdate();
        $text = $update->getMessage()->get('text');

        $chatId = $update->getChat()->get('id');

        if (Str::startsWith(Str::lower($text), ['велес', 'инком', 'индустриал'])) {
            (new CreateDocumentExecutor())->execute($chatId, $text);
        } else {
            (new StartCreateDocumentExecutor())->execute($chatId);
        }

        return 'ok';
    }
}
