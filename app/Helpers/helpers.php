<?php

use RealRashid\SweetAlert\Facades\Alert;

if (!function_exists('confirmDelete')) {
    function confirmDelete($title, $text)
    {
        Alert::question($title, $text)
            ->showConfirmButton('Yes, delete it!', '#3085d6')
            ->showCancelButton('Cancel', '#d33')
            ->reverseButtons()
            ->toHtml();
    }
}

if (!function_exists('format_price')) {
    function format_price($val, $fallback = 'N/A')
    {
        if ($val === null || $val === '' || $val === 'N/A' || $val === '-') {
            return $fallback;
        }

        $str = trim((string)$val);
        if ($str === '' || $str === '-' || strtolower($str) === 'n/a') {
            return $fallback;
        }

        $prefix = '';
        if (preg_match('/^(rp\.?|idr)\s*/i', $str, $matches)) {
            $prefix = $matches[0];
            $str = trim(substr($str, strlen($prefix)));
        }

        // If already formatted in Indonesian: 57.805.000 or 57.805.000,50
        if (preg_match('/^\d{1,3}(\.\d{3})+(,\d+)?$/', $str)) {
            return $prefix . $str;
        }

        // If formatted in US/comma: 57,805,000 or 57,805,000.50
        if (preg_match('/^\d{1,3}(,\d{3})+(\.\d+)?$/', $str)) {
            $parts = explode('.', $str);
            $intPart = str_replace(',', '.', $parts[0]);
            $decPart = isset($parts[1]) && strlen($parts[1]) > 0 ? (',' . $parts[1]) : '';
            return $prefix . $intPart . $decPart;
        }

        // If contains comma as decimal without dots: 57805000,50
        if (strpos($str, ',') !== false && strpos($str, '.') === false) {
            $parts = explode(',', $str);
            if (is_numeric($parts[0])) {
                $intFormatted = number_format((float)$parts[0], 0, '', '.');
                $decFormatted = isset($parts[1]) && strlen($parts[1]) > 0 ? (',' . $parts[1]) : '';
                return $prefix . $intFormatted . $decFormatted;
            }
        }

        // If standard numeric: 57805000 or 57805000.5
        if (is_numeric($str)) {
            $parts = explode('.', $str);
            $intFormatted = number_format((float)$parts[0], 0, '', '.');
            $decFormatted = '';
            if (isset($parts[1])) {
                $dec = rtrim($parts[1], '0');
                if (strlen($dec) > 0) {
                    $decFormatted = ',' . $dec;
                }
            }
            return $prefix . $intFormatted . $decFormatted;
        }

        return $prefix . $str;
    }
}

