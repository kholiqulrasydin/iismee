<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Activities;
use App\Models\Presention;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{

    public function check_if_exists(){

        try {
            $presention = Presention::where('mahasiswa_id', Auth::user()->id)->orderBy('created_at', 'desc')->first();
            $data = Activities::where('presention_id', $presention['id'])->orderBy('created_at', 'desc')->first();
            if(!isset($presention) || !isset($data)){
                return response()->json([
                    'responseData' => [
                        'result' => false
                    ],
                    'statusCode' => 200
                ], 200);
            }else{
            $result = date("m.d.y", strtotime($data['created_at'])) == date("m.d.y");
                return response()->json([
                    'responseData' => [
                        'result' => $result
                    ],
                    'statusCode' => 200
                ], 200);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'responseData' => [
                    'errorlog' => $e->getMessage(),
                    'msg' => 'Failed to fetch data'
                ],
                'statusCode' => 500
            ], 500);
        }

    }

    public function delete(Request $req)
    {
        try {
            $req->validate([
                'docId' => 'required',
            ]);
            Activities::where('id', $req['docId'])->delete();
            return response()->json([
                'responseData' => [
                    'msg' => 'success deleting activity data'
                ],
                'statusCode' => 200
            ], 200);
        } catch (\Exception $th) {
            return response()->json([
                'responseData' => [
                    'msg' => 'failed to delete activity data',
                    'errorlog' => $th->getMessage()
                ],
                'statusCode' => 500
            ], 500);
        }
    }

    public function store(Request $req)
    {
        try {
            $req->validate([
                'details' => 'required',
                'problem' => 'required',
                'solution' => 'required',
                'is_with_employee' => 'required',
                'is_with_team' => 'required'
            ]);
            $data = Presention::where('mahasiswa_id', Auth::user()->id)->orderBy('created_at', 'desc')->first();
            $activity = new Activities();
            $activity->details = $req['details'];
            $activity->problem = $req['problem'];
            $activity->solution = $req['solution'];
            $activity->is_with_employee = $req['is_with_employee'];
            $activity->is_with_team = $req['is_with_team'];
            $activity->presention_id = $data['id'];
            $activity->save();
            $resultdata = Activities::where('presention_id', $data['id'])->first();
            return response()->json([
                'responseData' => [
                    'msg' => 'success storing new activity',
                    'activity' => $resultdata
                ],
                'statusCode' => 200
            ], 500);
        } catch (\Exception $th) {
            return response()->json([
                'responseData' => [
                    'msg' => 'failed to storing activity data',
                    'errorlog' => $th->getMessage()
                ],
                'statusCode' => 500
            ], 500);
        }
    }
}
