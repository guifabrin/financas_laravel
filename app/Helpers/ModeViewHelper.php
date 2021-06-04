<?php

namespace App\Helpers;

use App\SysConfig;
use App\UserConfig;

class ModeViewHelper
{
    public static function check($request, $constant)
    {
        $modeViewConfigId = config($constant);
        $modeViewConfig = $request->user()->configs()->where(['config_id' => $modeViewConfigId])->first();
        if (!isset($modeViewConfig)) {
            $modeViewConfig = new UserConfig;
            $modeViewConfig->user()->associate($request->user());
            $modeViewConfig->config()->associate(SysConfig::find($modeViewConfigId));
            $modeViewConfig->value = 'table';
            $modeViewConfig->save();
        }
        if (isset($request->view_mode)) {
            $modeViewConfig->value = $request->view_mode;
            $modeViewConfig->save();
        }
        return $modeViewConfig->value;
    }
}