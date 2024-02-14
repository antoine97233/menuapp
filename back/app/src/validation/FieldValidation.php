<?php

namespace App\src\validation;

class FieldValidation
{
    /**
     * Vérifie si tous les champs du formulaire sont remplis.
     *
     * @param array $formData Données du formulaire.
     * @return bool True si tous les champs sont remplis, sinon False.
     */
    public static function validateForm(array $formData)
    {
        foreach ($formData as $field) {
            if (empty($field)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Vérifie si le mot de passe respecte certaines conditions.
     *
     * @param string $password Mot de passe à vérifier
     * @return bool True si le mot de passe est valide, sinon False
     */
    public static function validatePassword($password)
    {
        // Le mot de passe doit avoir au moins 8 caractères, une majuscule, un chiffre et un symbole.
        $regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#^]).{8,}$/';

        return preg_match($regex, $password);
    }

    /**
     * Vérifie si le champ ne contient pas d'espaces.
     *
     * @param string $name Champ à vérifier.
     * @return bool True si le champ ne contient pas d'espaces, sinon False.
     */
    public static function validateNoSpaces($name)
    {
        return !preg_match('/\s/', $name);
    }
}
