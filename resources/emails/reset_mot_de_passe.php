<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0;padding:0;background-color:#f3f4f6;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 20px;">
        <tr>
            <td align="center">
                <table width="480" cellpadding="0" cellspacing="0" style="background-color:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="padding:32px 40px;background-color:#7830E0;text-align:center;">
                            <h1 style="color:#ffffff;font-size:24px;font-weight:700;margin:0;">Cado.me</h1>
                        </td>
                    </tr>
                    <!-- Content -->
                    <tr>
                        <td style="padding:40px;">
                            <h2 style="color:#1f2937;font-size:20px;font-weight:600;margin:0 0 16px;">Réinitialisation de mot de passe</h2>
                            <p style="color:#6b7280;font-size:15px;line-height:1.6;margin:0 0 16px;">
                                Vous avez demandé la réinitialisation de votre mot de passe. Cliquez sur le bouton ci-dessous pour créer un nouveau mot de passe.
                            </p>
                            <p style="color:#6b7280;font-size:15px;line-height:1.6;margin:0 0 16px;">
                                Ce lien expirera dans <strong>1 heure</strong>.
                            </p>
                            <p style="text-align:center;margin:32px 0;">
                                <a href="<?= $url_reset ?>" style="display:inline-block;background-color:#7830E0;color:#ffffff;font-weight:600;font-size:15px;padding:14px 32px;border-radius:12px;text-decoration:none;">
                                    Réinitialiser mon mot de passe
                                </a>
                            </p>
                            <p style="color:#9ca3af;font-size:13px;line-height:1.5;margin:0;">
                                Si vous n'avez pas demandé cette réinitialisation, vous pouvez ignorer cet email. Votre mot de passe actuel restera inchangé.
                            </p>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td style="padding:24px 40px;border-top:1px solid #f3f4f6;">
                            <p style="color:#9ca3af;font-size:12px;text-align:center;margin:0;">
                                © <?= date('Y') ?> Cado.me — Plateforme communautaire
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
