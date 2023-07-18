<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;
use App\Models\Station;
use App\Models\Dibi;
use App\Models\WeatherForecast;
use App\Models\LocationRegency;
use Stevebauman\Location\Facades\Location;
use Spatie\Browsershot\Browsershot;
use Sheets;
use DB;
use Illuminate\Support\Facades\Http;

class PageController extends Controller
{
    public function index(Request $request)
    {   
       
        $ip = $request->ip(); 
        $ip = '162.159.24.227'; /* Static IP address */
        $currentUserInfo = Location::get($ip);
        $data = [];
        $date = \Carbon\Carbon::now()->format('Y-m-d');
        $hazardData = Http::withHeaders(['User-Agent' => $_SERVER['HTTP_USER_AGENT']])->post('https://tangkalbencana.id/api/getHazard', [
            'username' => 'admin',
            'apikey' => '69aa399d-3ff1-4724-861a-b5133aeded6b',
            'date' => $date,
        ])->getBody();
        $hazardData = (json_decode($hazardData,TRUE));
        unset($hazardData['TANAH_LONGSOR']);
        unset($hazardData['BANJIR_BANDANG']);
        $hazardData = collect($hazardData);

        $data["bmkg"] = Http::withHeaders(['User-Agent' => $_SERVER['HTTP_USER_AGENT']])->post('https://tangkalbencana.id/api/getAllEarthquake', [
            'username' => 'admin',
            'apikey' => '69aa399d-3ff1-4724-861a-b5133aeded6b',
        ])->getBody();
        $data["bmkg"] = json_decode($data["bmkg"],TRUE);
        $data["earthquakeLatest"] = collect($data["bmkg"])->sortByDesc('DateTime')->first();

        $data['hotspot'] = Http::withHeaders(['User-Agent' => $_SERVER['HTTP_USER_AGENT']])->post('https://tangkalbencana.id/api/getHotspot', [
            'username' => 'admin',
            'apikey' => '69aa399d-3ff1-4724-861a-b5133aeded6b',
            'date' => \Carbon\Carbon::now()->addDays(-1)->format('Y-m-d'),
            'source' => 'viirs',
        ])->getBody();
        $data['hotspot'] = collect(json_decode($data['hotspot']));

        $data['volcano'] = Http::withHeaders(['User-Agent' => $_SERVER['HTTP_USER_AGENT']])->post('https://tangkalbencana.id/api/getVolcano', [
            'username' => 'admin',
            'apikey' => '69aa399d-3ff1-4724-861a-b5133aeded6b',
        ])->getBody();        

        $data['volcano'] = collect(json_decode($data['volcano']));

        $lat = $currentUserInfo->latitude;
        $lon = $currentUserInfo->longitude;
            
        $nearest = DB::table("location_regencies")
            ->select("location_regencies.id"
                ,DB::raw("6371 * acos(cos(radians(" . $lat . ")) 
                * cos(radians(location_regencies.lat)) 
                * cos(radians(location_regencies.lon) - radians(" . $lon . ")) 
                + sin(radians(" .$lat. ")) 
                * sin(radians(location_regencies.lat))) AS distance"))
                ->groupBy("location_regencies.id")
                ->orderBy('distance', 'asc')->first();
                

        $location = LocationRegency::whereId($nearest->id)->first();
        Carbon::setLocale('id');
        $date = Carbon::now();
        $dataDaily = [];
        $dataHourly = [];
        for ($i=-1; $i < 2; $i++) {
            $dateDaily = Carbon::today()->addDays($i)->format('Y-m-d');
            $hourlyForecast = Http::withHeaders(['User-Agent' => $_SERVER['HTTP_USER_AGENT']])->post('https://tangkalbencana.id/api/getDetailForecast', [
                'username' => 'admin',
                'apikey' => '69aa399d-3ff1-4724-861a-b5133aeded6b',
                'date' => $dateDaily,
                'regency_id' => $location->id,
            ])->getBody();
            $hourlyForecast = collect(json_decode($hourlyForecast));
            $dataHourly = array_merge($dataHourly,$hourlyForecast['forecast']);
        }

        $dataHourly = collect($dataHourly)->map(function ($val) {
            $val->date = Carbon::createFromFormat('Y-m-d H:i:s', $val->date, 'UTC')->setTimezone('Asia/Jakarta')->format('Y-m-d H:i');
            return $val;
        });
        $dataHourly = collect($dataHourly)->where('date','>=',Carbon::now()->format('Y-m-d') )->values();

        for ($i=0; $i < 2; $i++) {
            $dateDaily = Carbon::today()->addDays($i);
            $dataDaily[] = [
                "date" => $dateDaily,
                "rain" => $dataHourly->where('date','>=',$dateDaily->format('Y-m-d'))->where('date','<',$dateDaily->addDays(1)->format('Y-m-d'))->sum('rain'),
                "temperature" => $dataHourly->where('date','>=',$dateDaily->format('Y-m-d'))->where('date','<',$dateDaily->addDays(1)->format('Y-m-d'))->avg('temperature')
            ];
        }
        $forecast = collect($dataHourly)->where("date",'>=',$date->format('Y-m-d H:00:00'))->where("date",'<',$date->addHours(1)->format('Y-m-d H:00:00'))->first();
        if (!$forecast) {
            $forecast = new \stdClass();
            $forecast->date = Carbon::now()->format("Y-m-d H:00");
            $forecast->rain = NULL;
            $forecast->temperature = NULL;
        }

        $weather = [
            "daily" => $dataDaily,
            "hourly" => $dataHourly,
            "date" => $forecast->date,
            "currentForecast" => $forecast,
            "location" => $location,
        ];
        return view('pages.index',compact('data','hazardData','weather'));
    }

    public function weather(Request $request)
    {   
        $searchbar = LocationRegency::All();
        return view('pages.weather',compact('searchbar'));
    }

    public function radar()
    {   
        return view('pages.radar');
    }

    public function satellite()
    {   
        return view('pages.satellite');
    }

    public function observation()
    {   
        $stations = Http::withHeaders(['User-Agent' => $_SERVER['HTTP_USER_AGENT']])->post('https://tangkalbencana.id/api/getStations', [
            'username' => 'admin',
            'apikey' => '69aa399d-3ff1-4724-861a-b5133aeded6b',
        ])->getBody();        

        $stations = collect(json_decode($stations));
        return view('pages.observation',compact('stations'));
    }

    public function history()
    {   
        $hazards = Dibi::select('hazard')->distinct()->get()->pluck('hazard');

        return view('pages.history',compact('hazards'));
    }

    public function hydrometeorology()
    {   
        return view('pages.hydrometeorology');
    }

    public function forestfire()
    {   
        return view('pages.forestfire');
    }  

    public function volcano()
    {   
        // $data = [];
        // if (File::exists(Storage::disk('local')->path('volcano/volcano_status.json'))) {
        //     $string = file_get_contents(Storage::disk('local')->path('volcano/volcano_status.json'));
        //     $tmp = json_decode($string, true);
        //     $data = $tmp;
        // }else{
        //     $data = [];
        // }
        // $data = collect($data);
        $client = new Client();
        $date = \Carbon\Carbon::now();
        $crawler = $client->request('GET', 'https://magma.esdm.go.id/');   
        $title = $crawler->filter('script')->each(function($node){
            // if(strpos($node->text(), 'var markersGunungApi') !== false){
            // } 
                return $node->html();
        });
        foreach ($title as $key => $value) {
            $string = explode("\n",$value);
            foreach($string as $t){
                if(strpos($t, 'var markersGunungApi') !== false){
                    $result = substr($t,strpos($t, 'var markersGunungApi')+23,-1);
                }             
            }
        }
        $data = json_decode($result,true);


        $activities = Http::withHeaders(['User-Agent' => $_SERVER['HTTP_USER_AGENT']])->post('https://tangkalbencana.id/api/getVolcanoActivity', [
            'username' => 'admin',
            'apikey' => '69aa399d-3ff1-4724-861a-b5133aeded6b',
        ])->getBody();
        $activities = collect(json_decode($activities));

        $eruptions = Http::withHeaders(['User-Agent' => $_SERVER['HTTP_USER_AGENT']])->post('https://tangkalbencana.id/api/getVolcanoEruption', [
            'username' => 'admin',
            'apikey' => '69aa399d-3ff1-4724-861a-b5133aeded6b',
        ])->getBody();
        $eruptions = collect(json_decode($eruptions));
        foreach($data as $key => $value){
            $data[$key]["activity"] = $activities->where("title",$value["ga_nama_gapi"])->first();
            $data[$key]["eruption"] = $eruptions->where("title",$value["ga_nama_gapi"])->first();
        }
        $data = collect($data);
    
        return view('pages.volcano',compact("data"));
    }    

    // public function test()
    // {   
    //     $client = new Client();
    //     $crawler = $client->request('GET', 'https://magma.esdm.go.id/');
    //     $a=[];
    //     foreach ($crawler as $domElement) {
    //         $a[] = ($domElement->nodeName);
    //     }
    //     return $a;
    // }        

    public function earthquake()
    {   
        $data = [];

        $data["bmkg"] = Http::withHeaders(['User-Agent' => $_SERVER['HTTP_USER_AGENT']])->post('https://tangkalbencana.id/api/getAllEarthquake', [
            'username' => 'admin',
            'apikey' => '69aa399d-3ff1-4724-861a-b5133aeded6b',
        ])->getBody();
        $data["bmkg"] = json_decode($data["bmkg"],TRUE);

        $data["earthquakeLatest"] = Http::withHeaders(['User-Agent' => $_SERVER['HTTP_USER_AGENT']])->post('https://tangkalbencana.id/api/getEarthquake', [
            'username' => 'admin',
            'apikey' => '69aa399d-3ff1-4724-861a-b5133aeded6b',
        ])->getBody();
        $data["earthquakeLatest"] = json_decode($data["earthquakeLatest"],TRUE);
        $data["usgs"] = json_decode(file_get_contents('https://earthquake.usgs.gov/earthquakes/feed/v1.0/summary/all_month.geojson'), true);

        return view('pages.earthquake',compact("data"));
    } 

    public function inarisk()
    {   
        return view('pages.inarisk');
    } 

    public function dibi()
    {   
        $hazards = Dibi::select('hazard')->distinct()->get()->pluck('hazard');
        return view('pages.dibi',compact('hazards'));
    } 

    public function earthquakeLatest()
    {   
        $url = 'https://data.bmkg.go.id/DataMKG/TEWS/autogempa.json';
        $file_name = basename($url);

        $data = json_decode(file_get_contents($url), true);

        return view('pages.earthquake_latest',compact("data"));
    }     

    public function testing(){
            $data = Http::withHeaders(['User-Agent' => $_SERVER['HTTP_USER_AGENT']])->post('https://tangkalbencana.id/api/getDibiRegency', [
                'username' => 'admin',
                'apikey' => '69aa399d-3ff1-4724-861a-b5133aeded6b',
                'regency_id' => "1101",
            ])->getBody();
            $data = collect(json_decode($data));
            return $data;
    }
}
