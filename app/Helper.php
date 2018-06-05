<?php

function format_money($value){
  return "<font color='".($value<0?'red':'green')."'>".number_format($value, 2 , __('config.decimal_point'), __('config.thousand_point'))."</font>";
}

function formatDate($date){
  return date( __('config.date_format'),strtotime($date));
}

function formatDateTime($date){
  return date( __('config.date_time_format'),strtotime($date));
}