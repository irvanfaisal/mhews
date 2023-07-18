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
    
    <script src="//d3js.org/d3.v4.min.js"></script>
    <script src="//npmcdn.com/geotiff@0.4.1/dist/geotiff.browserify.js"></script>
    <script src="https://unpkg.com/leaflet-canvaslayer-field@1.4.1/dist/leaflet.canvaslayer.field.js"></script>
    <script src="https://d3js.org/topojson.v2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chroma-js/1.3.6/chroma.min.js"></script>
@endsection
@section('content')


@include('layouts.loader')

<main class="bg-main">
    @include('layouts.sidebar')
    <section class="flex flex-col home-section">
        <div>
            <div id="map" class="min-h-screen">
                <div id="map-summary">
                    <div class="grid gap-2 grid-cols-4 mb-2">
                        <div class="border-2 bg-gray-800 bg-opacity-25 border-orange-500 shadow-paleblue rounded-sm">
                            <div class="flex justify-between">
                                <div class="my-auto px-1 flex">
                                    <i class='text-orange-500 text-2xl my-auto bx bxs-flame mr-1'></i>
                                    <p class="text-orange-500 text-sm uppercase font-bold my-auto">Total<br>Titik Panas</p>
                                </div>
                                <div class="my-auto bg-orange-500 py-2 px-4">
                                    <p class="text-black text-xl text-center font-bold count-hazard">-</p>
                                    <p class="text-black text-xs text-center">Titik</p>
                                </div>
                            </div>
                        </div>
                        <div class="border-2 bg-gray-800 bg-opacity-25 border-green-600 shadow-paleblue rounded-sm">
                            <div class="flex justify-between">
                                <div class="my-auto px-1 flex">
                                    <i class='text-green-600 text-2xl my-auto bx bxs-flame mr-1'></i>
                                    <p class="text-green-600 text-sm uppercase font-bold my-auto">Rendah</p>
                                </div>
                                <div class="my-auto bg-green-600 py-2 px-4">
                                    <p class="text-gray-100 text-xl text-center font-bold count-hazard">-</p>
                                    <p class="text-gray-100 text-xs text-center">Titik</p>
                                </div>
                            </div>
                        </div>
                        <div class="border-2 bg-gray-800 bg-opacity-25 border-yellow-400 shadow-paleblue rounded-sm">
                            <div class="flex justify-between">
                                <div class="my-auto px-1 flex">
                                    <i class='text-yellow-400 text-2xl my-auto bx bxs-flame mr-1'></i>
                                    <p class="text-yellow-400 text-sm uppercase font-bold my-auto">Sedang</p>
                                </div>
                                <div class="my-auto bg-yellow-400 py-2 px-4">
                                    <p class="text-black text-xl text-center font-bold count-hazard">-</p>
                                    <p class="text-black text-xs text-center">Titik</p>
                                </div>
                            </div>
                        </div>
                        <div class="border-2 bg-gray-800 bg-opacity-25 border-red-600 shadow-paleblue rounded-sm">
                            <div class="flex justify-between">
                                <div class="my-auto px-1 flex">
                                    <i class='text-red-600 text-2xl my-auto bx bxs-flame mr-1'></i>
                                    <p class="text-red-600 text-sm uppercase font-bold my-auto">Tinggi</p>
                                </div>
                                <div class="my-auto bg-animation-red py-2 px-4">
                                    <p class="text-gray-100 text-xl text-center font-bold count-hazard">-</p>
                                    <p class="text-gray-100 text-xs text-center">Titik</p>
                                </div>
                            </div>
                        </div>                                                
                    </div>                    
                </div>
                <div id="menu-top-left">
                    <div class="flex flex-col flex-nowrap bg-black bg-opacity-50 shadow-xs shadow-paleblue rounded-sm">
                        <div>
                            <nav class="tabs flex flex-col sm:flex-row">
                                <button data-target="panel-1" class="tab whitespace-nowrap text-xs text-gray-100 py-2 px-4 block hover:bg-yellow-600 hover:bg-opacity-25 focus:outline-none flex-grow border-b border-r border-yellow-600">
                                    Kabupaten/Kota
                                </button>
                                <button data-target="panel-2" class="tab active bg-yellow-600 whitespace-nowrap text-xs text-gray-100 py-2 px-4 block hover:bg-yellow-600 hover:bg-opacity-25 focus:outline-none flex-grow border-b border-yellow-600">
                                    Provinsi
                                </button>
                            </nav>
                        </div>
                        <div id="panels" class="flex-grow flex-nowrap">
                            <div class="panel-1 tab-content h-full">
                                <div class="p-2 bg-opacity-25">
                                    <div class="max-h-80 overflow-y-auto custom-scrollbar pr-1">                            
                                        <table id="table-hotspot-regency" class="table-auto text-gray-100 text-xs w-full relative">
                                            <thead>
                                                <tr>
                                                    <!-- <th class="p-1 bg-opacity-25 border-b border-white border-opacity-20">No</th> -->
                                                    <th class="sticky top-0 p-1 bg-yellow-600 border-b border-white border-opacity-20 text-left">Kabupaten/Kota</th>
                                                    <!-- <th class="p-1 bg-opacity-25 border-b border-white border-opacity-20 text-left">Provinsi</th> -->
                                                    <th class="sticky top-0 p-1 bg-yellow-600 border-b border-white border-opacity-20">Titik Panas</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-hotspot">
                                               
                                            </tbody>
                                        </table>
                                    </div>
                                </div> 
                            </div>
                            <div class="panel-2 active tab-content h-full">
                                <div class="p-2 bg-opacity-25">
                                    <div class=" max-h-80 overflow-y-auto custom-scrollbar pr-1">                            
                                        <table id="table-hotspot-province" class="table-auto text-gray-100 text-xs w-full relative">
                                            <thead>
                                                <tr>
                                                    <!-- <th class="p-1 bg-opacity-25 border-b border-white border-opacity-20">No</th> -->
                                                    <th class="sticky top-0 p-1 bg-yellow-600 border-b border-white border-opacity-20 text-left">Provinsi</th>
                                                    <th class="sticky top-0 p-1 bg-yellow-600 border-b border-white border-opacity-20">Titik Panas</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-hotspot">
                                            
                                            </tbody>
                                        </table>
                                    </div>
                                </div> 
                            </div>
                        </div>   
                    </div>                    
                </div>
                <div id="map-legend">
                    <div class="flex bg-black bg-opacity-25 p-2 rounded-sm">
                            <h1 class="text-gray-100 uppercase text-xs text-opacity-75 my-auto">Sumber Data Titik Panas:</h1>
                        <select id="data-source" class="ml-2 rounded leading-tight py-1 px-2 text-gray-100 uppercase font-bold my-auto bg-yellow-600 rounded-none focus:border-none focus:outline-none cursor-pointer" onchange="getHotspot()">
                            <option class="text-gray-100 uppercase font-bold bg-main" value="viirs" selected>VIIRS</option>
                            <option class="text-gray-100 uppercase font-bold bg-main" value="modis">MODIS</option>
                        </select>                    
                    </div>
                    <div class="mt-1">
                        <h5 class="text-gray-100 text-right font-bold mb-2">FDRS</h5>
                        <div class="scale">
                            <div class="indicator block text-xxs text-gray-100 text-opacity-60">Tinggi</div>
                            <div class="indicator block text-xxs text-gray-100 text-opacity-60">Sedang</div>
                            <div class="indicator block text-xxs text-gray-100 text-opacity-60">Rendah</div>
                            <div class="indicator block text-xxs text-gray-100 text-opacity-60">Aman</div>
                            <div id="gradient-bar" class="float-right"></div>
                        </div>                    
                    </div>
                </div>
                    <div id="map-zoom">
                        <div>
                            <div id='in' class="bg-yellow-600 bg-opacity-50 mb-1 flex hover:bg-yellow-600 cursor-pointer" style="width: 25px;height: 25px;"><p class="cursor-pointer m-auto text-gray-100 font-bold">+</p></div>
                            <div id='out' class="bg-yellow-600 bg-opacity-50 flex hover:bg-yellow-600 cursor-pointer" style="width: 25px;height: 25px;"><p class="cursor-pointer m-auto text-gray-100 font-bold">-</p></div>                  
                        </div>
                    </div>
                    <div id="menu-layer" class="flex flex-col justify-end items-end">
                        <div class="flex items-end">
                            <input id="date" type='date' class="bg-yellow-600 bg-opacity-75 p-1 m-1 rounded-sm" required value="{{ \Carbon\Carbon::now()->addDays(-1)->format('Y-m-d') }}" onchange="getHotspot();">
                            <div class="my-1 bg-black bg-opacity-25 py-1 px-2 shadow-xs shadow-paleblue rounded-sm">
                                <p class="my-1 text-gray-100">Tingkat Kepercayaan</p>
                                <div class="flex flex-col justify-end items-end">
                                    <div class="marker">
                                        <label class="checkbox text-gray-100 flex my-auto">
                                            <input type="checkbox" name="variable-level" value="Tinggi" checked onchange="changeHotspotLevel();">
                                            <span class="checkmark checkmark-red"></span> Tinggi
                                        </label>
                                    </div>
                                    <div class="marker">
                                        <label class="checkbox text-gray-100">
                                            <input type="checkbox" name="variable-level" value="Sedang" checked onchange="changeHotspotLevel();">
                                            <span class="checkmark checkmark-yellow"></span> Sedang
                                        </label>
                                    </div>
                                    <div class="marker">
                                        <label class="checkbox text-gray-100">
                                            <input type="checkbox" name="variable-level" value="Rendah" checked onchange="changeHotspotLevel();">
                                            <span class="checkmark checkmark-green"></span> Rendah
                                        </label>
                                    </div>
                                </div>
                            </div>       
                        </div>                        
                        <div class="bg-black bg-opacity-25 p-2 my-auto rounded-sm shadow-xs shadow-paleblue">
                            <div class="flex">
                                <div class="flex justify-end">
                                    <input id="check-fdrs" type="checkbox" name="menu-layer" class="my-auto" data-text="FDRS" value='FDRS' onchange="getFDRS();">
                                    <label class="ml-1 my-auto text-gray-100 text-xs text-right" for="timestep3">Fire Danger Rating System (FDRS)</label>
                                </div>
                            </div>
                        </div> 
                        <div class="bg-black bg-opacity-25 p-2 my-1 rounded-sm shadow-xs shadow-paleblue text-right">
                            <p class="text-white font-bold mr-2 mb-1 text-orange-500">Layer inaRISK</p>
                            <div class="flex items-end justify-end">
                                <div class="mr-2 flex">
                                    <input type="radio" name="layer-inarisk-hazard" class="my-auto" data-text="Kebakaran Hutan dan Lahan" checked value='kebakaran_hutan_dan_lahan' onclick="getInariskHazard(this.value);">
                                    <label class="ml-1 my-auto text-white text-xs">Kebakaran Hutan & Lahan</label>
                                </div>
                            </div>
                            <hr class="my-1 border-gray-300 border-opacity-30">
                            <div class="flex justify-end px-2">
                                <div class="ml-2 flex">
                                    <input type="radio" name="layer-inarisk-type" class="my-auto" data-text="Bahaya" value='bahaya' onclick="getInariskType(this.value);">
                                    <label class="ml-1 my-auto text-gray-100 text-xs">Bahaya</label>
                                </div>
                                <div class="ml-2 flex">
                                    <input type="radio" name="layer-inarisk-type" class="my-auto" data-text="Kerentanan" value='kerentanan' onclick="getInariskType(this.value);">
                                    <label class="ml-1 my-auto text-gray-100 text-xs">Kerentanan</label>
                                </div>
                                <div class="ml-2 flex">
                                    <input type="radio" name="layer-inarisk-type" class="my-auto" data-text="Risiko" value='risiko' onclick="getInariskType(this.value);">
                                    <label class="ml-1 my-auto text-gray-100 text-xs">Risiko</label>
                                </div>
                                <div class="ml-2 flex">
                                    <input type="radio" name="layer-inarisk-type" class="my-auto" data-text="Tanpa Layer" checked value='no_layer' onclick="getInariskType(this.value);">
                                    <label class="ml-1 my-auto text-gray-100 text-xs">Tanpa Layer</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="menu-title">
                        <div class="flex">
                            <img src="{{ asset('img/BNPB-white.png') }}" style="max-height:60px;">    
                            <img src="{{ asset('img/logo-white.png') }}" class="ml-2" style="max-height:60px;">
                        </div>
                        <div class="my-auto mt-2 pt-1 border-t">
                            <p id="map-title" class="text-gray-100 font-bold text-xl uppercase" style="text-shadow: 0 0 10px rgba(0, 0, 0,1);">Pengamatan Titik Panas di Indonesia</p>
                            <p id="title-date" class="text-gray-100" style="text-shadow: 0 0 10px rgba(0, 0, 0,1);">-</p>
                        </div>
                    </div>
            </div>
        </div>
    </section>


