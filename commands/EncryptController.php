<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use app\models\Person;
use app\components\FileEncryptor;

/**
 * Manage file encryption for signature images and uploaded files.
 */
class EncryptController extends Controller
{
    /**
     * Encrypt all existing unencrypted signature files in person-signature directory.
     * Safe to run multiple times — skips already-encrypted files.
     *
     * Usage: php yii encrypt/signatures
     */
    public function actionSignatures()
    {
        $encryptor = Yii::$app->fileEncryptor;
        $basePath = Yii::getAlias('@app/web/uploads/person-signature');

        if (!is_dir($basePath)) {
            $this->stdout("Directory not found: {$basePath}\n");
            return ExitCode::OK;
        }

        // Find all Person records that have signature filenames
        $persons = Person::find()
            ->andWhere(['!=', 'signature', ''])
            ->orWhere(['!=', 'signature_thai', ''])
            ->all();

        $encrypted = 0;
        $skipped = 0;
        $errors = 0;

        foreach ($persons as $person) {
            // Encrypt English signature
            if (!empty($person->signature)) {
                $result = $this->encryptPersonSignature($person, 'signature', $basePath, $encryptor);
                if ($result === 'encrypted') $encrypted++;
                elseif ($result === 'skipped') $skipped++;
                else $errors++;
            }

            // Encrypt Thai signature
            if (!empty($person->signature_thai)) {
                $result = $this->encryptPersonSignature($person, 'signature_thai', $basePath, $encryptor);
                if ($result === 'encrypted') $encrypted++;
                elseif ($result === 'skipped') $skipped++;
                else $errors++;
            }
        }

        $this->stdout("\nDone! Encrypted: {$encrypted}, Skipped (already encrypted): {$skipped}, Errors: {$errors}\n");
        return ExitCode::OK;
    }

    /**
     * Decrypt all encrypted signature files back to plaintext.
     * Use this for rollback if needed.
     *
     * Usage: php yii encrypt/decrypt-signatures
     */
    public function actionDecryptSignatures()
    {
        $encryptor = Yii::$app->fileEncryptor;
        $basePath = Yii::getAlias('@app/web/uploads/person-signature');

        $persons = Person::find()
            ->andWhere(['!=', 'signature', ''])
            ->orWhere(['!=', 'signature_thai', ''])
            ->all();

        $decrypted = 0;
        $skipped = 0;
        $errors = 0;

        foreach ($persons as $person) {
            if (!empty($person->signature)) {
                $result = $this->decryptPersonSignature($person, 'signature', $basePath, $encryptor);
                if ($result === 'decrypted') $decrypted++;
                elseif ($result === 'skipped') $skipped++;
                else $errors++;
            }

            if (!empty($person->signature_thai)) {
                $result = $this->decryptPersonSignature($person, 'signature_thai', $basePath, $encryptor);
                if ($result === 'decrypted') $decrypted++;
                elseif ($result === 'skipped') $skipped++;
                else $errors++;
            }
        }

        $this->stdout("\nDone! Decrypted: {$decrypted}, Skipped (not encrypted): {$skipped}, Errors: {$errors}\n");
        return ExitCode::OK;
    }

    /**
     * Encrypt uploaded files for a specific directory.
     * Useful for encrypting submission-document files or other uploads.
     *
     * Usage: php yii encrypt/directory uploads/submission-document
     *
     * @param string $relativePath relative path from @app/web/
     */
    public function actionDirectory($relativePath)
    {
        $encryptor = Yii::$app->fileEncryptor;
        $basePath = Yii::getAlias("@app/web/{$relativePath}");

        if (!is_dir($basePath)) {
            $this->stderr("Directory not found: {$basePath}\n");
            return ExitCode::UNSPECIFIED_ERROR;
        }

        $files = $this->findFilesRecursive($basePath);
        $encrypted = 0;
        $skipped = 0;
        $errors = 0;

        foreach ($files as $file) {
            if (FileEncryptor::isEncryptedFile($file)) {
                $skipped++;
                continue;
            }

            $result = $encryptor->encryptFile($file);
            if ($result !== false) {
                $this->stdout("  Encrypted: {$file}\n");
                $encrypted++;
            } else {
                $this->stderr("  Error: {$file}\n");
                $errors++;
            }
        }

        $this->stdout("\nDone! Encrypted: {$encrypted}, Skipped: {$skipped}, Errors: {$errors}\n");
        return ExitCode::OK;
    }

    private function encryptPersonSignature($person, $attribute, $basePath, $encryptor)
    {
        $filename = $person->$attribute;
        $filePath = $basePath . '/' . $filename;

        // Already encrypted
        if (FileEncryptor::isEncryptedFile($filename)) {
            $this->stdout("  Skip (already encrypted): Person #{$person->id} {$attribute}\n");
            return 'skipped';
        }

        if (!file_exists($filePath)) {
            $this->stderr("  File not found: Person #{$person->id} {$attribute} -> {$filePath}\n");
            return 'error';
        }

        $encPath = $encryptor->encryptFile($filePath);
        if ($encPath === false) {
            $this->stderr("  Encryption failed: Person #{$person->id} {$attribute}\n");
            return 'error';
        }

        // Update DB with new filename (with .enc)
        $person->$attribute = basename($encPath);
        $person->save(false);
        $this->stdout("  Encrypted: Person #{$person->id} {$attribute} -> {$person->$attribute}\n");
        return 'encrypted';
    }

    private function decryptPersonSignature($person, $attribute, $basePath, $encryptor)
    {
        $filename = $person->$attribute;
        $filePath = $basePath . '/' . $filename;

        // Not encrypted
        if (!FileEncryptor::isEncryptedFile($filename)) {
            $this->stdout("  Skip (not encrypted): Person #{$person->id} {$attribute}\n");
            return 'skipped';
        }

        if (!file_exists($filePath)) {
            $this->stderr("  File not found: Person #{$person->id} {$attribute} -> {$filePath}\n");
            return 'error';
        }

        $plainPath = preg_replace('/\.enc$/', '', $filePath);
        $result = $encryptor->decryptFileTo($filePath, $plainPath);
        if (!$result) {
            $this->stderr("  Decryption failed: Person #{$person->id} {$attribute}\n");
            return 'error';
        }

        unlink($filePath);
        $person->$attribute = basename($plainPath);
        $person->save(false);
        $this->stdout("  Decrypted: Person #{$person->id} {$attribute} -> {$person->$attribute}\n");
        return 'decrypted';
    }

    private function findFilesRecursive($dir)
    {
        $files = [];
        $items = scandir($dir);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;
            $path = $dir . '/' . $item;
            if (is_dir($path)) {
                $files = array_merge($files, $this->findFilesRecursive($path));
            } else {
                $files[] = $path;
            }
        }
        return $files;
    }
}
