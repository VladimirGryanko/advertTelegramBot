<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * Class User
 * @package app\models\user
 * @property $id
 * @property $email
 * @property $username
 * @property $password
 * @property $authKey
 * @property $role
 * @property string $accessToken [varchar(255)]
 * @property string $Host [char(255)]
 * @property string $User [char(32)]
 * @property string $Select_priv [enum('N', 'Y')]
 * @property string $Insert_priv [enum('N', 'Y')]
 * @property string $Update_priv [enum('N', 'Y')]
 * @property string $Delete_priv [enum('N', 'Y')]
 * @property string $Create_priv [enum('N', 'Y')]
 * @property string $Drop_priv [enum('N', 'Y')]
 * @property string $Reload_priv [enum('N', 'Y')]
 * @property string $Shutdown_priv [enum('N', 'Y')]
 * @property string $Process_priv [enum('N', 'Y')]
 * @property string $File_priv [enum('N', 'Y')]
 * @property string $Grant_priv [enum('N', 'Y')]
 * @property string $References_priv [enum('N', 'Y')]
 * @property string $Index_priv [enum('N', 'Y')]
 * @property string $Alter_priv [enum('N', 'Y')]
 * @property string $Show_db_priv [enum('N', 'Y')]
 * @property string $Super_priv [enum('N', 'Y')]
 * @property string $Create_tmp_table_priv [enum('N', 'Y')]
 * @property string $Lock_tables_priv [enum('N', 'Y')]
 * @property string $Execute_priv [enum('N', 'Y')]
 * @property string $Repl_slave_priv [enum('N', 'Y')]
 * @property string $Repl_client_priv [enum('N', 'Y')]
 * @property string $Create_view_priv [enum('N', 'Y')]
 * @property string $Show_view_priv [enum('N', 'Y')]
 * @property string $Create_routine_priv [enum('N', 'Y')]
 * @property string $Alter_routine_priv [enum('N', 'Y')]
 * @property string $Create_user_priv [enum('N', 'Y')]
 * @property string $Event_priv [enum('N', 'Y')]
 * @property string $Trigger_priv [enum('N', 'Y')]
 * @property string $Create_tablespace_priv [enum('N', 'Y')]
 * @property string $ssl_type [enum('', 'ANY', 'X509', 'SPECIFIED')]
 * @property string $ssl_cipher [blob]
 * @property string $x509_issuer [blob]
 * @property string $x509_subject [blob]
 * @property string $max_questions [int unsigned]
 * @property string $max_updates [int unsigned]
 * @property string $max_connections [int unsigned]
 * @property string $max_user_connections [int unsigned]
 * @property string $plugin [char(64)]
 * @property string $authentication_string
 * @property string $password_expired [enum('N', 'Y')]
 * @property int $password_last_changed [timestamp]
 * @property string $password_lifetime [smallint unsigned]
 * @property string $account_locked [enum('N', 'Y')]
 * @property string $Create_role_priv [enum('N', 'Y')]
 * @property string $Drop_role_priv [enum('N', 'Y')]
 * @property string $Password_reuse_history [smallint unsigned]
 * @property string $Password_reuse_time [smallint unsigned]
 * @property string $Password_require_current [enum('N', 'Y')]
 * @property string $User_attributes [json]
 */
class User extends ActiveRecord implements IdentityInterface
{
    const ROLE_USER = 'user';
    const ROLE_MODER = 'moderator';
    const ROLE_ADMIN = 'admin';
    /**
     * @var mixed|null
     */


    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['username', 'email', 'password'], 'string'],
            [['username', 'email', 'password'], 'required'],
            [['email'], 'email'],
        ];
    }

    /**
     * @return array
     */
    public static function roles(): array
    {
        return [
            self::ROLE_USER => 'user',
            self::ROLE_ADMIN => 'admin',
            self::ROLE_MODER => 'moderator',
        ];
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        $role = Yii::$app->authManager->getRolesByUser($this->id);

        return array_key_exists('admin', $role);

    }

    /**
     * @return bool
     */
    public function isModerator(): bool
    {
        $role = Yii::$app->authManager->getRolesByUser($this->id);

        return array_key_exists('moderator', $role) || array_key_exists('admin', $role);
    }

    /**
     * @return bool
     */
    public function isUser(): bool
    {
        $role = Yii::$app->authManager->getRolesByUser($this->id);

        return array_key_exists('moderator', $role)
            || array_key_exists('admin', $role)
            || array_key_exists('user', $role);
    }

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'user';
    }

    /**
     * @param string $username
     * @param string $password
     * @return User|IdentityInterface|null
     */
    public static function findUser(string $username, string $password)
    {
        $user = static::find()
            ->select(['id'])
            ->where(['username' => $username, 'password' => (int)$password])
            ->asArray()
            ->one();

        return $user ? static::findIdentity($user['id']) : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @param string $authKey
     * @return bool
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }

    /**
     * @param int|string $id
     * @return User|IdentityInterface|null
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @param mixed $token
     * @param null $type
     * @return User|IdentityInterface|null
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['accessToken' => $token]);
    }
}
