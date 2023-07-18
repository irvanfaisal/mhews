<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\WeatherForecast;
use App\Models\LocationRegency;
use Stevebauman\Location\Facades\Location;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
class FilterWeather extends Component
{

    public $searchLocation;
    public $regencies;
    protected $listeners = ['getLocation'];

    public $readyToLoad = false;
 
    public function init()
    {
        $this->readyToLoad = true;
    }

    public function mount(){
        $locations = LocationRegency::all();
        $position = Location::get();
        if ($position->countryName == "Indonesia") {
            $locations = LocationRegency::all();
            foreach ($locations as $key => $location)
            {
              $a = $position->latitude - $location['lat'];
              $b = $position->longitude - $location['lon'];
              $distance = sqrt(($a**2) + ($b**2));
              $distances[$key] = $distance;
            }

            asort($distances);
            $closest = $locations[key($distances)];
        } else {
            $closest = LocationRegency::where('id',162)->first();
        }

        $this->searchLocation = $closest->id;
        $this->regencies = $locations;
    }

    public function getLocation($id)
    {
        $this->searchLocation = $id;
    }

    public function render()
    {
        $this->dispatchBrowserEvent('reload');
        $locationId = $this->searchLocation;
        $location = LocationRegency::whereId($locationId)->first();

        Carbon::setLocale('id');
        $date = Carbon::now()->format('Y-m-d H:00:00');
        $dataDaily = [];
        $dataHourly = [];
        for ($i=-1; $i < 7; $i++) {
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

        for ($i=0; $i < 7; $i++) {
            $dateDaily = Carbon::today()->addDays($i);
            $dataDaily[] = [
                "date" => $dateDaily,
                "rain" => $dataHourly->where('date','>=',$dateDaily->format('Y-m-d'))->where('date','<',$dateDaily->addDays(1)->format('Y-m-d'))->sum('rain'),
                "temperature" => $dataHourly->where('date','>=',$dateDaily->format('Y-m-d'))->where('date','<',$dateDaily->addDays(1)->format('Y-m-d'))->avg('temperature')
            ];
        }

        $forecast = collect($dataHourly)->where("date",'>=',Carbon::now()->format('Y-m-d H:00:00'))->where("date",'<',Carbon::now()->addHours(1)->format('Y-m-d H:00:00'))->first();
        if (!$forecast) {
            $forecast = new \stdClass();
            $forecast->date = Carbon::now()->format("Y-m-d H:00");
            $forecast->rain = NULL;
            $forecast->temperature = NULL;
        }
        $now = Carbon::now();
        $chartData = collect($dataHourly)->filter(function ($value, $key) {
            if(Carbon::parse($value->date)->isSameDay(Carbon::now())) {
                return $value;
            }            

        });

        $this->dispatchBrowserEvent('reload-chart', ['data' => $chartData,'location' => $location]);

        return view('livewire.filter-weather',[
            "daily" => $dataDaily,
            "hourly" => $dataHourly,
            "date" => $forecast->date,
            "currentForecast" => $forecast,
            "location" => $location
        ]);
    }
}
