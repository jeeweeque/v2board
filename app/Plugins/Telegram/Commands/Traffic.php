<?php

namespace App\Plugins\Telegram\Commands;

use App\Models\User;
use App\Plugins\Telegram\Telegram;
use App\Utils\Helper;

class Traffic extends Telegram {
    public $command = '/traffic';
    public $description = 'Информация по остатку трафика на счете';

    public function handle($message, $match = []) {
        $telegramService = $this->telegramService;
        if (!$message->is_private) return;
        $user = User::where('telegram_id', $message->chat_id)->first();
        if (!$user) {
            $telegramService->sendMessage($message->chat_id, 'Счет не найдет, пожалуйста привяжите аккаунт Телеграм командой /bind', 'markdown');
            return;
        }
        $transferEnable = Helper::trafficConvert($user->transfer_enable);
        $up = Helper::trafficConvert($user->u);
        $down = Helper::trafficConvert($user->d);
        $remaining = Helper::trafficConvert($user->transfer_enable - ($user->u + $user->d));
        $text = "🚥Остаток Трафика:\n———————————————\nОбщее количество трафика：`{$transferEnable}`\nЗагружено：`{$up}`\nСкачено：`{$down}`\nОстаток：`{$remaining}`";
        $telegramService->sendMessage($message->chat_id, $text, 'markdown');
    }
}
