<?php

use Carbon\Carbon;

if (!function_exists('format_date_az')) {
    /**
     * Format date with capitalized month name for Azerbaijani locale
     *
     * @param Carbon $date
     * @param string $format
     * @return string
     */
    function format_date_az(Carbon $date, string $format = 'd F Y, H:i'): string
    {
        // Set locale to Azerbaijani
        $date->locale('az');

        $formatted = $date->translatedFormat($format);

        // Capitalize first letter of month names in Azerbaijani
        $months = [
            'yanvar', 'fevral', 'mart', 'aprel', 'may', 'iyun',
            'iyul', 'avqust', 'sentyabr', 'oktyabr', 'noyabr', 'dekabr'
        ];

        foreach ($months as $month) {
            $capitalizedMonth = mb_convert_case($month, MB_CASE_TITLE, 'UTF-8');
            $formatted = str_replace($month, $capitalizedMonth, $formatted);
        }

        return $formatted;
    }
}
