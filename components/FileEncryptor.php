<?php

namespace app\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * FileEncryptor - Encrypt/decrypt files at rest using AES-256-CBC.
 *
 * Usage:
 *   $encryptor = Yii::$app->fileEncryptor;
 *   $encryptor->encryptFile('/path/to/file.png');           // encrypts in-place, adds .enc
 *   $decrypted = $encryptor->decryptFileToString('/path/to/file.png.enc');
 *   $encryptor->decryptFileTo('/path/to/file.png.enc', '/tmp/file.png');
 *
 * Config (config/web.php & config/console.php):
 *   'fileEncryptor' => [
 *       'class' => 'app\components\FileEncryptor',
 *       'encryptionKey' => 'your-64-hex-char-key',
 *   ],
 */
class FileEncryptor extends Component
{
    /**
     * @var string Hex-encoded 256-bit key (64 hex chars).
     */
    public $encryptionKey;

    const CIPHER = 'aes-256-cbc';
    const ENC_EXTENSION = '.enc';

    public function init()
    {
        parent::init();
        if (empty($this->encryptionKey)) {
            throw new InvalidConfigException('FileEncryptor::encryptionKey must be set.');
        }
    }

    /**
     * Get binary key from hex string.
     */
    private function getKey()
    {
        return hex2bin($this->encryptionKey);
    }

    /**
     * Encrypt raw data string and return ciphertext (IV prepended).
     */
    public function encrypt($data)
    {
        $key = $this->getKey();
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::CIPHER));
        $encrypted = openssl_encrypt($data, self::CIPHER, $key, OPENSSL_RAW_DATA, $iv);
        // prepend IV so we can extract it on decrypt
        return $iv . $encrypted;
    }

    /**
     * Decrypt ciphertext (with prepended IV) and return plaintext.
     */
    public function decrypt($ciphertext)
    {
        $key = $this->getKey();
        $ivLength = openssl_cipher_iv_length(self::CIPHER);
        $iv = substr($ciphertext, 0, $ivLength);
        $encrypted = substr($ciphertext, $ivLength);
        return openssl_decrypt($encrypted, self::CIPHER, $key, OPENSSL_RAW_DATA, $iv);
    }

    /**
     * Encrypt a file in-place. Adds .enc extension.
     * Returns the new file path (with .enc).
     */
    public function encryptFile($filePath)
    {
        if (!file_exists($filePath)) {
            Yii::warning("FileEncryptor: file not found: {$filePath}", __METHOD__);
            return false;
        }

        $plaintext = file_get_contents($filePath);
        $ciphertext = $this->encrypt($plaintext);
        $encPath = $filePath . self::ENC_EXTENSION;

        if (file_put_contents($encPath, $ciphertext) === false) {
            Yii::error("FileEncryptor: failed to write encrypted file: {$encPath}", __METHOD__);
            return false;
        }

        // remove original plaintext file
        unlink($filePath);

        return $encPath;
    }

    /**
     * Decrypt file contents and return as string (binary safe).
     */
    public function decryptFileToString($encFilePath)
    {
        if (!file_exists($encFilePath)) {
            Yii::warning("FileEncryptor: encrypted file not found: {$encFilePath}", __METHOD__);
            return false;
        }

        $ciphertext = file_get_contents($encFilePath);
        return $this->decrypt($ciphertext);
    }

    /**
     * Decrypt file and write plaintext to a destination path.
     */
    public function decryptFileTo($encFilePath, $destPath)
    {
        $plaintext = $this->decryptFileToString($encFilePath);
        if ($plaintext === false) {
            return false;
        }
        return file_put_contents($destPath, $plaintext) !== false;
    }

    /**
     * Encrypt an uploaded file immediately after save.
     * Pass the plain file path; returns the .enc path and new filename.
     *
     * @param string $filePath full path to the saved plain file
     * @return array|false ['path' => encryptedFilePath, 'filename' => newFilename] or false
     */
    public function encryptUploadedFile($filePath)
    {
        $encPath = $this->encryptFile($filePath);
        if ($encPath === false) {
            return false;
        }
        return [
            'path' => $encPath,
            'filename' => basename($encPath),
        ];
    }

    /**
     * Check if a filename indicates an encrypted file.
     */
    public static function isEncryptedFile($filename)
    {
        return substr($filename, -4) === self::ENC_EXTENSION;
    }
}
