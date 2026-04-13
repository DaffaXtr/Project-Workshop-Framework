<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('generateGuestName')) {
    function generateGuestName()
    {
        $last = DB::table('pesanan')->count() + 1;
        return 'Guest_' . str_pad($last, 7, '0', STR_PAD_LEFT);
    }
}