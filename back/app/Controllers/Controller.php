<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Entities\Admin;
use App\Helpers\MessageHelpers;
use Exception;

abstract class Controller
{
    protected $id;
    protected $title;
    protected $description;

    /**
     * Affiche une vue en utilisant un fichier de modèle.
     *
     * @param string $path Chemin du fichier de modèle.
     * @param array $data Données à passer à la vue.
     * @return void
     */
    public static function render(string $path, array $data = []): void
    {
        extract($data);

        ob_start();

        include dirname(__DIR__) . '/Views/' . $path . '.php';

        $content = ob_get_clean();

        include dirname(__DIR__) . '/Views/base.php';
    }


    /**
     * Affiche une vue en utilisant un fichier de modèle.
     *
     * @param string $path Chemin du fichier de modèle.
     * @param array $data Données à passer à la vue.
     * @return void
     */
    public static function renderOffline(string $path, array $data = []): void
    {
        extract($data);

        ob_start();

        include dirname(__DIR__) . '/Views/' . $path . '.php';

        $content = ob_get_clean();

        include dirname(__DIR__) . '/Views/baseOffline.php';
    }



    /**
     * Vérifie si l'administrateur est authentifié.
     * Redirige vers la page de connexion si l'administrateur n'est pas authentifié.
     *
     * @return void
     */
    protected function checkAdminAuthentication(): void
    {
        session_start();

        if (!isset($_SESSION['adminEmail'])) {
            http_response_code(403);
            $this->logout();
            header("Location: index.php?controller=auth");
            exit();
        }

        $userModel = new UserModel();
        $admin = new Admin();
        $admin->setAdminEmail($_SESSION['adminEmail']);

        $user = $userModel->findByEmail($admin);

        if (empty($user)) {
            http_response_code(403);
            $this->logout();
            header("Location: index.php?controller=auth");
            exit();
        }
    }

    /**
     * Vérifie si l'administrateur est un super administrateur.
     * Redirige vers la page d'accueil si l'administrateur n'est pas un super administrateur.
     *
     * @return bool True si l'administrateur est un super administrateur, sinon false.
     */
    protected function checkIfSuperAdmin(): bool
    {
        $userModel = new UserModel();
        $admin = new Admin();
        $admin->setAdminEmail($_SESSION['adminEmail']);

        $user = $userModel->findByEmail($admin);

        if ($user[0]['adminSuper'] == 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Vérifie si le jeton CSRF est valide.
     * Affiche un message d'erreur et propose de rafraîchir la page si le jeton n'est pas valide.
     *
     * @return bool True si le jeton est valide, sinon false.
     */
    protected function checkCsrfTokenValid(): bool
    {
        if (!isset($_POST['token']) || !$this->isCsrfTokenValid($_POST['token'])) {
            return true;
        }

        return false;
    }

    /**
     * Vérifie si le jeton CSRF est valide côté serveur.
     *
     * @param string $token Jeton CSRF à vérifier.
     * @return bool True si le jeton est valide, sinon false.
     */
    protected function isCsrfTokenValid(string $token): bool
    {
        return isset($_SESSION['token']) && $token === $_SESSION['token'] && $_SESSION['token_time'] >= (time() - (100 * 20));
    }

    /**
     * Génère un jeton CSRF (Cross-Site Request Forgery) et le stocke en session.
     * Si un jeton existe déjà en session, il est remplacé par un nouveau.
     * Le jeton généré est utilisé pour protéger les formulaires contre les attaques CSRF.
     *
     * @return void
     */
    protected function generateCsrfToken(): void
    {
        if (!isset($_SESSION['token'])) {
            $randomBytes = openssl_random_pseudo_bytes(32);
            $token = bin2hex($randomBytes);
            $_SESSION['token'] = $token;
            $_SESSION['token_time'] = time() + (60 * 60 * 6);
        } else {
            unset($_SESSION['token']);
            $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
            $_SESSION['token_time'] = time() + (60 * 60 * 6);
        }
    }

    /**
     * Traite la demande de déconnexion.
     *
     * @return void
     */
    protected function logout(): void
    {
        session_start();
        unset($_SESSION['adminEmail']);
        unset($_SESSION['token']);
        unset($_SESSION['token_time']);
        unset($_SESSION['jwt']);
        session_destroy();
    }


    /**
     * Gère les erreurs en renvoyant une réponse JSON.
     *
     * @param Exception $e L'exception à gérer.
     * @param array $data Les données à inclure dans la réponse.
     * @return string
     */
    protected function handleException(Exception $e): string
    {
        $message = $e->getMessage();
        $code = $e->getCode();

        http_response_code($code);

        if ($code === 401) {
            if ($message === "Vous ne pouvez pas supprimer votre propre compte." || $message === "Identifiants de connexion invalides") {
                $error  = MessageHelpers::failedMessage($message);
            } else {
                $error = MessageHelpers::refreshMessage($message);
            }
        } else {
            $error = MessageHelpers::failedMessage($message);
        }

        return $error;
    }


    /**
     * Gère les erreurs en renvoyant une réponse JSON au format JSON.
     *
     * @param Exception $e L'exception à gérer.
     * @param array $data Les données à inclure dans la réponse.
     * @return string
     */
    protected function handleExceptionJson(Exception $e): string
    {
        $code = $e->getCode();
        http_response_code($code);
        return json_encode(['error' => 'Une erreur est survenue lors de la récupération des données.'], JSON_UNESCAPED_UNICODE);
    }
}
