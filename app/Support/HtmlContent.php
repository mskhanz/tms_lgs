<?php

namespace App\Support;

class HtmlContent
{
    /** @var list<string> */
    private const ALLOWED_TAGS = [
        'p', 'br', 'strong', 'b', 'em', 'i', 'u', 's', 'strike', 'span',
        'ul', 'ol', 'li', 'h1', 'h2', 'h3', 'h4', 'blockquote', 'a', 'div',
        'sub', 'sup',
    ];

    public static function sanitize(?string $html): ?string
    {
        if ($html === null) {
            return null;
        }

        $html = trim($html);
        if ($html === '' || self::isEmpty($html)) {
            return null;
        }

        $allowed = '<'.implode('><', self::ALLOWED_TAGS).'>';
        $clean = strip_tags($html, $allowed);

        // Drop event handlers / javascript URLs.
        $clean = preg_replace('/\son\w+\s*=\s*(".*?"|\'.*?\'|[^\s>]+)/iu', '', $clean) ?? $clean;
        $clean = preg_replace('/(href|src)\s*=\s*("|\')\s*javascript:[^\\2]*\\2/iu', '$1="#"', $clean) ?? $clean;
        $clean = preg_replace('/<\/?(script|iframe|object|embed|form|input|button|textarea|style)[^>]*>/iu', '', $clean) ?? $clean;

        // Allow only safe inline styles (color / background-color / text-decoration / font-weight / font-style / text-align).
        $clean = preg_replace_callback('/style\s*=\s*("|\')(.*?)\1/iu', function (array $m) {
            $style = $m[2];
            $safe = [];
            foreach (explode(';', $style) as $rule) {
                $rule = trim($rule);
                if ($rule === '' || ! str_contains($rule, ':')) {
                    continue;
                }
                [$prop, $val] = array_map('trim', explode(':', $rule, 2));
                $prop = strtolower($prop);
                $val = preg_replace('/expression|javascript|url\s*\(/iu', '', $val) ?? $val;
                if (in_array($prop, ['color', 'background-color', 'text-decoration', 'font-weight', 'font-style', 'text-align'], true)) {
                    $safe[] = $prop.': '.$val;
                }
            }

            return $safe === [] ? '' : 'style="'.implode('; ', $safe).'"';
        }, $clean) ?? $clean;

        $clean = trim($clean);

        return $clean === '' ? null : $clean;
    }

    public static function isEmpty(?string $html): bool
    {
        $plain = html_entity_decode(strip_tags((string) $html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $plain = preg_replace('/\x{00A0}/u', ' ', $plain) ?? $plain;

        return trim($plain) === '';
    }

    public static function display(?string $html, string $fallback = ''): string
    {
        $clean = self::sanitize($html);
        if ($clean === null) {
            return e($fallback);
        }

        return $clean;
    }
}
