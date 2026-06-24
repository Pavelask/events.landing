<?php

if (!function_exists('clean_html')) {
    /**
     * Sanitize HTML: strip dangerous tags/attributes, keep safe formatting.
     * Allowed: p, br, strong, em, b, i, u, a, ul, ol, li, h2-h6, blockquote, pre, code, img, span, div.
     * Allowed attributes: href, src, alt, title, class, style (inline only), target, rel.
     */
    function clean_html(?string $html): string
    {
        if ($html === null || $html === '') {
            return '';
        }
        

        $allowedTags = [
            'p' => ['style'],
            'br' => [],
            'strong' => [],
            'b' => [],
            'em' => [],
            'i' => [],
            'u' => [],
            'a' => ['href', 'title', 'target', 'rel'],
            'ul' => [],
            'ol' => [],
            'li' => [],
            'h2' => ['style'],
            'h3' => ['style'],
            'h4' => [],
            'h5' => [],
            'h6' => [],
            'blockquote' => [],
            'pre' => [],
            'code' => [],
            'img' => ['src', 'alt', 'title', 'width', 'height'],
            'span' => ['class'],
            'div' => ['class'],
        ];

        $html = strip_tags($html, '<' . implode('><', array_keys($allowedTags)) . '>');

        $html = preg_replace_callback('/<([a-z][a-z0-9]*)(?:\s+([^>]*?))?>/i', function ($match) use ($allowedTags) {
            $tag = strtolower($match[1]);
            $attrs = $match[2] ?? '';

            if (!isset($allowedTags[$tag])) {
                return '';
            }

            $allowed = $allowedTags[$tag];
            $cleaned = preg_replace_callback('/(\w+)(?:\s*=\s*(?:"([^"]*)"|\'([^\']*)\'|(\S+)))?/', function ($m) use ($allowed) {
                $attr = strtolower($m[1]);
                if (!in_array($attr, $allowed)) {
                    return '';
                }
                $val = $m[2] ?? $m[3] ?? $m[4] ?? $attr;
                if ($attr === 'href' || $attr === 'src') {
                    if (preg_match('/^\s*(javascript|data|vbscript):/i', $val)) {
                        return '';
                    }
                }
                return $attr . '="' . htmlspecialchars($val, ENT_QUOTES, 'UTF-8') . '"';
            }, $attrs);

            return '<' . $tag . ($cleaned ? ' ' . $cleaned : '') . '>';
        }, $html);

        return $html;
    }
}
