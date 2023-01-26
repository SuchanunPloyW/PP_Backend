<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DB_TranModel;

class DB_TranController extends Controller
{

    public function index()
    {
        $data = DB_TranModel::all();
        return $data;
    }


    public function store(Request $request)
    {
        //
    }
    public function show($id)
    {
        $data = DB_TranModel::where('id', $id)->get();
        return $data;
    }
    // post id
    public function post(Request $request)
    {
        $fields = $request->validate([
            'parent' => 'required',
        ]);
        $data = DB_TranModel::where('parent', $fields['parent'])->get();
        return $data;
    }











    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}