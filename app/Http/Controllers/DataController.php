<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Models\Observation;
use App\Models\Station;
use App\Models\Dibi;
use App\Models\LocationRegency;
use App\Models\LocationProvince;
use App\Models\WeatherForecast;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;


class DataController extends Controller
{
    public function getHazard(Request $request)
    {   
        $date = \Carbon\Carbon::parse($request->date)->format('Y-m-d');
        $data = Http::withHeaders(['User-Agent' => $_SERVER['HTTP_USER_AGENT']])->post('https://tangkalbencana.id/api/getHazard', [
            'username' => 'admin',
            'apikey' => '69aa399d-3ff1-4724-861a-b5133aeded6b',
            'date' => $date,
        ])->getBody();
        $data = (json_decode($data,TRUE));
        unset($data['TANAH_LONGSOR']);
        unset($data['BANJIR_BANDANG']);
        $data = collect($data);

        return $data;
    }

    public function getDibi(Request $request)
    {   
        $year = $request->year;
        $month = $request->month;
        $hazard = $request->hazard;
        $data = Dibi::whereYear('date',$year)->when(($month != "all"), function ($query) use ($month) {
                    $query->whereMonth('date', $month);
                })->when(($hazard != "all"), function ($query) use ($hazard) {
                    $query->where('hazard', $hazard);
                })->get();
        return $data;
    }

    public function getDibiRegency(Request $request)
    {   
        $dibi = Dibi::where("regency_id",$request->id)->whereYear('date','>',Carbon::now()->addYears(-5))->get();
        $location = LocationRegency::where('regency_id',$request->id)->first();
        $data = [
            "dibi" => $dibi,
            "location" => $location
        ];
        return $data;
    }


    public function getDibiProvince(Request $request)
    {   
        $location = LocationProvince::where('province_id',$request->province)->first();
        $dibi = Dibi::where("province_name",$location->name)->get();
        $data = [
            "dibi" => $dibi,
            "location" => $location
        ];
        return $data;
    }


    public function getWindmap(Request $request)
    {

        $date = $request->date;
        $data = array();
        $url = 'http://167.205.106.70/web/wrf/wdir/' . $date . '.json';
        $json = @file_get_contents($url);
        if($json){
            $tmp = json_decode($json, true);            
            $tmp[0]['data'] = array_map('round',$tmp[0]['data']);
            $tmp[1]['data'] = array_map('round',$tmp[1]['data']);
            array_push($data, $tmp);       
        }

        return $data;
    }

    public function getObservations(Request $request)
    {

        $date = \Carbon\Carbon::parse($request->date)->format('Y-m-d');
        $station = $request->station;
        // return $date;

        $data = Http::withHeaders(['User-Agent' => $_SERVER['HTTP_USER_AGENT']])->post('https://tangkalbencana.id/api/getObservations', [
            'username' => 'admin',
            'apikey' => '69aa399d-3ff1-4724-861a-b5133aeded6b',
            'date' => $date,
            'station' => $station,
        ])->getBody();
        $data = (json_decode($data,TRUE));
        $sensor_1 = array();
        $sensor_2 = array();
        $sensor_3 = array();
        $residu_1 = array();
        $residu_2 = array();
        $residu_3 = array();
        for ($i=0; $i < sizeof($data['data']); $i++) {
            $dataDate = \Carbon\Carbon::parse($data['data'][$i]['date'], 'UTC');
            // return $data;
            if ($data['data'][$i]['sensor_1']){
                array_push($sensor_1, array(($dataDate->unix())*1000,floatval($data['data'][$i]['sensor_1'])));
            }
            if ($data['data'][$i]['sensor_2']){
                array_push($sensor_2, array(($dataDate->unix())*1000,floatval($data['data'][$i]['sensor_2'])));
            }
            if ($data['data'][$i]['sensor_3']){
                array_push($sensor_3, array(($dataDate->unix())*1000,floatval($data['data'][$i]['sensor_3'])));
            }
            if ($data['data'][$i]['residu_1']){
                array_push($residu_1, array(($dataDate->unix())*1000,floatval($data['data'][$i]['residu_1'])));
            }
            if ($data['data'][$i]['residu_2']){
                array_push($residu_2, array(($dataDate->unix())*1000,floatval($data['data'][$i]['residu_2'])));
            }
            if ($data['data'][$i]['residu_3']){
                array_push($residu_3, array(($dataDate->unix())*1000,floatval($data['data'][$i]['residu_3'])));
            }
        }
        $observations = [
            'sensor_1' => $sensor_1,
            'sensor_2' => $sensor_2,
            'sensor_3' => $sensor_3,
            'residu_1' => $residu_1,
            'residu_2' => $residu_2,
            'residu_3' => $residu_3
        ];

        return $observations;
    }

    public function getDetailForecast(Request $request)
    {        
        $data = Http::withHeaders(['User-Agent' => $_SERVER['HTTP_USER_AGENT']])->post('https://tangkalbencana.id/api/getDetailForecast', [
            'username' => 'admin',
            'apikey' => '69aa399d-3ff1-4724-861a-b5133aeded6b',
            'regency_id' => $request->id,
            'date' => $request->date,
        ]);

        return $data;
    }

    public function getHotspot(Request $request)
    {   
        $data = Http::withHeaders(['User-Agent' => $_SERVER['HTTP_USER_AGENT']])->post('https://tangkalbencana.id/api/getHotspot', [
            'username' => 'admin',
            'apikey' => '69aa399d-3ff1-4724-861a-b5133aeded6b',
            'date' => $request->date,
            'source' => $request->source,
        ]);

        return $data;
    }
}
