<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Activities;
use App\Models\Presention;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MediaController extends  Controller
{
    public function fetch_media(Request $request)
    {
        $request->validate([
            'filecontext' => 'required',
            'fileName' => 'required'
        ]);

        try {
            $file = Storage::disk('private')->url($request['filecontext'] . '/' . $request['fileName']);
            return response()->download($file, $request['fileName']);
        } catch (\Exception $th) {
            return response()->json([
                'responseData' => [
                    'msg' => 'failed to download file',
                    'errorlog' => $th->getMessage()
                ],
                'statusCode' => 500
            ], 500);
        }
    }

    public function upload_media(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,xlxs,xlx,docx,doc,csv,txt,png,gif,jpg,jpeg|max:2048',
            'filecontext' => 'required'
        ]);
        $file_context = $request['filecontext'];
        $documentId = $request['docId'];

        $fileName = Auth::user()->id . '_' . Auth::user()->num . '_' . $file_context . Auth::user()->name  . '.' . $request->file->getClientOriginalExtension();
        $filePath = $file_context . '/' . $fileName;
        $path = Storage::disk('private')->put($filePath, file_get_contents($request->file));
        $msg = '';
        $updatedData = [];
        // $path = Storage::disk('private')->url($path);

        switch ($file_context) {
            
            case 'proposal':
                Proposal::where('upload_by', Auth::user()->id)->update([
                    'fileName' => $fileName
                ]);
                $msg = 'success updating proposal file';
                $updatedData = Proposal::where('upload_by', Auth::user()->id)->first();
                break;
            
            case 'logbook':
                Activities::where('id', $documentId)->update([
                    'file_name' => $fileName
                ]);
                $msg = 'success updating logbook file';
                $updatedData = Activities::where('id', $documentId)->first();
                break;

            case 'presention':
                Presention::where('id', $documentId)->update([
                    'selfies_file_name' => $fileName
                ]);
                $msg = 'success updating presention file';
                $updatedData = Presention::where('id', $documentId)->first();
                break;

            default:
                $msg = 'nothing updated';
                return response()->json(['responseData' => ['msg' => $msg], 'statusCode' => '301'], 301);
                break;
        }

        return response()->json([
            'responseData' => [
                'msg' => $msg,
                'updatedData' => $updatedData
            ],
            'statusCode' => 200
        ], 200);
        
    }

    public function delete(Request $request)
    {
        $request->validate([
            'filecontext' => 'required'
        ]);

        $file_context = $request['filecontext'];
        $documentId = $request['docId']; 
        $updatedData = [];

        switch ($file_context) {
            
            case 'proposal':
                Proposal::where('upload_by', Auth::user()->id)->update([
                    'fileName' => ''
                ]);
                $msg = 'success deleting proposal file';
                $updatedData = Proposal::where('upload_by', Auth::user()->id)->first();
                break;
            
            case 'logbook':
                Activities::where('id', $documentId)->update([
                    'file_name' => ''
                ]);
                $msg = 'success deleting logbook file';
                $updatedData = Activities::where('id', $documentId)->first();
                break;

            case 'presention':
                Presention::where('id', $documentId)->update([
                    'selfies_file_name' => ''
                ]);
                $msg = 'success updating presention file';
                $updatedData = Presention::where('id', $documentId)->first();
                break;

            default:
                $msg = 'nothing delete';
                return response()->json(['responseData' => ['msg' => $msg], 'statusCode' => '301'], 301);
                break;
        }
        return response()->json([
            'responseData' => [
                'msg' => $msg,
                'updatedData' => $updatedData
            ],
            'statusCode' => 200
        ], 200);
    }
}
