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
