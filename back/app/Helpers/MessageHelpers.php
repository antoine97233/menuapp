<?php

namespace App\Helpers;

/**
 * Classe utilitaire pour générer des messages d'alerte.
 */
class MessageHelpers
{


    /**
     * Génère un message d'alerte avec un type spécifié.
     *
     * @param string $type     Type du message ("success", "info", "warning", "danger", etc.).
     * @param string $action   Action effectuée (utilisé pour personnaliser le message).
     *
     * @return string Le code HTML de l'alerte.
     */
    public static function successMessage(string $action): string
    {
        return "<div class='alert alert-success fixed-top w-100 p-3 shadow-sm text-center'>
        <p>Élément " . $action . "</p>
        <div class='d-flex justify-content-center'>
            <div class='spinner-border text-success' role='status'>
                <span class='sr-only'>Loading...</span>
            </div>
        </div>
    </div>";
    }

    /**
     * Génère un message d'alerte en cas d'échec.
     *
     * @param string $message  Message d'erreur à afficher.
     *
     * @return string Le code HTML de l'alerte.
     */
    public static function failedMessage(string $message): string
    {
        return "<div class='alert alert-danger fixed-top w-100  p-3 shadow-sm' role='alert'>
        <div class='text-end'> 
            <button type='button' class='btn-close text-center' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>
        <p class='text-center'>Erreur : " . $message . "</p>
    </div>";
    }

    /**
     * Génère un message d'erreur 404.
     *
     * @param string $message  Message d'erreur à afficher.
     *
     * @return string Le code HTML de l'alerte d'erreur 404.
     */
    public static function errorPage(string $message): string
    {
        return "<div class='alert alert-warning fixed-top w-100  p-3 shadow-sm' role='alert'>
        <p class='text-center'>Erreur 404 : " . $message . "</p>
    </div>";
    }


    /**
     * Génère un message d'erreur avec un lien de retour.
     *
     * @param string $link Lien de retour vers la page précédente.
     *
     * @return string Le code HTML de l'alerte d'erreur.
     */
    public static function errorMessage(string $link): string
    {
        return "<div class='alert alert-danger fixed-top w-100 p-3 shadow-sm' role='alert'>
        <p class='text-center'>Erreur</p>
        <p class='text-center'>Revenir à la page précédente <a href='index.php?controller=" . $link . "'>ici !</a></p>
    </div>";
    }

    /**
     * Génère un message d'alerte pour rafraîchir la page.
     *
     * @param string $message  Message d'alerte à afficher.
     *
     * @return string Le code HTML de l'alerte de rafraîchissement.
     */
    public static function refreshMessage(string $message): string
    {
        return "<div class='refreshMessage fixed-top top-0 start-0 w-100 h-100 bg-dark opacity-75'></div>
    <div class='alert alert-warning position-absolute top-50 start-50 translate-middle p-4 shadow-sm refreshMessage' role='alert'>
        <p class='text-center'>" . $message . "</p>
        <p class='text-center'>Cliquez <a href='index.php?controller=auth&action=logout'>ici</a></p>
    </div>";
    }
}
