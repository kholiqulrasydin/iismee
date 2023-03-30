<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Presention;

class PresentionController extends Controller
{
    public function fetch_all(){

        try {
            $data = Presention::where('id', Auth::user()->id)->get();
            return response()->json([
                'responseData' => [
                    'presentions' => $data
                ],
                'statusCode' => 200
            ], 200);
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

    public function add_presention(Request $request)
    {
        try {
            $request->validate([
                'isK3Used' => 'required',
                'todays_motivations' => 'required',
                'isLate' => 'required'
            ]);
            $presention = new Presention;
            $presention->mahasiswa_id = Auth::user()->id;
            $presention->isK3Used = $request['isK3Used'];
            $presention->todays_motivations = $request['todays_motivations'];
            $presention->isLate = $request['isLate'];
            $presention->save();

            return response()->json([
                'responseData' => [
                    'msg' => 'success presenting self'
                ],
                'statusCode' => 200
            ], 200);
        } catch (\Exception $th) {
            return response()->json([
                'responseData' => [
                    'msg' => 'failed to presenting self',
                    'errorlog' => $th->getMessage()
                ],
                'statusCode' => 500
            ], 500);
        }

    }

}
