<?php

function format_money($value)
{
    $value = round($value, 2);
    return "<font class='" . ($value < 0 ? 'negative' : 'positive') . "'>" . number_format($value, 2, __('config.decimal_point'), __('config.thousand_point')) . "</font>";
}

function formatDate($date)
{
    return date(__('config.date_format'), strtotime($date));
}

function formatDateTime($date)
{
    return formatDate($date);
    //return date(__('config.date_time_format'), strtotime($date));
}
