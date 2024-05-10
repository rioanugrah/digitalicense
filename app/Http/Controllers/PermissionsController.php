<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Validator;

class PermissionsController extends Controller
{
    public function index(Request $request)
    {
        $data = Permission::all();
        return view('permissions.index',compact('data'));
        // if ($request->ajax()) {
        //     $data = Permission::all();
        //     return DataTables::of($data)
        //             ->addIndexColumn()
        //             ->addColumn('action', function($row){
        //                 $btn = '<button type="button" onclick="initFirebaseMessagingRegistration()" class="btn btn-primary btn-icon">
        //                             <i class="fas fa-undo-alt"></i> Allow Notification
        //                         </button>';
        //                 return $btn;
        //             })
        //             ->rawColumns(['action'])
        //             ->make(true);

        // }
        // return view('backend_new_2023.permissions.index');
    }

    public function create()
    {
        return view('permissions.create');
    }

    public function simpan(Request $request)
    {
        $rules = [
            'name'  => 'required',
            'guard_name'  => 'required',
        ];

        $messages = [
            'name.required'  => 'Nama wajib diisi.',
            'guard_name.required'  => 'Guard Name wajib diisi.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->passes()) {
            $input = $request->all();
            $permissions = Permission::create($input);

            if($permissions){
                // $message_title="Berhasil !";
                // $message_content="Permission Berhasil Ditambah";
                // $message_type="success";
                // $message_succes = true;
                return redirect()->route('permissions')
                ->with('success','Permission created successfully');
            }

            // $array_message = array(
            //     'success' => $message_succes,
            //     'message_title' => $message_title,
            //     'message_content' => $message_content,
            //     'message_type' => $message_type,
            // );
            // return response()->json($array_message);
        }
        // dd($validator->errors()->all());
        return redirect()->route('permissions.create')
        ->with(['error' => $validator->errors()->all()]);
        // return response()->json(
        //     [
        //         'success' => false,
        //         'error' => $validator->errors()->all()
        //     ]
        // );
    }
}
