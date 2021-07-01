<?php


namespace app\components;

use \yii\base\Component;
use yii\base\InvalidConfigException;
use yii\httpclient\Client;
use \yii\httpclient\Response;


class TelegramComponent extends Component
{
    /** @var string */
    public $token;
    /** @var Client */
    private $client;

    /** URL для создания запроса боту */
    private const BASE_URL = 'https://api.telegram.org/bot%s';

    /** @var string */
    private const METHOD_GET_UPDATES = 'getUpdates';
    private const METHOD_SEND_MESSAGE = 'sendMessage';
    private const METHOD_SET_WEBHOOK = 'setWebhook';
    private const METHOD_DELETE_WEBHOOK = 'deleteWebhook';
    private const METHOD_DELETE_MESSAGE = 'deleteMessage';
    private const METHOD_EDIT_MESSAGE = 'editMessageText';

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        if ($this->token === null) {
            throw new InvalidConfigException('Telegram bot token is undefined.');
        }
        $this->client = new Client([
            'baseUrl' => sprintf(self::BASE_URL, $this->token),
        ]);
    }

    /**
     * Метод телеграмма для отправки сообщения пользователю
     * @param int $chatId
     * @param string $message
     * @return array
     * @throws \yii\httpclient\Exception
     */
    public function sendMessage(int $chatId, string $message): array
    {
        $params = [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'html',
        ];
        $response = $this->client
            ->get(self::METHOD_SEND_MESSAGE, $params)
            ->send();

        $this->processResponse($response);
        return $response->getData();
    }

    /**
     * Метод изменения собщения
     * @param int $chatId
     * @param int $messageId
     * @param string $text
     * @return void
     */
    public function editMessageText(int $chatId, int $messageId, string $text): void
    {
        $response = $this->client
            ->get(self::METHOD_EDIT_MESSAGE, [
                'chat_id' => $chatId,
                'message_id' => $messageId,
                'text' => $text,
                'parse_mode' => 'html'
            ])
            ->send();

        $this->processResponse($response);
    }

    /**
     * Метод удаления сообщения из чата
     * @param int $chatId
     * @param int $messageId
     * @return void
     * @throws \yii\httpclient\Exception
     */
    public function deleteMessage(int $chatId, int $messageId): void
    {
        $response = $this->client
            ->get(self::METHOD_DELETE_MESSAGE, ['chat_id' => $chatId, 'message_id' => $messageId])
            ->send();

        $this->processResponse($response);
    }

    /**
     * Получение обновлений используя телеграмм метод getUpdates()
     * @return null | array
     */
    public function getUpdates(): ?array
    {
        $response = $this->client
            ->get(self::METHOD_GET_UPDATES)
            ->send();
        return $response->getData();
    }

    /**
     * Метод установки вубхуков
     * @param string $url
     * @param string $certificate
     * @return void
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function setWebhook($url, $certificate): void
    {
        $response = $this->client
            ->post(self::METHOD_SET_WEBHOOK, ['url' => $url])
            ->send();
        $this->processResponse($response);
    }

    /**
     * Метод удаления вебхуков
     * @return void
     */
    public function deleteWebhook(): void
    {
        $response = $this->client
            ->post(self::METHOD_DELETE_WEBHOOK)
            ->send();
        $this->processResponse($response);
    }

    /**
     * @param Response $response
     */
    private function processResponse(Response $response): void
    {
        $data = $response->getData();

        if (!$data['ok']) {
            throw new \RuntimeException($data['description'], $data['error_code']);
        }
    }
}