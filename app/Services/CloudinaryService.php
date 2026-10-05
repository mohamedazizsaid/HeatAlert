<?php

namespace App\Services;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

/**
 * Service d'upload d'images vers Cloudinary.
 *
 * Usage dans un controller :
 *   $result = app(CloudinaryService::class)->upload($request->file('photo'), 'signalements');
 *   $publicId = $result['public_id'];   // stocker en BDD
 *   $url      = $result['secure_url'];  // afficher dans les vues
 */
class CloudinaryService
{
    /** Dossier racine de ce projet dans Cloudinary */
    private const ROOT_FOLDER = 'heatalert';

    // ─────────────────────────────────────────────────────────────────────────
    // UPLOAD
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Upload un fichier image vers Cloudinary.
     *
     * @param  UploadedFile $file    Le fichier uploadé via la requête HTTP
     * @param  string       $folder  Sous-dossier dans Cloudinary (ex: 'signalements')
     * @return array{public_id: string, secure_url: string}
     *
     * @throws \RuntimeException si l'upload échoue
     */
    public function upload(UploadedFile $file, string $folder = 'uploads'): array
    {
        $result = Cloudinary::uploadApi()->upload($file->getRealPath(), [
            'folder'        => self::ROOT_FOLDER . '/' . $folder,
            'resource_type' => 'image',
        ]);

        return [
            'public_id'  => $result['public_id'],
            'secure_url' => $result['secure_url'],
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // SUPPRESSION
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Supprime une image depuis Cloudinary via son public_id.
     *
     * @param  string $publicId  Public ID stocké en BDD (ex: "heatalert/signalements/abc123")
     * @return bool   true si suppression réussie, false sinon
     */
    public function delete(string $publicId): bool
    {
        try {
            Cloudinary::uploadApi()->destroy($publicId);
            return true;
        } catch (\Throwable $e) {
            Log::warning('CloudinaryService::delete failed', [
                'public_id' => $publicId,
                'error'     => $e->getMessage(),
            ]);
            return false;
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // URL
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Retourne l'URL sécurisée Cloudinary d'un public_id avec transformations optionnelles.
     *
     * @param  string $publicId  Public ID Cloudinary
     * @param  int    $width     Largeur cible (0 = aucun redimensionnement)
     * @param  int    $height    Hauteur cible (0 = aucun redimensionnement)
     * @param  string $crop      Mode crop Cloudinary ('fill', 'fit', 'thumb', 'limit'…)
     * @return string URL HTTPS
     */
    public function url(string $publicId, int $width = 0, int $height = 0, string $crop = 'fill'): string
    {
        $options = ['secure' => true, 'quality' => 'auto', 'fetch_format' => 'auto'];
        if ($width  > 0) $options['width']  = $width;
        if ($height > 0) $options['height'] = $height;
        if ($width  > 0 || $height > 0) $options['crop'] = $crop;

        return cloudinary()->image($publicId)->toUrl();
    }

    // ─────────────────────────────────────────────────────────────────────────
    // HELPERS
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Détermine si la valeur stockée en BDD est un public_id Cloudinary
     * (et non un chemin local comme "signalements/abc.jpg").
     */
    public function isCloudinaryId(?string $value): bool
    {
        if (! $value) return false;
        return str_starts_with($value, self::ROOT_FOLDER . '/');
    }

    /**
     * Retourne l'URL publique d'un champ photo.
     * - Si c'est un public_id Cloudinary → URL Cloudinary sécurisée
     * - Si c'est un chemin local → asset() local (rétro-compatibilité)
     * - Si null → null
     */
    public function resolveUrl(?string $photoField): ?string
    {
        if (! $photoField) return null;

        if ($this->isCloudinaryId($photoField)) {
            return cloudinary()->image($photoField)->toUrl();
        }

        // Rétro-compatibilité : anciens fichiers stockés localement
        return asset('storage/' . $photoField);
    }
}