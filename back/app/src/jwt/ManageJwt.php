<?php

namespace App\src\jwt;

require_once('../vendor/autoload.php');

use DateTimeImmutable;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Dotenv\Dotenv as Dotenv;


class ManageJwt
{
    /**
     * Crée un JWT avec les données spécifiées.
     *
     * @param string $email      L'adresse e-mail de l'utilisateur.
     * @param int    $superAdmin Indique si l'utilisateur est un super administrateur.
     *
     * @return string Le JWT généré.
     */
    public static function create(string $email, int $superAdmin): string
    {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__, 3));
        $dotenv->load();

        $key  = $_ENV["JWT_KEY"];
        $tokenId    = base64_encode(random_bytes(16));
        $issuedAt   = new DateTimeImmutable();

        $payload = [
            'iat'  => $issuedAt->getTimestamp(),
            'jti'  => $tokenId,
            'iss'  => "your.domain.name",
            'nbf'  => $issuedAt->getTimestamp(),
            'exp'  => $issuedAt->modify('+6 hours')->getTimestamp(),
            'data' => [
                'userName'   => $email,
                'superAdmin' => $superAdmin
            ]
        ];

        return JWT::encode(
            $payload,
            $key,
            'HS512'
        );
    }

    /**
     * Valide un JWT et vérifie s'il est encore valide.
     *
     * @param string|null $jwt Le JWT à valider.
     * @return bool Renvoie true si le JWT est valide, sinon false.
     */
    public static function validate(?string $jwt = null): bool
    {
        if ($jwt === null && isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $authorizationHeader = $_SERVER['HTTP_AUTHORIZATION'];

            if (strpos($authorizationHeader, 'Bearer ') === 0) {
                if (!preg_match('/Bearer\s(\S+)/', $authorizationHeader, $matches)) {
                    exit;
                }

                $jwt = $matches[1];
                if (!$jwt) {
                    exit;
                }
            }
        }

        if ($jwt) {
            $key  = $_ENV["JWT_KEY"];
            $key = new Key($key, 'HS512');

            // Définir un gestionnaire d'erreurs personnalisé pour supprimer l'exception
            set_error_handler(function () {
                return true; // Ne rien faire en cas d'erreur
            });

            try {
                $token = JWT::decode($jwt, $key);

                // Maintenant, rétablir le gestionnaire d'erreurs
                restore_error_handler();

                $now = new DateTimeImmutable();
                $serverName = "your.domain.name";

                // Check if this token has expired.
                if (isset($token->exp) && $now->getTimestamp() >= $token->exp) {
                    return false; // Le JWT est expiré
                }

                if (
                    $token->iss !== $serverName ||
                    $token->nbf > $now->getTimestamp() ||
                    $token->exp < $now->getTimestamp()
                ) {

                    return false;
                }

                // D'autres vérifications peuvent être ajoutées ici si nécessaire

                return true; // Le JWT est valide
            } catch (\Exception $e) {
                // Le JWT n'est pas valide pour une raison autre que l'expiration
                // ou il y a eu une erreur lors du décodage
            } finally {
                // Assurez-vous de toujours rétablir le gestionnaire d'erreurs
                restore_error_handler();
            }
        }

        return false;
    }
}
