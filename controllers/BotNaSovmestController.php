<?php


namespace app\controllers;


use app\components\TelegramComponent;
use app\models\User;
use Cassandra\Time;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\redis\Connection;
use yii\web\ForbiddenHttpException;

class BotNaSovmestController extends \yii\rest\Controller
{

    /**
     * @return array[]
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [],
                'rules' => [
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['?']
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index' => ['post', 'get'],
                ],
            ],
        ];
    }

    /* @var string[] */
    private const COMMANDS = [
        '/start' => 'commandStart',
        '/logout' => 'commandLogout',
        '/balance' => 'commandBalance',
        '/help' => 'commandHelp',
    ];

    /** @var string[] */
    private const ADMIN_COMMANDS = [
        '/scanner' => 'commandScanner',
    ];

    /* @var string */
    private const REDIS_KEY_TEMPLATE = 'botNaSovmest:%d:%s';
    /* @var int */
    private const REDIS_KEY_LIFETIME_DEFAULT = 86400;
    /* @var int */
    private const REDIS_KEY_LIFETIME_BRUTEFORCE = 15 * 60;
    /* @var string */
    private const STORE_USERNAME = 'username';
    private const STORE_BRUTEFORCE = 'bruteforceMaxAttempts';
    private const STORE_MESSAGE_ID = 'message_id';
    private const STORE_UPDATE_ID = 'offset';
    private const GLOBAL_CHAT_ID = 0;

    /* @var bool */
    public $layout = false;
    /* @var TelegramComponent */
    private $component;
    /* @var int */
    private $chatId;
    /* @var User */
    private $user;

    /**
     * {@inheritDoc}
     */
    public function init()
    {
        $this->component = \Yii::$app->telegram;
    }

    /**
     * @param string $token
     * @return string|void
     * @throws ForbiddenHttpException
     * @throws InvalidConfigException
     */
    public function actionIndex(string $token)
    {
        if ($token !== $this->component->token) {
            throw new ForbiddenHttpException('Доступ запрещен');
        }

        $params = \Yii::$app->request->getBodyParams();
        $updateId = $params['update_id'];
        $lastUpdateId = $this->storeGet(self::GLOBAL_CHAT_ID, self::STORE_UPDATE_ID);

        if ((int)$lastUpdateId === (int)$updateId) {
            return;
        }

        $this->storeSet(self::GLOBAL_CHAT_ID, self::STORE_UPDATE_ID, $updateId);

        $this->chatId = $params['message']['chat']['id'];
        $this->user = User::findOne(['chat_id' => $this->chatId]);
        $messageText = $params['message']['text'];
        $messageId = $params['message']['message_id'];

        if (array_key_exists($messageText, self::COMMANDS)) {
            call_user_func([$this, self::COMMANDS[$messageText]]);
        } elseif (
            array_key_exists($messageText, self::ADMIN_COMMANDS)
            && \Yii::$app->getAuthManager()->checkAccess($this->user->getId(), User::ROLE_ADMIN)
        ) {
            call_user_func([$this, self::ADMIN_COMMANDS[$messageText]]);
        } else {
            $this->processMessage($messageText, $messageId);
        }

        return '';
    }

    /**
     * Метод ответа бота на команду /start
     */
    private function commandStart(): void
    {
        if ($this->user !== null) {
            $this->send('already_logged');
            return;
        }
        $this->storeSet($this->chatId, self::STORE_USERNAME, null);
        $this->send('welcome');
        $messageId = $this->send('auth', ['state' => 'login'])['result']['message_id'];
        $this->storeSet($this->chatId, self::STORE_MESSAGE_ID, $messageId);
    }

    /**
     * Метод отправкии сообщений
     * @param string $view
     * @param array $context
     * @return array
     * @throws \yii\httpclient\Exception
     */
    private function send(string $view, array $context = []): array
    {
        $message = $this->render(sprintf('//telegram/%s', $view), $context);
        return $this->component->sendMessage($this->chatId, $message);
    }

    /**
     * Метод удаления сообщений
     * @param int $chatId
     * @param int $messageId
     * @throws \yii\httpclient\Exception
     */
    private function delete(int $chatId, int $messageId): void
    {
        $this->component->deleteMessage($chatId, $messageId);
    }

    /**
     * Метод редактирования сообщения
     * @param string $view
     * @param array $context
     */
    private function edit(string $view, array $context = []): void
    {
        $messageId = $this->storeGet($this->chatId, self::STORE_MESSAGE_ID);
        $message = $this->render(sprintf('//hook/telegram/%s', $view), $context);
        $this->component->editMessageText($this->chatId, $messageId, $message);
    }

    /**
     * @param int $chatId
     * @param string $name
     * @param string|null $value
     * @param int $lifetime
     */
    private function storeSet(int $chatId, string $name, ?string $value, int $lifetime = self::REDIS_KEY_LIFETIME_DEFAULT): void
    {
        $redis = $this->getRedis();
        $key = sprintf(self::REDIS_KEY_TEMPLATE, $chatId, $name);

        if ($value === null && $redis->exists($key)) {
            $redis->del($key);
        } elseif ($value !== null) {
            $redis->set($key, $value);
            $redis->expire($key, $lifetime);
        }
    }

    /**
     * @param int $chatId
     * @param string $name
     * @return string|null
     */
    private function storeGet(int $chatId, string $name): ?string
    {
        $redis = $this->getRedis();
        $key = sprintf(self::REDIS_KEY_TEMPLATE, $chatId, $name);

        return $redis->get($key);
    }

    /**
     * @return Connection
     */
    private function getRedis(): Connection
    {
        return \Yii::$app->redis;
    }
}