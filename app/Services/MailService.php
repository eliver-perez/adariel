<?php

namespace App\Services;

use PHPMailer\PHPMailer\Exception as PHPMailerException;
use PHPMailer\PHPMailer\PHPMailer;
use RuntimeException;

class MailService
{
    public function send(array $data): array
    {
        $mail = new PHPMailer(true);

        try {
            $host = trim((string) env('MAIL_HOST'));
            $username = trim((string) env('MAIL_USERNAME'));
            $password = (string) env('MAIL_PASSWORD');
            $fromAddress = trim((string) env('MAIL_FROM_ADDRESS'));
            $fromName = trim((string) env('MAIL_FROM_NAME'));

            if (
                $host === '' ||
                $username === '' ||
                $password === '' ||
                $fromAddress === ''
            ) {
                throw new RuntimeException(
                    'La configuración del servidor de correo está incompleta.'
                );
            }

            $mail->CharSet = 'UTF-8';
            $mail->isSMTP();

            $mail->Host = $host;
            $mail->Port = (int) env('MAIL_PORT');
            $mail->SMTPAuth = true;
            $mail->Username = $username;
            $mail->Password = $password;
            $mail->Timeout = 20;

            $encryption = strtolower(
                trim((string) env('MAIL_ENCRYPTION'))
            );

            if ($encryption === 'tls') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            } elseif ($encryption === 'ssl') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } else {
                throw new RuntimeException(
                    'El método de cifrado del correo no es válido.'
                );
            }

            $mail->setFrom(
                $fromAddress,
                $data['fromName'] ?? $fromName
            );

            $replyToAddress = trim(
                (string) env('MAIL_REPLY_TO_ADDRESS')
            );

            if ($replyToAddress !== '') {
                $mail->addReplyTo(
                    $replyToAddress,
                    trim((string) env('MAIL_REPLY_TO_NAME'))
                );
            }

            $mail->addAddress(
                $data['to'],
                $data['toName'] ?? ''
            );

            /*
             * Imágenes incrustadas mediante CID.
             */
            foreach ($data['embeddedImages'] ?? [] as $image) {
                if (!empty($image['path'])) {
                    if (
                        !is_file($image['path']) ||
                        !is_readable($image['path'])
                    ) {
                        throw new RuntimeException(
                            'No fue posible leer una imagen del correo.'
                        );
                    }

                    $mail->addEmbeddedImage(
                        $image['path'],
                        $image['cid'],
                        $image['name'] ?? basename($image['path']),
                        PHPMailer::ENCODING_BASE64,
                        $image['mime'] ?? ''
                    );

                    continue;
                }

                if (array_key_exists('content', $image)) {
                    $mail->addStringEmbeddedImage(
                        $image['content'],
                        $image['cid'],
                        $image['name'] ?? 'image.png',
                        PHPMailer::ENCODING_BASE64,
                        $image['mime'] ?? 'image/png'
                    );
                }
            }

            $mail->isHTML(true);
            $mail->Subject = $data['subject'];
            $mail->Body = $data['html'];
            $mail->AltBody = $data['text'] ?? '';

            if (!$mail->send()) {
                throw new RuntimeException(
                    'El servidor de correo rechazó el mensaje.'
                );
            }

            return [
                'accepted'  => true,
                'messageId' => $mail->getLastMessageID(),
            ];
        } catch (PHPMailerException $e) {
            error_log(
                'Error PHPMailer: '.$e->getMessage().
                ' | '.$mail->ErrorInfo
            );

            throw new RuntimeException(
                'No fue posible enviar el correo electrónico.'
            );
        }
    }
}