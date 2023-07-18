@extends('layouts.master')

@section('head')
 <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
   integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
   crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
   integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
   crossorigin=""></script>
  <!-- Load Esri Leaflet from CDN -->
  <script src="https://unpkg.com/esri-leaflet@3.0.3/dist/esri-leaflet.js"
    integrity="sha512-kuYkbOFCV/SsxrpmaCRMEFmqU08n6vc+TfAVlIKjR1BPVgt75pmtU9nbQll+4M9PN2tmZSAgD1kGUCKL88CscA=="
    crossorigin=""></script>

  <!-- Load Esri Leaflet Vector from CDN -->
  <script src="https://unpkg.com/esri-leaflet-vector@3.1.1/dist/esri-leaflet-vector.js"
    integrity="sha512-7rLAors9em7cR3/583gZSvu1mxwPBUjWjdFJ000pc4Wpu+fq84lXF1l4dbG4ShiPQ4pSBUTb4e9xaO6xtMZIlA=="
    crossorigin=""></script>   
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.21.0/moment-with-locales.js"></script>
@endsection
@section('content')


@include('layouts.loader')

<main class="bg-white">
    @include('layouts.sidebar')
    <section class="flex flex-col home-section">
        <div>
            <div id="map" class="min-h-screen">

                <div id="map-title" class="flex bg-orange-gradient p-2">
                    <img class="mx-auto" src="{{ asset('img/BNPB-white.png') }}" style="max-height:75px;">
                    <div class="my-auto ml-4">
                        <p class="text-white font-bold text-xl uppercase" style="text-shadow: 0 0 10px rgba(0, 0, 0,1);">Data Informasi Bencana Indonesia</p>
                    </div>
                </div>                
                <div id="map-zoom">
                    <div>
                        <div id='in' class="bg-white bg-opacity-30 mb-1 flex hover:bg-orange-500 cursor-pointer" style="width: 25px;height: 25px;"><p class="cursor-pointer m-auto text-white font-bold">+</p></div>
                        <div id='out' class="bg-white bg-opacity-30 flex hover:bg-orange-500 cursor-pointer" style="width: 25px;height: 25px;"><p class="cursor-pointer m-auto text-white font-bold">-</p></div>                  
                    </div>
                </div>
                <div id="menu-layer">
                    <select id="year" class="py-1 px-2" onchange="getDibi()">
                        @for ($i = 2022; $i >= 2008; $i--)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                    <select id="month" class="py-1 px-2" onchange="getDibi()">
                        <option selected value="all">Sepanjang Tahun</option>
                        <option value="1">Januari</option>
                        <option value="2">Februari</option>
                        <option value="3">Maret</option>
                        <option value="4">April</option>
                        <option value="5">Mei</option>
                        <option value="6">Juni</option>
                        <option value="7">Juli</option>
                        <option value="8">Agustus</option>
                        <option value="9">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>
                    </select>
                    <select id="hazard" class="py-1 px-2" onchange="getDibi()">
                        <option selected value="all">SEMUA BENCANA</option>
                        @foreach($hazards as $hazard)
                            <option value="{{ $hazard }}">{{ $hazard }}</option>
                        @endforeach
                    </select>
                </div>
                <div id="menu-top-left">
                    <div id="chart-top-left" style="max-width:400px;"></div>
                </div>
                <div id="menu-top-right">
                    
                </div>                
                  
            </div>
        </div>
    </section>


</main>
@endsection

@section('js')
<script type="text/javascript">
    initMap();
    addMapLayer('map-title','topleft');
    addMapLayer('menu-top-left','topleft');
    addMapLayer('menu-top-right','topright');
    addMapLayer('menu-layer','bottomleft');
    addMapLayer('map-zoom','bottomright');
    initSidebar();

    var storagePath = "{{ asset("") }}";
    document.getElementById("menu-hydrometeorology").classList.add("bg-white");
    document.getElementById("menu-hydrometeorology").childNodes[1].childNodes[1].classList.remove("text-white");
    document.getElementById("menu-hydrometeorology").childNodes[1].childNodes[1].classList.add("text-orange-500");
    document.getElementById("menu-hydrometeorology").childNodes[1].childNodes[3].classList.remove("text-white");
    document.getElementById("menu-hydrometeorology").childNodes[1].childNodes[3].classList.add("text-orange-500");

</script>
@endsection