<?php

namespace App\Plugins\Telegram\Commands;

use App\Models\User;
use App\Plugins\Telegram\Telegram;

class Bind extends Telegram {
    public $command = '/bind';
    public $description = 'Привязать аккаунт Телеграм';

    public function handle($message, $match = []) {
        if (!$message->is_private) return;
        if (!isset($message->args[0])) {
            abort(500, '500, после /bind следует добавить ссылку на подписку');
        }
        $subscribeUrl = $message->args[0];
        $subscribeUrl = parse_url($subscribeUrl);
        parse_str($subscribeUrl['query'], $query);
        $token = $query['token'];
        if (!$token) {
            abort(500, 'Неправильный адрес подписки \ Проблема с подрузкой подписки');
        }
        $user = User::where('token', $token)->first();
        if (!$user) {
            abort(500, 'Пользователь не найден');
        }
        if ($user->telegram_id) {
            abort(500, 'Этот аккаунт уже используется');
        }
        $user->telegram_id = $message->chat_id;
        if (!$user->save()) {
            abort(500, 'Оошибка настройки');
        }
        $telegramService = $this->telegramService;
        $telegramService->sendMessage($message->chat_id, 'Привязка аккаунта прошла успешно');
    }
}
