<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DB_CustomerModel;
use App\Models\DB_CampaignModel;
use App\Models\DB_TranModel;
use App\Models\DB_user_groupModel;
use App\Models\User;

class DB_CustomerController extends Controller
{
    public function get_customer(Request $request)
    {
        $login_chk = $request->user();
        $login_chk = $login_chk->status;
        $login_chk_id = $request->user();
        $login_chk_id = $login_chk_id->id;
        $login_branch = $request->user();
        $login_branch = $login_branch->branch;


        $fields = $request->validate([
            'line_usrid' => 'required',
        ]);
        $user = User::where('line_usrid', $fields['line_usrid'])->first();
        $user_id = $user->id;
        //! check permission
        if ($login_chk == 'leader') {
            // leader can see all customer in team
            if ($user_id == $login_chk_id) {
                $data = user_leader($user_id);
                foreach ($data as $key => $value) {
                    if ($data[$key]['leader'] == $user_id) {
                        $salesman = $data[$key]['detail'];
                        $salesman = json_decode($salesman);
                        //find customer in db_customer where parent in salesman
                        $customer = DB_CustomerModel::whereIn('parent', $salesman)
                            ->with('car')
                            ->with('color_car')
                            ->with('grade_car')
                            ->get();
                        $customer_rs = loop_campaign($customer);
                        return $customer_rs;
                    }
                }
            } else {
                $error = error_res();
                return $error;
            }
        } else if ($login_chk == 'user') {
            if ($user_id == $login_chk_id) {
                // user can see all customer in me
                $customer = DB_CustomerModel::where('parent', $user_id)
                    ->with('car')
                    ->with('color_car')
                    ->with('grade_car')
                    ->get();
                $customer_rs = loop_campaign($customer);
                return $customer_rs;
            } else {
                $error = error_res();
                return $error;
            }
        } else if ($login_chk == 'admin') {
            if ($user_id == $login_chk_id && $login_branch == '1') {
                // admin can see all customer in all branch
                $customer = DB_CustomerModel::with('car')
                    ->with('color_car')
                    ->with('grade_car')
                    ->get();
                $customer_rs = loop_campaign($customer);
                return $customer_rs;
            } else {
                // 2 = ตลาดไท 3 = วงเวียน
                $branch = [2, 3];
                $user_branch = User::whereIn('branch', $branch)
                    ->get();
                $user_branch_id = $user_branch->pluck('id');
                $customer = DB_CustomerModel::whereIn('parent', $user_branch_id)
                    ->with('car')
                    ->with('color_car')
                    ->with('grade_car')
                    ->get();
                $customer_rs = loop_campaign($customer);
                return $customer_rs;
            }
        } else {
            $error = error_res();
            return $error;
        }
    }

    public function store(Request $request)
    {
        $login_chk = $request->user();
        $login_chk = $login_chk->status;
        $login_id = $request->user();
        $login_id = $login_id->id;
        //decode 
        $login_id = json_decode($login_id);

        // ตรวจสอบสิทธิ์การเข้าถึง
        if ($login_chk == 'user') {
            $fields = $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'serial_no' => 'required',
                'car' => 'required',
                'grade_car' => 'required',
                'color_car' => 'required | integer',
                'campaign' => 'required',
                'text' => 'required',
                'deliver' => 'required | integer',
                'postal_number' => 'required',
                'parent' => 'required | integer',
                'status' => 'required',
                'read_page' => 'required',
                'datetime' => 'required',
            ]);
            $customer = DB_CustomerModel::create([
                'first_name' => $fields['first_name'],
                'last_name' => $fields['last_name'],
                'serial_no' => $fields['serial_no'],
                'car' => $fields['car'],
                'grade_car' => $fields['grade_car'],
                'color_car' => $fields['color_car'],
                'campaign' => $fields['campaign'],
                'text' => $fields['text'],
                'deliver' => $fields['deliver'],
                'postal_number' => $fields['postal_number'],
                'parent' => $login_id,
                'status' => $fields['status'],
                'read_page' => $fields['read_page'],
                'datetime' => $fields['datetime'],
            ]);
            //create tran
            $tran = DB_TranModel::create([
                'parent' => $customer->id,
                'detail' => 'create customer by api test',
                'datetime' => $fields['datetime'],
            ]);
            //response
            return response()->json([
                'message' => 'success',
                'data' => $customer,
                'tran' => $tran

            ], 201);
        } else {
            $error = error_res();
            return $error;
        }
    }

    //update customer
    public function update(Request $request, $id)
    {
        $login_chk = $request->user();
        $login_chk = $login_chk->status;

        //! check permission
        //TODO: Leader
        if ($login_chk == 'leader') {
            $fields = $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'serial_no' => 'required',
                'car' => 'required',
                'grade_car' => 'required',
                'color_car' => 'required | integer',
                'campaign' => 'required',
                'text' => 'required',
                'deliver' => 'required | integer',
                'postal_number' => 'required',
                'parent' => 'required | integer',
                'status' => 'required',
                'read_page' => 'required',
                'datetime' => 'required',
            ]);
            $customer = DB_CustomerModel::find($id);
            $customer->update([
                'first_name' => $fields['first_name'],
                'last_name' => $fields['last_name'],
                'serial_no' => $fields['serial_no'],
                'car' => $fields['car'],
                'grade_car' => $fields['grade_car'],
                'color_car' => $fields['color_car'],
                'campaign' => $fields['campaign'],
                'text' => $fields['text'],
                'deliver' => $fields['deliver'],
                'postal_number' => $fields['postal_number'],
                'parent' => $fields['parent'],
                'status' => $fields['status'],
                'read_page' => $fields['read_page'],
                'datetime' => $fields['datetime'],
            ]);
            return response()->json([
                'message' => 'success'
            ], 200);
        } else if ($login_chk == 'user') {
            //TODO: User
            $fields = $request->validate([
                'car' => 'required',
                'serial_no' => 'required',
                'grade_car' => 'required',
                'color_car' => 'required | integer',
                'campaign' => 'required',
            ]);
            $customer = DB_CustomerModel::find($id);
            $customer->update([
                'car' => $fields['car'],
                'serial_no' => $fields['serial_no'],
                'grade_car' => $fields['grade_car'],
                'color_car' => $fields['color_car'],
                'campaign' => $fields['campaign'],
            ]);
            return response()->json([
                'message' => 'success'
            ], 200);
        } else {
            $error = error_res();
            return $error;
        }
    }
}





