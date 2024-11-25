<?php

if (!function_exists('setActive')) {
    function setActive($route)
    {
        return request()->routeIs($route) ? 'h-12 shadow-soft-xl rounded-lg bg-white font-semibold text-slate-700 transition-colors' : '';
    }
}

?>