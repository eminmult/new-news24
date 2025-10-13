<?php

namespace App\Services;

class AzerbaijaniSearchNormalizer
{
    /**
     * Карта замен азербайджанских специфичных букв
     */
    protected static array $characterMap = [
        // Ə → a, e
        'Ə' => ['A', 'E'],
        'ə' => ['a', 'e'],

        // Ö → o
        'Ö' => ['O'],
        'ö' => ['o'],

        // Ü → u
        'Ü' => ['U'],
        'ü' => ['u'],

        // I (без точки) → i
        'I' => ['I', 'i'],
        'ı' => ['i'],

        // Ğ → g, gh
        'Ğ' => ['G', 'Gh'],
        'ğ' => ['g', 'gh'],

        // Ş → sh, s
        'Ş' => ['Sh', 'S'],
        'ş' => ['sh', 's'],

        // Ç → ch, c
        'Ç' => ['Ch', 'C'],
        'ç' => ['ch', 'c'],
    ];

    /**
     * Обратная карта - от латиницы к азербайджанским буквам
     */
    protected static array $reverseMap = [
        'a' => 'ə',
        'e' => 'ə',
        'o' => 'ö',
        'u' => 'ü',
        'i' => 'ı',
        'g' => 'ğ',
        'gh' => 'ğ',
        'sh' => 'ş',
        's' => 's', // оставляем как есть
        'ch' => 'ç',
        'c' => 'c', // оставляем как есть
    ];

    /**
     * Нормализует поисковый запрос для азербайджанского языка
     *
     * @param string $query
     * @return array Массив вариантов запроса
     */
    public static function normalizeQuery(string $query): array
    {
        $variants = [$query];

        // Генерируем варианты с заменой специфичных букв на латиницу
        $latinVariant = self::toLatinVariant($query);
        if ($latinVariant !== $query) {
            $variants[] = $latinVariant;
        }

        // Генерируем варианты с заменой латиницы на специфичные буквы
        $azerbaijaniVariant = self::toAzerbaijaniVariant($query);
        if ($azerbaijaniVariant !== $query) {
            $variants[] = $azerbaijaniVariant;
        }

        return array_unique($variants);
    }

    /**
     * Конвертирует азербайджанские буквы в латиницу (ə → a)
     * Максимально упрощает для лучшего поиска
     */
    public static function toLatinVariant(string $text): string
    {
        $result = $text;

        // Заменяем специфичные буквы на латиницу
        $result = str_replace(['Ə', 'ə'], ['a', 'a'], $result); // всегда на a (самый частый)
        $result = str_replace(['Ö', 'ö'], ['o', 'o'], $result);
        $result = str_replace(['Ü', 'ü'], ['u', 'u'], $result);
        $result = str_replace(['I', 'ı'], ['i', 'i'], $result);
        $result = str_replace(['Ğ', 'ğ'], ['g', 'g'], $result);
        $result = str_replace(['Ş', 'ş'], ['sh', 'sh'], $result);
        $result = str_replace(['Ç', 'ç'], ['ch', 'ch'], $result);

        return $result;
    }

    /**
     * Конвертирует азербайджанские буквы в латиницу (ə → e)
     * Альтернативный вариант для тех кто пишет "e" вместо "ə"
     */
    public static function toLatinVariantWithE(string $text): string
    {
        $result = $text;

        // Заменяем специфичные буквы на латиницу
        $result = str_replace(['Ə', 'ə'], ['e', 'e'], $result); // ə → e (альтернатива)
        $result = str_replace(['Ö', 'ö'], ['o', 'o'], $result);
        $result = str_replace(['Ü', 'ü'], ['u', 'u'], $result);
        $result = str_replace(['I', 'ı'], ['i', 'i'], $result);
        $result = str_replace(['Ğ', 'ğ'], ['g', 'g'], $result);
        $result = str_replace(['Ş', 'ş'], ['sh', 'sh'], $result);
        $result = str_replace(['Ç', 'ç'], ['ch', 'ch'], $result);

        return $result;
    }

    /**
     * Конвертирует латиницу в азербайджанские буквы (упрощенный вариант)
     */
    public static function toAzerbaijaniVariant(string $text): string
    {
        // Проверяем, есть ли уже специфичные буквы
        if (preg_match('/[əöüığşç]/ui', $text)) {
            return $text; // Уже азербайджанский текст
        }

        $result = $text;

        // Заменяем сочетания букв на специфичные азербайджанские
        // Сначала длинные сочетания, потом короткие
        $result = preg_replace('/sh/ui', 'ş', $result);
        $result = preg_replace('/ch/ui', 'ç', $result);
        $result = preg_replace('/gh/ui', 'ğ', $result);

        return $result;
    }

    /**
     * Генерирует все возможные варианты написания слова
     * с учетом азербайджанских букв
     */
    public static function generateVariants(string $word): array
    {
        $variants = [$word];

        // Добавляем латинизированный вариант
        $latinVariant = self::toLatinVariant($word);
        if ($latinVariant !== $word) {
            $variants[] = $latinVariant;
        }

        // Добавляем вариант с sh → ş
        if (stripos($word, 'sh') !== false) {
            $variants[] = str_ireplace('sh', 'ş', $word);
        }

        // Добавляем вариант с ch → ç
        if (stripos($word, 'ch') !== false) {
            $variants[] = str_ireplace('ch', 'ç', $word);
        }

        // Добавляем вариант с gh → ğ
        if (stripos($word, 'gh') !== false) {
            $variants[] = str_ireplace('gh', 'ğ', $word);
        }

        // Популярные замены
        $commonReplacements = [
            ['aliyev', 'əliyev'],
            ['eliyev', 'əliyev'],
            ['baki', 'bakı'],
            ['baku', 'bakı'],
            ['qarabag', 'qarabağ'],
            ['karabakh', 'qarabağ'],
            ['karabag', 'qarabağ'],
            ['azerbaycan', 'azərbaycan'],
            ['azerbaijan', 'azərbaycan'],
        ];

        foreach ($commonReplacements as [$from, $to]) {
            if (stripos($word, $from) !== false) {
                $variants[] = str_ireplace($from, $to, $word);
            }
            if (stripos($word, $to) !== false) {
                $variants[] = str_ireplace($to, $from, $word);
            }
        }

        return array_unique($variants);
    }
}
