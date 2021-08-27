<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

use App\Models\Role;

class RoleController extends Controller
{
    public function read(Request $request)
    {
        $start  = $request->page ? $request->page - 1 : 0;
        $length = $request->limit;   
        $search = $request->search;

        $roles = Role::selectRaw("
            roles.id,
            roles.code,
            roles.name as role,
            roles.data_manager,
            roles.guest
        ");

        $rows  = clone $roles;
        $total = $rows->count();

        $roles->offset($start);
        $roles->limit($length); 
        $roles = $roles->get();

        $data = [];
        foreach ($roles as $key => $role) {
            $data[] = $role;
        }

        if(!$roles){
            return response()->json([
                'status'    => Response::HTTP_BAD_REQUEST,
                'message'   => 'Failed to get role data.'
            ],Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'status' => Response::HTTP_OK,
            'total'  => $total,
            'data'   => $data
        ], Response::HTTP_OK);
    }
}
