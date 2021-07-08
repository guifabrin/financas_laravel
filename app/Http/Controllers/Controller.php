<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

use App\Helpers\ModeViewHelper;

function view_theme($request, $view, $params = [])
{
    if (!$request->user()) {
        $params['theme'] = 'cosmo';
        return view($view, $params);
    }
    $themes = [
        "cerulean",
        "cosmo",
        "cyborg",
        "darkly",
        "flatly",
        "journal",
        "litera",
        "lumen",
        "lux",
        "materia",
        "minty",
        "morph",
        "pulse",
        "quartz",
        "sandstone",
        "simplex",
        "sketchy",
        "slate",
        "solar",
        "spacelab",
        "superhero",
        "united",
        "vapor",
        "yeti",
        "zephyr",
    ];
    $params['theme'] = ModeViewHelper::theme($request, 'constants.user_configs.theme');
    $params['themes'] = $themes;

    $sql = "
        SELECT a.description AS account_description,
            a.id          AS account_id,
            n.id          AS notification_id,
            n.seen,
            t.*
        FROM   transactions AS t
            JOIN accounts AS a
                ON t.account_id = a.id
            JOIN notifications n
                ON n.entity_id = t.id
        WHERE  t.id IN (SELECT entity_id
                        FROM   notifications)
            AND t.account_id IN (SELECT id
                                    FROM   accounts
                                    WHERE  user_id =? )
        ORDER BY n.id desc
        LIMIT  10 
    ";
    $params['notifications'] = DB::select($sql, [$request->user()->id]);
    $params['notificationsCount'] = count(
        array_filter(
            $params['notifications'],
            function ($v, $k) {
                return !$v->seen;
            },
            ARRAY_FILTER_USE_BOTH,
        ),
    );
    return view($view, $params);
}

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
