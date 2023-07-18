@extends('layouts.master')

@section('head')
 <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
   integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
   crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
   integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
   crossorigin=""></script>
  <script src="https://unpkg.com/esri-leaflet@3.0.3/dist/esri-leaflet.js"
    integrity="sha512-kuYkbOFCV/SsxrpmaCRMEFmqU08n6vc+TfAVlIKjR1BPVgt75pmtU9nbQll+4M9PN2tmZSAgD1kGUCKL88CscA=="
    crossorigin=""></script>

  <!-- Load Esri Leaflet Vector from CDN -->
  <script src="https://unpkg.com/esri-leaflet-vector@3.1.1/dist/esri-leaflet-vector.js"
    integrity="sha512-7rLAors9em7cR3/583gZSvu1mxwPBUjWjdFJ000pc4Wpu+fq84lXF1l4dbG4ShiPQ4pSBUTb4e9xaO6xtMZIlA=="
    crossorigin=""></script>   
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.21.0/moment-with-locales.js"></script>
    <script src="//d3js.org/d3.v4.min.js"></script>
    <script src="https://d3js.org/topojson.v2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chroma-js/1.3.6/chroma.min.js"></script>   
@endsection
@section('content')

@include('layouts.loader')

<main class="bg-main">
    @include('layouts.sidebar')
    <section class="flex flex-col home-section">   
        <div class="grid grid-cols-1 flex-grow">
            <div id="map" class="min-h-screen">
                <div id="menu-title">
                    <div class="flex">
                        <img src="{{ asset('img/BNPB-white.png') }}" style="max-height:60px;">    
                        <img src="{{ asset('img/logo-white.png') }}" class="ml-2" style="max-height:60px;">
                    </div>
                    <div class="my-auto mt-2 pt-1 border-t">
                        <p id="map-title" class="text-white font-bold text-xl uppercase" style="text-shadow: 0 0 10px rgba(0, 0, 0,1);">Pengamatan Tinggi Muka Air di Indonesia</p>
                    </div>
                </div>
                <div id="menu-top-right">
                    <div class="b-4 bg-black bg-opacity-30 shadow-paleblue p-2">
                        <p class="text-white font-bold text-xl uppercase" style="text-shadow: 0 0 10px rgba(0, 0, 0,1);">Pengamatan Tinggi Muka Air</p>
                        <p class="text-white text-xs">Nama Stasiun:</p>
                        <div class="inline-block relative my-2">
                            <select id="station" class="text-xs appearance-none border border-gray-400 hover:border-gray-500 px-2 py-2 pr-8 shadow focus:outline-none focus:shadow-outline" onchange="changeStation(this);">
                                <option class="text-xs" disabled selected>Pilih Stasiun</option>
                                @foreach($stations as $station)
                                    <option class="text-xs" data-lat="{{ $station->lat }}"  data-lon="{{ $station->lon }}" value="{{ $station->station_id }}">{{ $station->name }}</option>
                                @endforeach
                            </select>
                            <br>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                        <p class="text-white text-xs">Tanggal:</p>
                        <div class="my-2">
                            <input type="date" id="date" class="text-xs appearance-none border border-gray-400 hover:border-gray-500 px-2 py-2 shadow focus:outline-none focus:shadow-outline" max='{{ \Carbon\Carbon::now()->format("Y-m-d") }}' value='{{ \Carbon\Carbon::now()->format("Y-m-d") }}'>
                        </div>
                        <button onclick="getObservations();" class="my-2 text-xs px-4 py-2 bg-yellow-300 font-bold uppercase">Lihat Data</button>  
                    </div>                    
                </div>
                <div id="map-chart">
                        <div id="chart" style="height:250px;"></div>
                </div>                
                <div id="map-legend" class="bg-black bg-opacity-25 p-2 mr-2 my-auto rounded-sm">
                        <p class="text-white text-xs">Ketersediaan Data: </p>
                        <p class="text-white text-xs"><span class="text-green-600">&#9679;</span> Data Terkini</p>
                        <p class="text-white text-xs"><span class="text-yellow-500">&#9679;</span> 7 hari terakhir</p>
                        <p class="text-white text-xs"><span class="text-red-800">&#9679;</span> <  7 hari terakhir</p>
                </div> 
                <div id="map-zoom">
                    <div>
                        <div id='in' class="bg-white bg-opacity-25 mb-1 flex hover:bg-red-dark cursor-pointer" style="width: 25px;height: 25px;"><p class="cursor-pointer m-auto text-white font-bold">+</p></div>
                        <div id='out' class="bg-white bg-opacity-25 flex hover:bg-red-dark cursor-pointer" style="width: 25px;height: 25px;"><p class="cursor-pointer m-auto text-white font-bold">-</p></div>                  
                    </div>
                </div>
            </div>

        </div>
    </section>
</main>

@endsection

@section('js')
<script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/highcharts-3d.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
<script src="https://code.highcharts.com/stock/modules/accessibility.js"></script>
<script type="text/javascript">
    Highcharts.theme = {
        "colors": ["#348f41","#7eb258","#bfd577","#fff89e","#f9bd6a","#ed8052","#d43d51"],
      "chart": {
        "style": {
          "fontFamily": "Roboto",
          "color": "#fff"
        }
      },
      "xAxis": {
        "gridLineWidth": 1,
        "labels":{
            "style":{
                "color": "rgba(255,255,255,.75)",
            }
        },
        "gridLineColor": "rgba(255,255,255,.075)",
        "lineColor": "rgba(255,255,255,.075)",
        "minorGridLineColor": "rgba(255,255,255,.075)",
        "tickColor": "rgba(255,255,255,.075)",
        "tickWidth": 1
      },
      "yAxis": {
        "gridLineColor": "rgba(255,255,255,.075)",
        "lineColor": "rgba(255,255,255,.075)",
        "minorGridLineColor": "rgba(255,255,255,.075)",
        "tickColor": "rgba(255,255,255,.075)",
        "tickWidth": 1
      },
      "legendBackgroundColor": "rgba(0,0,0, 0)",
      "background": "#505053",
      "dataLabelsColor": "#B0B0B3",
      "textColor": "#C0C0C0",
      "contrastTextColor": "#F0F0F3",
      "maskColor": "rgba(255,255,255,0.3)"
    }
    Highcharts.setOptions(Highcharts.theme);

    var storagePath = "{{ asset("") }}";
    var stationContainer;
    initSidebar();
    stations = {!! json_encode($stations) !!};
    initMap();
    getObservationStations();
    addMapLayer('menu-title','topleft');
    addMapLayer('menu-top-right','topright');
    addMapLayer('map-chart','bottomright');
    addMapLayer('map-legend','bottomleft');
    addMapLayer('map-zoom','bottomleft');

    document.getElementById("menu-observation").classList.add("bg-white");
    document.getElementById("menu-observation").childNodes[1].childNodes[1].classList.remove("text-white");
    document.getElementById("menu-observation").childNodes[1].childNodes[1].classList.add("text-orange-500");
    document.getElementById("menu-observation").childNodes[1].childNodes[3].classList.remove("text-white");
    document.getElementById("menu-observation").childNodes[1].childNodes[3].classList.add("text-orange-500");
    
</script>
@endsection