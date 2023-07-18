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
                        <p id="map-title" class="text-white font-bold text-xl uppercase" style="text-shadow: 0 0 10px rgba(0, 0, 0,1);">Pengamatan Satelit di Indonesia</p>
                        <p id="map-date" class="text-white" style="text-shadow: 0 0 10px rgba(0, 0, 0,1);">-</p>
                    </div>
                </div>

                <div id="menu-layer">
                    <input id="date" type='date' class="bg-paleblue-light bg-opacity-75 p-1 m-1 rounded-sm" required value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" onchange="getSatellite();">
                    <select id="time" class="bg-paleblue-light bg-opacity-75 p-1 m-1 rounded-sm" onfocus='this.size=10;' onblur='this.size=1;' onchange='this.size=1; this.blur();getSatellite();'>
                        @for($i = 0;$i<24;$i++)                        
                            <option value="{{ sprintf('%02d',$i); }}" class="text-center" {{ ($i == \Carbon\Carbon::now()->addHours(-1)->format('G') ? 'selected' : '') }}>{{ sprintf('%02d',$i); }}:00</option>
                        @endfor
                    </select>  
                </div>                
                <div id="menu-legend" class="bg-black bg-opacity-30 p-2">
                    <p class="text-white text-lg font-bold uppercase">Legenda:</p>
                    <img class="max-h-60" src="{{ asset('img/legend-radar.png') }}">
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

<script type="text/javascript">
    var layerData;
    var storagePath = "{{ asset("") }}";
    initSidebar();
    moment.locale('id');
    initMap();
    let layerStatic = L.esri.dynamicMapLayer({
        url: "https://inarisk1.bnpb.go.id:6443/arcgis/rest/services/Basemap/batas_administrasi/MapServer",
        opacity: 0.75
    }).addTo(map);    
    getSatellite();
    addMapLayer('menu-title','topleft');
    addMapLayer('menu-layer','bottomleft');
    addMapLayer('menu-legend','topright');
    addMapLayer('map-zoom','bottomright');

    document.getElementById("menu-satellite").classList.add("bg-white");
    document.getElementById("menu-satellite").childNodes[1].childNodes[1].classList.remove("text-white");
    document.getElementById("menu-satellite").childNodes[1].childNodes[1].classList.add("text-orange-500");
    document.getElementById("menu-satellite").childNodes[1].childNodes[3].classList.remove("text-white");
    document.getElementById("menu-satellite").childNodes[1].childNodes[3].classList.add("text-orange-500");
</script>
@endsection