</main>
@endsection

@section('js')

<script type="text/javascript">
    initMap();
    addMapLayer('menu-title','topleft');
    addMapLayer('menu-top-left','topleft');
    addMapLayer('map-summary','bottomleft');
    addMapLayer('menu-layer','bottomright');
    addMapLayer('map-zoom','bottomright');
    addMapLayer('map-legend','topright');

    document.getElementById("menu-forestfire").classList.add("bg-white");
    document.getElementById("menu-forestfire").childNodes[1].childNodes[1].classList.remove("text-white");
    document.getElementById("menu-forestfire").childNodes[1].childNodes[1].classList.add("text-orange-500");
    document.getElementById("menu-forestfire").childNodes[1].childNodes[3].classList.remove("text-white");
    document.getElementById("menu-forestfire").childNodes[1].childNodes[3].classList.add("text-orange-500");    
    initSidebar();
    
    moment.locale('id');
    var hazardMarkerContainer;
    var layerData;
    var storagePath = "{{ asset("") }}";
    getHotspot();
    
    var gradientBar = document.getElementById("gradient-bar");
    var barHeight = gradientBar.offsetHeight;
    var indicators = document.getElementsByClassName("indicator");
    var numberOfIndicators = indicators.length;
    var counter = 0;
    for (var x = 0; x < numberOfIndicators; x++) {
        indicators[x].style.top = counter + "px";
        counter += barHeight / numberOfIndicators + 3;
    }

    const tabs = document.querySelectorAll(".tabs");
    const tab = document.querySelectorAll(".tab");
    const panel = document.querySelectorAll(".tab-content");

    function onTabClick(event) {

      // deactivate existing active tabs and panel

      for (let i = 0; i < tab.length; i++) {
        tab[i].classList.remove("active");
        tab[i].classList.remove("bg-yellow-600");
      }

      for (let i = 0; i < panel.length; i++) {
        panel[i].classList.remove("active");
      }

      // activate new tabs and panel
      event.target.classList.add('active');
      let classString = event.target.getAttribute('data-target');
      document.getElementById('panels').getElementsByClassName(classString)[0].classList.add("active")
      event.target.classList.add("bg-yellow-600");
    }

    for (let i = 0; i < tab.length; i++) {
      tab[i].addEventListener('click', onTabClick, false);
    }
</script>
@endsection