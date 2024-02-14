<?php

namespace App\src\fileTreatment;

use Exception;

/**
 * Gestion des images ajoutées.
 */
class ManageFile
{


    /**
     * Gère le téléchargement, la validation et la mise à jour facultative d'images.
     *
     * @param string $inputName Nom de l'élément input du formulaire contenant le fichier image.
     * @param string $outputPath Chemin de sortie où l'image téléchargée sera enregistrée.
     * @param int|null $resizeWidth Largeur souhaitée de l'image redimensionnée (optionnel).
     * @param string|null $existingImagePath Chemin de l'image existante à mettre à jour (optionnel).
     *
     * @return string Le chemin de l'image téléchargée ou le chemin de l'image par défaut.
     *
     * @throws \Exception En cas d'erreur lors de la validation de l'image.
     */
    public static function processImage($inputName, $outputPath, $resizeWidth = null, $existingImagePath = null)
    {
        if (!empty($_FILES[$inputName]) && $_FILES[$inputName]['error'] == 0) {
            $itemImage = $_FILES[$inputName];

            $allowedTypes = [IMAGETYPE_JPEG, IMAGETYPE_PNG];
            $detectedType = exif_imagetype($itemImage['tmp_name']);

            if (!in_array($detectedType, $allowedTypes)) {
                throw new Exception('Le fichier n\'est pas une image valide.', 400);
            }

            $itemImagePath = $outputPath;
            move_uploaded_file($itemImage['tmp_name'], $itemImagePath);

            if ($existingImagePath && $itemImagePath !== $existingImagePath && file_exists($existingImagePath)) {
                unlink($existingImagePath);
            }

            if ($resizeWidth !== null) {
                self::resizeImage($itemImagePath, $resizeWidth);
            }

            return $itemImagePath;
        } else {
            return null;
        }
    }


    /**
     * Gère le téléchargement, la validation et la mise à jour facultative des fichiers PDF.
     *
     * @param string $inputName Nom de l'élément input du formulaire contenant le fichier PDF.
     * @param string $outputPath Chemin de sortie où le fichier PDF téléchargé sera enregistré.
     * @param string|null $existingFilePath Chemin du fichier PDF existant à mettre à jour (optionnel).
     *
     * @return string Le chemin du fichier PDF téléchargé ou le chemin du fichier PDF existant.
     *
     * @throws \Exception En cas d'erreur lors de la validation du fichier PDF.
     */
    public static function processFile($inputName, $outputPath, $existingFilePath = null)
    {
        if (!empty($_FILES[$inputName]) && $_FILES[$inputName]['error'] == 0) {
            $uploadedFile = $_FILES[$inputName];

            $allowedTypes = ['application/pdf'];
            $detectedType = mime_content_type($uploadedFile['tmp_name']);

            if (!in_array($detectedType, $allowedTypes)) {
                throw new Exception('Le fichier n\'est pas un fichier PDF valide.', 400);
            }

            $filePath = $outputPath;
            move_uploaded_file($uploadedFile['tmp_name'], $filePath);

            if ($existingFilePath && $filePath !== $existingFilePath && file_exists($existingFilePath)) {
                unlink($existingFilePath);
            }

            return $filePath;
        } else {
            return null;
        }
    }





    /**
     * Redimensionne une image en utilisant la bibliothèque GD.
     *
     * @param string $imagePath Chemin de l'image
     * @param int $newWidth Nouvelle largeur
     * @param int $newHeight Nouvelle hauteur
     */
    private static function resizeImage($imagePath, $newWidth, $newHeight = null)
    {
        list($width, $height) = getimagesize($imagePath);

        $aspectRatio = $width / $height;
        if ($newWidth == 0) {
            $newWidth = (int)round($newHeight * $aspectRatio);
        } elseif ($newHeight == 0) {
            $newHeight = (int)round($newWidth / $aspectRatio);
        }

        $thumb = imagecreatetruecolor($newWidth, $newHeight);
        $source = imagecreatefromjpeg($imagePath);

        if (!$source || !imagecopyresized($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height)) {
            imagedestroy($thumb);
            imagedestroy($source);
            throw new Exception('Erreur lors du redimensionnement de l\'image.', 500);
        }

        ob_start();
        if (!imagejpeg($thumb, $imagePath, 90)) {
            ob_end_clean();
            imagedestroy($thumb);
            imagedestroy($source);
            throw new Exception('Erreur lors de la sauvegarde de l\'image redimensionnée.', 500);
        }
        ob_end_clean();

        imagedestroy($thumb);
        imagedestroy($source);
    }
}
