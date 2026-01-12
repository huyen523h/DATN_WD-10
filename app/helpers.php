<?php
if (! function_exists('placeholder_url')) {
    /**
     * Build a via.placeholder.com URL
     * @param string $size e.g. '400x250' or '60x40'
     * @param string $foreground e.g. '4F46E5'
     * @param string $background e.g. 'ffffff'
     * @param string|false $text optional text (will be urlencoded)
     * @return string
     */
    function placeholder_url(string $size = '400x250', string $foreground = 'CCCCCC', string $background = 'ffffff', $text = false): string
    {
        // Return an inline SVG data URI so images load without external DNS
        $w = 100;
        $h = 100;
        if (preg_match('/^(\\d+)x(\\d+)$/', $size, $m)) {
            $w = (int) $m[1];
            $h = (int) $m[2];
        }
        $fg = ltrim($foreground, '#');
        $bg = ltrim($background, '#');
        $label = '';
        if ($text !== false && $text !== null && $text !== '') {
            $label = (string) $text;
        }
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' . $w . '" height="' . $h . '">' .
            '<rect width="100%" height="100%" fill="#' . $bg . '"/>' .
            '<text x="50%" y="50%" font-family="Arial, Helvetica, sans-serif" font-size="' . max(12, (int) min($w/10, $h/6)) . '" fill="#' . $fg . '" dominant-baseline="middle" text-anchor="middle">' . htmlspecialchars($label) . '</text>' .
            '</svg>';
        return 'data:image/svg+xml;utf8,' . rawurlencode($svg);
    }
}

if (! function_exists('image_url')) {
    /**
     * Normalize image path and return a URL usable in <img src="...">.
     * - If full http(s) URL provided, return it.
     * - If starts with /storage/, return asset(path).
     * - If relative path stored in storage/app/public, return asset('storage/'.path).
     * - If not found, return a placeholder data URI.
     */
    function image_url($path, $defaultSize = '400x250')
    {
        if (!$path) {
            return placeholder_url($defaultSize);
        }

        // Already a full URL
        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }

        // Absolute public path starting with slash
        if (str_starts_with($path, '/')) {
            // If it's already under /storage, just asset() it
            return asset($path);
        }

        // If file exists under storage/app/public/<path>
        $storageFile = storage_path('app/public/' . ltrim($path, '/'));
        if (file_exists($storageFile)) {
            return asset('storage/' . ltrim($path, '/'));
        }

        // If file exists under public/<path>
        $publicFile = public_path(ltrim($path, '/'));
        if (file_exists($publicFile)) {
            return asset(ltrim($path, '/'));
        }

        // Fallback to placeholder
        return placeholder_url($defaultSize);
    }
}
