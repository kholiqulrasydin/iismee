<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Activities;
use App\Models\Presention;
use App\Models\Proposal;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use finfo;
use ParagonIE_Sodium_Core_Util as Uint8List;

class MediaController extends  Controller
{
    // function getFileExtensionFromUint8List(Uint8List $data): string {
    //     $finfo = new finfo(FILEINFO_MIME_TYPE);
    //     $mime = $finfo->buffer($data);
    
    //     $extension = pathinfo($filename, PATHINFO_EXTENSION);
    //     return $extension;
    // }

    // function base64ToUint8List(string $base64): Uint8List {
    //     $binary = base64_decode($base64);
    //     $bytes = unpack('C*', $binary);
    //     $uint8list = new Uint8List($bytes);
    //     return $uint8list;
    // }

    // function uint8ListToFile(Uint8List $uint8list, string $filename): bool {
    //     $binary = pack('C*', ...$uint8list->toList());
    //     return file_put_contents($filename, $binary) !== false;
    // }

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
        try {
            $request->validate([
                'file' => 'required',
                'filecontext' => 'required'
            ]);
            $file_context = $request['filecontext'];
            $documentId = $request['docId'];
            // $uint8ListFile = base64ToUint8List($request['file']);
            // $ext = getFileExtensionFromUint8List($uint8ListFile);
            $fileName = Auth::user()->id . '_' . Auth::user()->num . '_' . $file_context . '_' . Auth::user()->name . '_' . strtotime(date('Y-m-d H:i:s')) . '.' . $request->file('file')->getClientOriginalExtension();
            $filePath = $file_context . '/' . $fileName;
            Storage::disk('private')->put($filePath, file_get_contents($request->file('file')));
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
        } catch (\Exception $th) {
            return response()->json([
                'responseData' => [
                    'msg' => $th->getMessage()
                ],
                'statusCode' => 500
            ], 500);
        }
        
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
