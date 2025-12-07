<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Exception;

class ImageOptimizationService
{
    protected $manager;
    
    // Tailles optimisées pour différentes utilisations
    const SIZES = [
        'thumbnail' => ['width' => 300, 'height' => 300, 'quality' => 80],
        'medium' => ['width' => 800, 'height' => 800, 'quality' => 85],
        'large' => ['width' => 1200, 'height' => 1200, 'quality' => 90],
        'original' => ['quality' => 95], // Conserver l'original mais optimisé
    ];

    public function __construct()
    {
        // Utiliser GD (inclus dans PHP)
        try {
            if (extension_loaded('gd')) {
                $this->manager = new ImageManager(new Driver());
            } else {
                $this->manager = null;
            }
        } catch (Exception $e) {
            // Fallback si Intervention Image n'est pas disponible
            $this->manager = null;
        }
    }

    /**
     * Optimise et redimensionne une image
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $directory
     * @param string $filename
     * @return array Retourne un tableau avec les chemins des différentes tailles
     */
    public function optimizeAndResize($file, $directory = 'modeles', $filename = null)
    {
        if (!$filename) {
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
        } else {
            $extension = pathinfo($filename, PATHINFO_EXTENSION) ?: $file->getClientOriginalExtension();
            $filename = pathinfo($filename, PATHINFO_FILENAME);
        }

        // Normaliser l'extension en minuscules
        $extension = strtolower($extension);
        
        // Convertir en WebP si possible, sinon garder le format original
        $useWebP = in_array($extension, ['jpg', 'jpeg', 'png']);
        $outputExtension = $useWebP ? 'webp' : $extension;

        $paths = [];

        try {
            if ($this->manager) {
                // Utiliser Intervention Image si disponible
                $image = $this->manager->read($file->getRealPath());
                
                // Générer les différentes tailles
                foreach (self::SIZES as $sizeName => $config) {
                    if ($sizeName === 'original') {
                        // Conserver l'original mais optimisé (limiter à 2000px max)
                        $sourceWidth = $image->width();
                        $sourceHeight = $image->height();
                        if ($sourceWidth > 2000 || $sourceHeight > 2000) {
                            $optimizedImage = $image->scaleDown(2000, 2000);
                        } else {
                            $optimizedImage = $image;
                        }
                    } else {
                        // Redimensionner en respectant le ratio (cover pour remplir)
                        $optimizedImage = $image->cover($config['width'], $config['height']);
                    }

                    // Encoder avec qualité optimisée
                    $quality = $config['quality'] ?? 85;
                    
                    // Sauvegarder
                    $path = $directory . '/' . $sizeName . '/' . $filename . '.' . $outputExtension;
                    $fullPath = storage_path('app/public/' . $path);
                    
                    // Créer le répertoire si nécessaire
                    $dir = dirname($fullPath);
                    if (!is_dir($dir)) {
                        mkdir($dir, 0755, true);
                    }
                    
                    // Encoder et sauvegarder
                    if ($outputExtension === 'webp') {
                        $optimizedImage->toWebp($quality)->save($fullPath);
                    } elseif ($outputExtension === 'jpg' || $outputExtension === 'jpeg') {
                        $optimizedImage->toJpeg($quality)->save($fullPath);
                    } elseif ($outputExtension === 'png') {
                        $optimizedImage->toPng()->save($fullPath);
                    } else {
                        $optimizedImage->save($fullPath);
                    }
                    
                    $paths[$sizeName] = $path;
                }
            } else {
                // Fallback : utiliser les fonctions natives PHP GD
                $paths = $this->optimizeWithGD($file, $directory, $filename, $outputExtension);
            }
        } catch (Exception $e) {
            \Log::error('Erreur lors de l\'optimisation de l\'image: ' . $e->getMessage());
            // En cas d'erreur, sauvegarder l'original
            $path = $directory . '/' . $filename . '.' . $extension;
            $file->storeAs('public', $path);
            $paths['original'] = $path;
        }

        return $paths;
    }

