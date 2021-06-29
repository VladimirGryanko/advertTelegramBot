<?php


namespace app\controllers;


use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\filters\AccessControl;
use function SebastianBergmann\Type\TestFixture\callback_function;

class TelegramController extends \yii\rest\Controller
{
    const BOT_COMMANDS = [
        '/start' => 'commandStart',
    ];

    /**
     * @param string $token
     * @return false|mixed
     * @throws InvalidConfigException
     */
    public function actionIndex(string $token)
    {
        /** @var Component $telegram */
        $telegram = \Yii::$app->telegram;

        if ($token !== $telegram->token) {
            throw new InvalidConfigException('Telegram bot token is undefined');
        }

        $updates = $telegram->getUpdates()['result'];

        $update = $updates[array_key_last($updates)];
        $message = $update['message']['text'];
        $chatId = $update['message']['chat']['id'];

        if (array_key_exists($message, self::BOT_COMMANDS)) {
            return call_user_func([$this, self::BOT_COMMANDS[$message]], $chatId, $telegram, 'Das');
        }

        return $telegram->sendMessage($chatId, 'Такой команды не существует');
    }

    /**
     * @param int $chatId
     * @param Component $telegram
     * @param string $advertGroup
     * @return array
     */
    public static function commandStart(int $chatId, Component $telegram, string $advertGroup): array
    {
        return $telegram->sendMessage($chatId, 'Подпишитесь на группу' . $advertGroup .
            'Чтобы продолжить пользоваться ботом');
    }


}