//! Function
function loop_campaign($customer)
{
    foreach ($customer as $key => $value) {
        $campaign_de = $customer[$key]->campaign;
        $chk_arr = $campaign_de;
        if ($chk_arr == 0) {
            $customer[$key]->campaign = $campaign_de;
        } else if ($chk_arr == 'null') {
            $customer[$key]->campaign = $campaign_de;
        } else {
            $campaign = campaign_chk($campaign_de);
            $customer[$key]->campaign = $campaign;
        }
    }
    return $customer;
}
function campaign_chk($campaign)
{
    $campaign = json_decode($campaign);
    $campaign = DB_CampaignModel::whereIn('id', $campaign)->get();
    $campaign_id = [];
    foreach ($campaign as $key => $value) {
        $campaign_id[$key]['id'] = $value->id;
        $parent = $value->parent;
        if ($parent != 0) {
            $parent = DB_CampaignModel::where('id', $parent)->get();
            $val_campaign = $value->detail;
            $cam_pa2 = $parent[0]->detail;
            // เถ้า parent ไม่เท่ากับ 0 ให้เรียกข้อมูลแคมเปญจาก parent มาแสดง
            if ($parent[0]->parent != 0) {
                $campaign_id[$key]['campaign'] = $val_campaign;

                $parent_chk = $value->parent;
                while ($parent_chk != 0) {
                    $parent = DB_CampaignModel::where('id', $parent_chk)->get();
                    $parent_chk = $parent[0]->parent;
                    $cam_pa = $parent[0]->detail;
                    $campaign_id[$key]['campaign'] = $cam_pa . ' > ' . $campaign_id[$key]['campaign'];
                }
            }
        } else {
            $campaign_id[$key]['campaign'] = $value->detail;
        }
    }

    return $campaign_id;
}


function user_leader($user_group)
{
    $user_group = DB_user_groupModel::get();
    $user_leader = [];
    foreach ($user_group as $key => $value) {
        $user_leader[$key]['id'] = $value->id;
        $leader = json_decode($value->leader);
        $arr_leader = $leader[0] ?? '';
        $user_leader[$key]['leader'] = $arr_leader;
        $user_leader[$key]['detail'] = $value->detail;
        /* $user_leader[$key]['id'] = $value->id; */
        $user_leader[$key]['name'] = $value->name;
        $user_leader[$key]['detail'] = $value->detail;
        $leader = json_decode($value->leader);
        $arr_leader = $leader[0] ?? '';
        $user_leader[$key]['leader'] = $arr_leader;
        $user_leader[$key]['branch'] = $value->branch;
        $user_leader[$key]['department'] = $value->department;
        $user_leader[$key]['parent'] = $value->parent;
        $user_leader[$key]['status'] = $value->status;
        $user_leader[$key]['datetime'] = $value->datetime;
    }
    return $user_leader;
}

function error_res()
{
    return response()->json([
        'message' => 'error',
        'data' => 'unauthorized access '
    ], 401);
}