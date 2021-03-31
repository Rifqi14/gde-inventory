<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use App\Models\MenuRole;

class RoleMenuController extends Controller
{
    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'id' 	=> 'required',
            'checked' 	=> 'required',
            'type' 	=> 'required',
        ]);

        if ($validator->fails()) {
        	return response()->json([
        		'status' 	=> false,
        		'message' 	=> $validator->errors()->first()
        	], 400);
        }
        switch($request->type){
            case 'access':
                $rolemenu = MenuRole::find($request->id);
                $rolemenu->role_access = $request->checked;
                $rolemenu->save();
                $message = 'Show Access Has Been Updated';
                break;
            case 'create':
                $rolemenu = MenuRole::find($request->id);
                $rolemenu->create = $request->checked;
                $rolemenu->save();
                $message = 'Create Access Has Been Updated';
                break;
            case 'read':
                $rolemenu = MenuRole::find($request->id);
                $rolemenu->read = $request->checked;
                $rolemenu->save();
                $message = 'Read Access Has Been Updated';
                break;
            case 'update':
                $rolemenu = MenuRole::find($request->id);
                $rolemenu->update = $request->checked;
                $rolemenu->save();
                $message = 'Update Access Has Been Updated';
                break;
            case 'delete':
                $rolemenu = MenuRole::find($request->id);
                $rolemenu->delete = $request->checked;
                $rolemenu->save();
                $message = 'Delete Access Has Been Updated';
                break;
            case 'import':
                $rolemenu = MenuRole::find($request->id);
                $rolemenu->import = $request->checked;
                $rolemenu->save();
                $message = 'Import Access Has Been Updated';
                break;
            case 'export':
                $rolemenu = MenuRole::find($request->id);
                $rolemenu->export = $request->checked;
                $rolemenu->save();
                $message = 'Export Access Has Been Updated';
                break;
            case 'print':
                $rolemenu = MenuRole::find($request->id);
                $rolemenu->print = $request->checked;
                $rolemenu->save();
                $message = 'Print Access Has Been Updated';
                break;
            case 'approval':
                $rolemenu = MenuRole::find($request->id);
                $rolemenu->approval = $request->checked;
                $rolemenu->save();
                $message = 'Approval Access Has Been Updated';
                break;
        }
        
        if (!$rolemenu) {
            return response()->json([
                'success' => false,
                'message' 	=> $rolemenu
            ], 400);
        }
        return response()->json([
        	'status' 	=> true,
        	'message' 	=> $message,
        ], 200);
    }
}
