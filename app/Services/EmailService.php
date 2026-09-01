<?php

namespace App\Services;

use App\Core\Config;

class EmailService
{
    public function envoyer(string $destinataire, string $sujet, string $contenuHtml): bool
    {
        $mailHost = Config::get('mail.host', '127.0.0.1');
        $mailPort = Config::get('mail.port', 1025);

        // En développement local, on log l'email
        if (Config::get('app.env') === 'local') {
            return $this->logEmail($destinataire, $sujet, $contenuHtml);
        }

        // Production : utiliser Symfony Mailer
        try {
            $transport = \Symfony\Component\Mailer\Transport\Smtp\StreamFilter\SmtpTransport::fromDsn("smtp://{$mailHost}:{$mailPort}");
            $mailer = new \Symfony\Component\Mailer\Mailer($transport);

            $email = (new \Symfony\Component\Mime\Email())
                ->from(Config::get('mail.from', 'noreply@cado.me'))
                ->to($destinataire)
                ->subject($sujet)
                ->html($contenuHtml);

            $mailer->send($email);
            return true;
        } catch (\Exception $e) {
            error_log("Erreur envoi email: " . $e->getMessage());
            return false;
        }
    }

    public function envoyerInvitation(string $destinataire, string $nomCommunaute, string $slug, string $token): bool
    {
        $url = Config::get('app.url') . "/invitation/{$token}";
        $contenuHtml = $this->chargerTemplate('invitation', [
            'nom_communaute' => $nomCommunaute,
            'url_invitation' => $url,
        ]);

        return $this->envoyer($destinataire, "Vous êtes invité à rejoindre {$nomCommunaute}", $contenuHtml);
    }

    public function envoyerBienvenue(string $destinataire, string $prenom): bool
    {
        $url = Config::get('app.url', 'http://localhost');
        $contenuHtml = $this->chargerTemplate('bienvenue', [
            'prenom' => $prenom,
            'url' => $url . '/app',
        ]);
        return $this->envoyer($destinataire, 'Bienvenue sur Cado.me !', $contenuHtml);
    }

    public function envoyerVerificationEmail(string $destinataire, string $token): bool
    {
        $url = Config::get('app.url', 'http://localhost') . "/verifier-email/{$token}";
        $contenuHtml = $this->chargerTemplate('verification_email', [
            'url_verification' => $url,
        ]);
        return $this->envoyer($destinataire, 'Vérifiez votre adresse email - Cado.me', $contenuHtml);
    }

    public function envoyerResetMotDePasse(string $destinataire, string $token): bool
    {
        $url = Config::get('app.url', 'http://localhost') . "/reinitialiser-mot-de-passe/{$token}";
        $contenuHtml = $this->chargerTemplate('reset_mot_de_passe', [
            'url_reset' => $url,
        ]);
        return $this->envoyer($destinataire, 'Réinitialisation de mot de passe - Cado.me', $contenuHtml);
    }

    public function envoyerNotificationCommunaute(string $destinataire, string $titre, string $message, ?string $urlAction = null): bool
    {
        $contenuHtml = $this->chargerTemplate('notification', [
            'titre' => $titre,
            'message' => $message,
            'url_action' => $urlAction,
        ]);
        return $this->envoyer($destinataire, $titre . ' - Cado.me', $contenuHtml);
    }

    private function logEmail(string $destinataire, string $sujet, string $contenuHtml): bool
    {
        $logDir = __DIR__ . '/../../storage/logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }

        $logFile = $logDir . '/mail_' . date('Y-m-d') . '.log';
        $entry = "\n--- Email ---\n";
        $entry .= "Date: " . date('Y-m-d H:i:s') . "\n";
        $entry .= "À: {$destinataire}\n";
        $entry .= "Sujet: {$sujet}\n";
        $entry .= "Contenu:\n{$contenuHtml}\n";
        $entry .= "--- Fin ---\n";

        return file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX) !== false;
    }

    private function chargerTemplate(string $template, array $data): string
    {
        extract($data);
        $templatePath = __DIR__ . '/../../resources/emails/' . $template . '.php';

        if (file_exists($templatePath)) {
            ob_start();
            require $templatePath;
            return ob_get_clean();
        }

        return "<p>{$template}</p>";
    }
}
