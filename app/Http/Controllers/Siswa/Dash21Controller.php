<?php

namespace App\Http\Controllers;

use App\Models\Dash21;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class Dash21Controller extends Controller
{
    public static function middleware()
    {
        return ['auth:sanctum'];
    }
    //
    private function generateGeneralRule()
    {
        try
        {
            $rules=[];
            $fields=Storage::json('/data/config/kuesioner_fields.json');
            if(empty($fields['fields']))
                throw new \Exception('kuesioner_fields incorrectly formatted');
            foreach($fields['fields'] as $f => $conf)
                $rules=[...$rules, $f=>$conf['rules']];
            return $rules;
        }
        catch(\Exception $e)
        {
            return [];
        }
    }
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),$this->generateGeneralRule());
        if($validator->fails())
        {
            $response=
            [
                'message'=>'Inputs invalid',
                'errors'=>$validator->errors()
            ];
            return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        // get siswa id here
        $siswa=Siswa::select('nisn')->where('id_user', Auth::user()->id)->first();
        $nisn=$siswa->nisn;

        try
        {
            $data=($validator->validated());
            $storage_path='/data/kuesioners/dash21s';
            $filename=now()->format('dmY').'_'.$nisn.'.json';
            $content=json_encode($validator->validated());
            $filepath=$storage_path.$filename;

            Storage::put($content, $filepath);

            $api_response=Http::post(config('services.depression_detection_model.predict_depression'), $data);
            $depression_status_result=$api_response['data']['result'];
            $stored_data=
            [
                'kuesioner_url'=>$filepath,
                'id_siswa'=>$nisn,
                'result'=>$depression_status_result
            ];
            Dash21::create($stored_data);

            $response=
            [
                'message'=>'Kuisioner berhasil disimpan', 
                'data'=>['depressed'=>$depression_status_result]
            ];
            return response()->json($response, Response::HTTP_OK);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'message'=>'Gagal mengunggah kuesioner. Galat server. Coba lagi'
            ], 500);
        }
    }
}
