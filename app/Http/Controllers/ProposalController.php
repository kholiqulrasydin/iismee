<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProposalController extends Controller
{
    public function fetch_data()
    {
        try {
            $data = Proposal::where('uploaded_by', Auth::user()->id)->first();
            return response()->json([
                'responseData' => [
                    'proposal' => $data
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

    public function store(Request $request)
    {
        try {
            $request->validate([
                'judul' => 'required',
                'tema' => 'required'
            ]);
            $proposal = new Proposal;
            $proposal->uploaded_by = Auth::user()->id;
            $proposal->judul = $request['judul'];
            $proposal->tema = $request['tema'];
            $proposal->save();

            return response()->json([
                'responseData' => [
                    'msg' => 'success storing proposal'
                ],
                'statusCode' => 200
            ], 200);
        } catch (\Exception $th) {
            return response()->json([
                'responseData' => [
                    'msg' => 'failed to storing proposal',
                    'errorlog' => $th->getMessage()
                ],
                'statusCode' => 500
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required',
                'judul' => 'required',
                'tema' => 'required'
            ]);
            $proposal = Proposal::find($request['id']);
            $proposal->judul = $request['judul'];
            $proposal->tema = $request['tema'];
            $proposal->save();

            return response()->json([
                'responseData' => [
                    'msg' => 'success updating proposal'
                ],
                'statusCode' => 200
            ], 200);
        } catch (\Exception $th) {
            return response()->json([
                'responseData' => [
                    'msg' => 'failed to updating proposal',
                    'errorlog' => $th->getMessage()
                ],
                'statusCode' => 500
            ], 500);
        }
    }
}