    /**
     * Optimise avec GD (fallback)
     */
    protected function optimizeWithGD($file, $directory, $filename, $extension)
    {
        $paths = [];
        $sourcePath = $file->getRealPath();
        
        // Détecter le type d'image
        $imageInfo = getimagesize($sourcePath);
        if (!$imageInfo) {
            throw new Exception('Impossible de lire l\'image');
        }

        $mimeType = $imageInfo['mime'];
        $sourceWidth = $imageInfo[0];
        $sourceHeight = $imageInfo[1];

        // Créer l'image source selon le type
        switch ($mimeType) {
            case 'image/jpeg':
                $sourceImage = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $sourceImage = imagecreatefrompng($sourcePath);
                break;
            case 'image/gif':
                $sourceImage = imagecreatefromgif($sourcePath);
                break;
            case 'image/webp':
                $sourceImage = imagecreatefromwebp($sourcePath);
                break;
            default:
                throw new Exception('Format d\'image non supporté');
        }

        // Générer les différentes tailles
        foreach (self::SIZES as $sizeName => $config) {
            if ($sizeName === 'original') {
                // Limiter l'original à 2000px max
                $maxDimension = 2000;
                if ($sourceWidth > $maxDimension || $sourceHeight > $maxDimension) {
                    $ratio = min($maxDimension / $sourceWidth, $maxDimension / $sourceHeight);
                    $newWidth = (int)($sourceWidth * $ratio);
                    $newHeight = (int)($sourceHeight * $ratio);
                } else {
                    $newWidth = $sourceWidth;
                    $newHeight = $sourceHeight;
                }
            } else {
                // Calculer les dimensions en respectant le ratio
                $ratio = min($config['width'] / $sourceWidth, $config['height'] / $sourceHeight);
                $newWidth = (int)($sourceWidth * $ratio);
                $newHeight = (int)($sourceHeight * $ratio);
            }

            // Créer une nouvelle image redimensionnée
            $newImage = imagecreatetruecolor($newWidth, $newHeight);
            
            // Préserver la transparence pour PNG et GIF
            if ($mimeType === 'image/png' || $mimeType === 'image/gif') {
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
                $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                imagefill($newImage, 0, 0, $transparent);
            }

            // Redimensionner
            imagecopyresampled(
                $newImage, $sourceImage,
                0, 0, 0, 0,
                $newWidth, $newHeight,
                $sourceWidth, $sourceHeight
            );

            // Sauvegarder
            $path = $directory . '/' . $sizeName . '/' . $filename . '.' . $extension;
            $fullPath = storage_path('app/public/' . $path);
            
            // Créer le répertoire si nécessaire
            $dir = dirname($fullPath);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            $quality = $config['quality'] ?? 85;
            
            // Sauvegarder selon le format
            if ($extension === 'webp' && function_exists('imagewebp')) {
                imagewebp($newImage, $fullPath, $quality);
            } elseif ($extension === 'jpg' || $extension === 'jpeg') {
                imagejpeg($newImage, $fullPath, $quality);
            } elseif ($extension === 'png') {
                imagepng($newImage, $fullPath, 9); // PNG utilise 0-9
            } elseif ($extension === 'gif') {
                imagegif($newImage, $fullPath);
            }

            imagedestroy($newImage);
            $paths[$sizeName] = $path;
        }

        imagedestroy($sourceImage);
        return $paths;
    }

    /**
     * Supprime toutes les versions d'une image
     */
    public function deleteImageVersions($imagePath)
    {
        if (!$imagePath) {
            return;
        }

        $directory = dirname($imagePath);
        $filename = basename($imagePath);
        $nameWithoutExt = pathinfo($filename, PATHINFO_FILENAME);

        // Supprimer toutes les tailles
        foreach (self::SIZES as $sizeName => $config) {
            $sizeDir = storage_path('app/public/' . $directory . '/' . $sizeName);
            if (is_dir($sizeDir)) {
                // Chercher tous les fichiers avec ce nom (différentes extensions)
                $pattern = $sizeDir . '/' . $nameWithoutExt . '.*';
                $files = glob($pattern);
                foreach ($files as $file) {
                    @unlink($file);
                }
            }
        }

        // Supprimer aussi l'original si présent
        if (Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }
    }

    /**
     * Obtient l'URL d'une image optimisée
     */
    public function getOptimizedUrl($imagePath, $size = 'medium')
    {
        if (!$imagePath) {
            return null;
        }

        // Si c'est déjà une URL complète
        if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
            return $imagePath;
        }

        // Extraire le répertoire de base et le nom du fichier
        $pathParts = explode('/', $imagePath);
        $sizeNames = array_keys(self::SIZES);
        
        // Vérifier si le chemin contient déjà une taille
        $baseDirectory = '';
        $filename = basename($imagePath);
        $existingSize = null;
        $sizeIndex = -1;
        
        foreach ($pathParts as $index => $part) {
            if (in_array($part, $sizeNames)) {
                $existingSize = $part;
                $sizeIndex = $index;
                // Reconstruire le répertoire de base sans la taille
                $baseDirectory = implode('/', array_slice($pathParts, 0, $index));
                break;
            }
        }
        
        // Si le chemin contient déjà la taille demandée, retourner directement
        if ($existingSize === $size) {
            return Storage::url($imagePath);
        }
        
        if ($sizeIndex === -1) {
            // Chemin normal sans taille, extraire directory
            $directory = dirname($imagePath);
            $baseDirectory = $directory === '.' ? '' : $directory;
        }
        
        $nameWithoutExt = pathinfo($filename, PATHINFO_FILENAME);
        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        // Construire le chemin optimisé avec la taille demandée
        if ($baseDirectory) {
            $optimizedPath = $baseDirectory . '/' . $size . '/' . $nameWithoutExt . '.webp';
        } else {
            $optimizedPath = $size . '/' . $nameWithoutExt . '.webp';
        }
        
        // Essayer WebP d'abord
        if (Storage::disk('public')->exists($optimizedPath)) {
            return Storage::url($optimizedPath);
        }

        // Essayer avec l'extension originale
        if ($baseDirectory) {
            $optimizedPath = $baseDirectory . '/' . $size . '/' . $nameWithoutExt . '.' . $extension;
        } else {
            $optimizedPath = $size . '/' . $nameWithoutExt . '.' . $extension;
        }
        
        if (Storage::disk('public')->exists($optimizedPath)) {
            return Storage::url($optimizedPath);
        }

        // Fallback sur l'original si la taille demandée n'existe pas
        return Storage::url($imagePath);
    }
}

