<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram;

class BotTelegramController extends Controller
{
    public function setWebhook()
    {
        $response = Telegram::setWebhook([
            'url' => env('TELEGRAM_WEBHOOK_URL')
        ]);
        dd($response);
        // dd('ok');
    }

    public function commandHandlerWebHook()
    {
        $updates = Telegram::commandsHandler(true);
        $chat_id = $updates->getChat()->getId();
        $username = $updates->getChat()->getFirstName();

        switch (strtolower($updates->getMessage()->getText())) {
            case 'me':
                return Telegram::sendMessage([
                    'chat_id' => $chat_id,
                    'text' => 'Halo '.$username.', Your User ID '.$chat_id.' Please update the profile menu'
                ]);
                break;
            case 'test':
                return Telegram::sendMessage([
                    'chat_id' => $chat_id,
                    'text' => 'Halo '.$username.' '.$chat_id
                ]);
                break;
            case 'baru':
                return Telegram::sendMessage([
                    'chat_id' => $chat_id,
                    'text' => 'Baru Masuk '.$username.' '.$chat_id
                ]);
                break;

            default:
                # code...
                break;
        }

        // if(strtolower($updates->getMessage()->getText() === 'test'))
        // {
        //     return Telegram::sendMessage([
        //         'chat_id' => $chat_id,
        //         'text' => 'Halo '.$username
        //     ]);
        // }
    }

    public function invoiceNotif()
    {

    }
}
