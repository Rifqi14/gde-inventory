<?php

namespace App\Http\Controllers;

use App\Models\LogRevise;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\File;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Global function to save reason attachment
     *
     * @param \Illuminate\Http\Request $file
     * @param string $filename
     * @param string $route
     * @param int $id
     * @return bool
     */
    public function reasonAttachment($file, $filename, $route, $id)
    {
        $src    = "assets/$route/$id";
        if (!file_exists($src)) {
            mkdir($src, 0777, true);
        }
        $file->move($src, $filename);

        if ($file) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Global function to remove log revise
     *
     * @param string $route
     * @param int $id
     * @return bool
     */
    public function destroyLogRevise($route, $id)
    {
        $logs   = LogRevise::routeMenu($route)->dataId($id)->get();
        $id     = [];
        foreach ($logs as $key => $log) {
            File::delete($log->revise_attachment);
            array_push($id, $log->id);
        }
        $logDestroy = LogRevise::destroy($id);

        if ($logDestroy) {
            return true;
        } else {
            return false;
        }
    }
}
