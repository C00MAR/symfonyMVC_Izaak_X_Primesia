<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploadService
{
    public function __construct(
        private string $uploadDirectory,
        private SluggerInterface $slugger,
    ) {
    }

    /**
     * Upload plusieurs images pour un produit
     * @param UploadedFile[] $files
     * @param int $productId
     * @return array Noms des fichiers uploadés
     */
    public function uploadProductImages(array $files, int $productId): array
    {
        $uploadedFiles = [];
        $productDir = $this->uploadDirectory . '/products/' . $productId;

        // Créer le dossier du produit s'il n'existe pas
        if (!is_dir($productDir)) {
            mkdir($productDir, 0755, true);
        }

        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $fileName = $this->uploadSingleProductImage($file, $productId);
                $uploadedFiles[] = $fileName;
            }
        }

        return $uploadedFiles;
    }

    /**
     * Upload une seule image pour un produit
     */
    public function uploadSingleProductImage(UploadedFile $file, int $productId): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        $productDir = $this->uploadDirectory . '/products/' . $productId;

        // Créer le dossier du produit s'il n'existe pas
        if (!is_dir($productDir)) {
            mkdir($productDir, 0755, true);
        }

        try {
            $file->move($productDir, $fileName);
        } catch (FileException $e) {
            throw new \Exception('Erreur lors de l\'upload du fichier: ' . $e->getMessage());
        }

        return $fileName;
    }

    /**
     * Supprimer une image spécifique d'un produit
     */
    public function deleteProductImage(int $productId, string $filename): void
    {
        $filepath = $this->uploadDirectory . '/products/' . $productId . '/' . $filename;
        if (file_exists($filepath)) {
            unlink($filepath);
        }
    }

    /**
     * Supprimer toutes les images d'un produit
     */
    public function deleteAllProductImages(int $productId): void
    {
        $productDir = $this->uploadDirectory . '/products/' . $productId;
        
        if (is_dir($productDir)) {
            $files = glob($productDir . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            rmdir($productDir);
        }
    }

    /**
     * Réorganiser les images après suppression (optionnel)
     */
    public function reorderProductImages(int $productId, array $orderedFilenames): void
    {
        $productDir = $this->uploadDirectory . '/products/' . $productId;
        $tempDir = $productDir . '_temp';

        if (!is_dir($productDir) || !is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        // Déplacer les fichiers dans l'ordre souhaité
        foreach ($orderedFilenames as $index => $filename) {
            $oldPath = $productDir . '/' . $filename;
            $newPath = $tempDir . '/' . sprintf('%02d_%s', $index + 1, $filename);
            
            if (file_exists($oldPath)) {
                rename($oldPath, $newPath);
            }
        }

        // Supprimer l'ancien dossier et renommer le nouveau
        $this->deleteAllProductImages($productId);
        rename($tempDir, $productDir);
    }

    /**
     * Obtenir toutes les images d'un produit
     */
    public function getProductImages(int $productId): array
    {
        $productDir = $this->uploadDirectory . '/products/' . $productId;
        $images = [];

        if (is_dir($productDir)) {
            $files = scandir($productDir);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..' && is_file($productDir . '/' . $file)) {
                    $images[] = $file;
                }
            }
        }

        return $images;
    }
}
