<?php

function format_money($value){
  return "<font color='".($value<0?'red':'green')."'>".number_format($value, 2 , __('config.decimal_point'), __('config.thousand_point'))."</font>";
}

function format_date($date){
  return date_format(date_create_from_format('Y-m-d', $date), __('config.date_format'));
}