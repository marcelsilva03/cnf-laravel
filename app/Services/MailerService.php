<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class MailerService
{
    protected PHPMailer $mail;
    protected bool $debug;
    protected array $headers = [];

    public function __construct(bool $debug = false)
    {
        $this->debug = $debug;
        $this->mail = new PHPMailer($debug);

        $this->mail->CharSet  = 'UTF-8';
        $this->mail->Encoding = 'base64';
        $this->mail->isSMTP();
        $this->mail->Host = env('MAIL_HOST');
        $this->mail->SMTPAuth = true;
        $this->mail->Username = env('MAIL_USERNAME');
        $this->mail->Password = env('MAIL_PASSWORD');
        $this->mail->SMTPSecure = env('MAIL_ENCRYPTION', 'tls');
        $this->mail->Port = env('MAIL_PORT');
    }

    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    /**
     * @throws Exception
     */
    public function prepare(string $subject, string $body): void
    {
        $this->mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
        $this->mail->isHTML(true);
        $this->mail->Subject = $subject;
        $this->mail->Body = $body;
    }

    /**
     * @throws Exception
     */
    public function addCarbonCopies(array $cc = [], array $bcc = []): void
    {
        if (count($cc) > 0) {
            foreach ($cc as $address) {
                $this->mail->addCC($address);
            }
        }
        if (count($bcc) > 0) {
            foreach ($bcc as $address) {
                $this->mail->addBCC($address);
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function sendMail(array $to): bool
    {
        try {
            foreach ($to as $address) {
                $this->mail->addAddress($address);
            }
            foreach ($this->headers as $name => $value) {
                $this->mail->addCustomHeader($name, $value);
            }
            return $this->mail->send();
        } catch (Exception $e) {
            if ($this->debug) {
                throw new \Exception("Mailer Error: {$e->getMessage()}");
            }
            return false;
        }
    }
}
