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
                <select id="data-source" class="block w-full px-4 rounded leading-tight appearance-none px-2 text-white uppercase font-bold text-right my-auto bg-transparent float-right rounded-none focus:border-none focus:outline-none cursor-pointer" onchange="getHazard()">
                    <option class="text-white uppercase font-bold bg-main" value="BMKG">BMKG</option>
                    <option class="text-white uppercase font-bold bg-main" value="WRF" selected>WRF</option>
                    <option class="text-white uppercase font-bold bg-main" value="GFS">GFS</option>
                </select>

                <div id="map-summary" class="grid gap-2 grid-cols-1 md:grid-cols-1">
                    <div class="border-2 border-opacity-80 border-yellow-300 shadow-paleblue bg-black bg-opacity-30">
                        <div class="flex justify-between">
                            <div class="my-auto px-1 flex">
                                <i class='text-yellow-300 text-2xl my-auto bx bxs-bolt mr-1'></i>
                                <p class="text-yellow-300 text-sm uppercase font-bold my-auto">Cuaca Ekstrem</p>
                            </div>
                            <div class="my-auto bg-animation-yellow bg-opacity-80 py-1 px-4">
                                <p class="text-black text-center font-bold count-hazard">-</p>
                                <p class="text-black text-xs text-center">Kab/Kota</p>
                            </div>
                        </div>
                    </div>
                    <div class="border-2 border-opacity-80 border-red-600 shadow-paleblue bg-black bg-opacity-30">
                        <div class="flex justify-between">
                            <div class="my-auto px-1 flex">
                                <i class='text-red-600 text-2xl my-auto bx bx-water mr-1'></i>
                                <p class="text-red-600 text-sm uppercase font-bold my-auto">Banjir</p>
                            </div>
                            <div class="my-auto bg-animation-red py-1 px-4">
                                <p class="text-white text-center font-bold count-hazard">-</p>
                                <p class="text-white text-xs text-center">Kab/Kota</p>
                            </div>
                        </div>
                    </div>                                           
                </div>                    
                <h1 id="title-date" class="text-white uppercase">-</h1>
                    <div id="map-zoom">
                        <div>
                            <div id='in' class="bg-white bg-opacity-30 mb-1 flex hover:bg-orange-500 cursor-pointer" style="width: 25px;height: 25px;"><p class="cursor-pointer m-auto text-white font-bold">+</p></div>
                            <div id='out' class="bg-white bg-opacity-30 flex hover:bg-orange-500 cursor-pointer" style="width: 25px;height: 25px;"><p class="cursor-pointer m-auto text-white font-bold">-</p></div>                  
                        </div>
                    </div>
                  
                    <div id="menu-layer" class="flex flex-col items-start">
                        <input id="date" type='date' class="bg-opacity-75 p-1 my-1 rounded-sm" required value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" onchange="getHazard();"> 
                        <div class="flex items-end">
                            <div id="legend-container" class="my-1">
                                <div id="legend-bmkg" class="bg-black bg-opacity-30 shadow-paleblue p-1 shadow-xs shadow-orange rounded-sm">
                                    <div>
                                        <p class="my-auto text-xs text-white">Matriks Dampak<br>Cuaca Ekstrem</p>
                                        <div class="flex">
                                            <div>
                                                <div class="flex">
                                                    <div style="pointer-events: none" class="button-bmkg impact-matrix flex impact-matrix-green"></div>
                                                    <button onclick="filterWeather('kategori2');this.classList.toggle('disable-matrix');" class="button-bmkg impact-matrix flex impact-matrix-yellow"><p class="m-auto font-bold text-xxs text-black">2</p></button>
                                                    <button onclick="filterWeather('kategori7');this.classList.toggle('disable-matrix');" class="button-bmkg impact-matrix flex impact-matrix-orange"><p class="m-auto font-bold text-xxs text-black">7</p></button>
                                                    <button onclick="filterWeather('kategori10');this.classList.toggle('disable-matrix');" class="button-bmkg impact-matrix flex impact-matrix-red"><p class="m-auto font-bold text-xxs text-black">10</p></button>
                                                </div>
                                                <div class="flex">
                                                    <div style="pointer-events: none" class="button-bmkg impact-matrix flex impact-matrix-green"></div>
                                                    <button onclick="filterWeather('kategori1');this.classList.toggle('disable-matrix');" class="button-bmkg impact-matrix flex impact-matrix-yellow"><p class="m-auto font-bold text-xxs text-black">1</p></button>
                                                    <button onclick="filterWeather('kategori6');this.classList.toggle('disable-matrix');" class="button-bmkg impact-matrix flex impact-matrix-orange"><p class="m-auto font-bold text-xxs text-black">6</p></button>
                                                    <button onclick="filterWeather('kategori9');this.classList.toggle('disable-matrix');" class="button-bmkg impact-matrix flex impact-matrix-orange"><p class="m-auto font-bold text-xxs text-black">9</p></button>
                                                </div>
                                                <div class="flex">
                                                    <div style="pointer-events: none" class="button-bmkg impact-matrix flex impact-matrix-green"></div>
                                                    <div style="pointer-events: none" class="button-bmkg impact-matrix flex impact-matrix-green"></div>
                                                    <button onclick="filterWeather('kategori4');this.classList.toggle('disable-matrix');" class="button-bmkg impact-matrix flex impact-matrix-yellow"><p class="m-auto font-bold text-xxs text-black">4</p></button>
                                                    <button onclick="filterWeather('kategori8');this.classList.toggle('disable-matrix');" class="button-bmkg impact-matrix flex impact-matrix-orange"><p class="m-auto font-bold text-xxs text-black">8</p></button>
                                                </div>
                                                <div class="flex">
                                                    <div style="pointer-events: none" class="button-bmkg impact-matrix flex impact-matrix-green"></div>
                                                    <div style="pointer-events: none" class="button-bmkg impact-matrix flex impact-matrix-green"></div>
                                                    <button onclick="filterWeather('kategori3');this.classList.toggle('disable-matrix');" class="button-bmkg impact-matrix flex impact-matrix-yellow"><p class="m-auto font-bold text-xxs text-black">3</p></button>
                                                    <button onclick="filterWeather('kategori5');this.classList.toggle('disable-matrix');" class="button-bmkg impact-matrix flex impact-matrix-yellow"><p class="m-auto font-bold text-xxs text-black">5</p></button>
                                                </div>
                                                <div>
                                                    <div class="flex mt-1" style="border-top: 1px solid #fff;">
                                                        <p class="m-auto font-bold p-0 text-xxs uppercase text-white">Severity</p>
                                                    </div>
                                                </div>            
                                            </div>    
                                            <div>
                                                <div class="flex ml-1" style="height: 80px;border-left: 1px solid #fff;">
                                                    <p class="m-auto font-bold p-0 text-xxs uppercase text-white" style="writing-mode: vertical-lr;">Likelihood</p>
                                                </div>
                                            </div>
                                        </div>                        
                                    </div>
                                </div> 
                                <div id="legend-wrf" class="bg-black bg-opacity-30 shadow-paleblue p-1 shadow-xs shadow-orange rounded-sm">
                                    <div>
                                        <p class="my-auto text-xs text-white">Matriks Dampak<br>Cuaca Ekstrem</p>
                                        <div class="flex">
                                            <div>
                                                <div class="flex">
                                                    <div style="pointer-events: none" class="button-wrf impact-matrix flex impact-matrix-green"></div>
                                                    <button onclick="filterWeather('kategori1');this.classList.toggle('disable-matrix');" class="button-wrf impact-matrix flex impact-matrix-yellow"><p class="m-auto font-bold text-xxs text-black">1</p></button>
                                                    <button onclick="filterWeather('kategori2');this.classList.toggle('disable-matrix');" class="button-wrf impact-matrix flex impact-matrix-orange"><p class="m-auto font-bold text-xxs text-black">2</p></button>
                                                    <button onclick="filterWeather('kategori3');this.classList.toggle('disable-matrix');" class="button-wrf impact-matrix flex impact-matrix-red"><p class="m-auto font-bold text-xxs text-black">3</p></button>
                                                </div>
                                                <div>
                                                    <div class="flex mt-1" style="border-top: 1px solid #fff;">
                                                        <p class="m-auto font-bold p-0 text-xxs uppercase text-white">Severity</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="legend-gfs" class="bg-black bg-opacity-30 shadow-paleblue p-1 shadow-xs shadow-orange rounded-sm">
                                    <div>
                                        <p class="my-auto text-xs text-white">Matriks Dampak<br>Cuaca Ekstrem</p>
                                        <div class="flex">
                                            <div>
                                                <div class="flex">
                                                    <div style="pointer-events: none" class="button-gfs impact-matrix flex impact-matrix-green"></div>
                                                    <button onclick="filterWeather('kategori1');this.classList.toggle('disable-matrix');" class="button-gfs impact-matrix flex impact-matrix-yellow"><p class="m-auto font-bold text-xxs text-black">1</p></button>
                                                    <button onclick="filterWeather('kategori2');this.classList.toggle('disable-matrix');" class="button-gfs impact-matrix flex impact-matrix-orange"><p class="m-auto font-bold text-xxs text-black">2</p></button>
                                                    <button onclick="filterWeather('kategori3');this.classList.toggle('disable-matrix');" class="button-gfs impact-matrix flex impact-matrix-red"><p class="m-auto font-bold text-xxs text-black">3</p></button>
                                                </div>
                                                <div>
                                                    <div class="flex mt-1" style="border-top: 1px solid #fff;">
                                                        <p class="m-auto font-bold p-0 text-xxs uppercase text-white">Severity</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>                        
                            <div class="inline-block bg-black bg-opacity-30 shadow-paleblue py-1 px-2 shadow-xs shadow-orange rounded-sm my-1 ml-2">
                                <div class="marker">
                                    <label class="checkbox text-white flex my-auto">Awas
                                        <input type="checkbox" name="variable-level" value="Awas" checked onchange="changeHazardLevel();">
                                        <span class="checkmark checkmark-red"></span>
                                    </label>
                                </div>                                              
                                <div class="marker">
                                    <label class="checkbox text-white">Siaga
                                        <input type="checkbox" name="variable-level" value="Siaga" checked onchange="changeHazardLevel();">
                                        <span class="checkmark checkmark-yellow"></span>
                                    </label>
                                </div>
                                <div class="marker">
                                    <label class="checkbox text-white">Waspada
                                        <input type="checkbox" name="variable-level" value="Waspada" checked onchange="changeHazardLevel();">
                                        <span class="checkmark checkmark-green"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="bg-black bg-opacity-25 p-2 ml-2 my-1 rounded-sm shadow-xs shadow-paleblue">
                                <p class="text-white font-bold mb-1 text-orange-500">Layer inaRISK</p>
                                <div class="flex">
                                    <div class="mr-2 flex">
                                        <input type="radio" name="layer-inarisk-hazard" class="my-auto" data-text="Cuaca Ekstrem" value='cuaca_ekstrim' onclick="getInariskHazard(this.value);">
                                        <label class="ml-1 my-auto text-white text-xs">Cuaca Ekstrem</label>
                                    </div>
                                    <div class="mr-2 flex">
                                        <input type="radio" name="layer-inarisk-hazard" class="my-auto" data-text="Banjir" checked value='banjir' onclick="getInariskHazard(this.value);">
                                        <label class="ml-1 my-auto text-white text-xs">Banjir</label>
                                    </div>
                                </div>
                                <hr class="my-1 border-gray-300 border-opacity-30">
                                <div class="flex">
                                    <div class="mr-2 flex">
                                        <input type="radio" name="layer-inarisk-type" class="my-auto" data-text="Bahaya" value='bahaya' onclick="getInariskType(this.value);">
                                        <label class="ml-1 my-auto text-white text-xs">Bahaya</label>
                                    </div>
                                    <div class="mr-2 flex">
                                        <input type="radio" name="layer-inarisk-type" class="my-auto" data-text="Kerentanan" value='kerentanan' onclick="getInariskType(this.value);">
                                        <label class="ml-1 my-auto text-white text-xs">Kerentanan</label>
                                    </div>
                                    <div class="mr-2 flex">
                                        <input type="radio" name="layer-inarisk-type" class="my-auto" data-text="Risiko" value='risiko' onclick="getInariskType(this.value);">
                                        <label class="ml-1 my-auto text-white text-xs">Risiko</label>
                                    </div>
                                    <div class="mr-2 flex">
                                        <input type="radio" name="layer-inarisk-type" class="my-auto" data-text="Tanpa Layer" checked value='no_layer' onclick="getInariskType(this.value);">
                                        <label class="ml-1 my-auto text-white text-xs">Tanpa Layer</label>
                                    </div>
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
                            <p id="map-title" class="text-white font-bold text-xl uppercase" style="text-shadow: 0 0 10px rgba(0, 0, 0,1);">Prediksi Bencana Hidrometeorologi di Indonesia</p>
                            <p id="map-date" class="text-white" style="text-shadow: 0 0 10px rgba(0, 0, 0,1);">-</p>
                        </div>
                    </div>
                <div id="info-summary" class="flex flex-col flex-nowrap bg-black bg-opacity-30 shadow-paleblue">
                    <div>
                        <nav class="tabs flex flex-col sm:flex-row">
                            <button data-target="panel-1" class="tab whitespace-nowrap text-xs text-white py-2 px-2 block hover:bg-orange-500 hover:bg-opacity-50 focus:outline-none flex-grow border-b">
                                Cuaca Ekstrem
                            </button>
                            <button data-target="panel-2" class="tab active bg-orange-500 whitespace-nowrap text-xs text-white py-2 px-2 block hover:bg-orange-500 hover:bg-opacity-50 focus:outline-none flex-grow border-b">
                                Banjir
                            </button>
                        </nav>
                    </div>
                    <div id="panels" class="flex-grow flex-nowrap">
                        <div class="panel-1 tab-content py-2 ml-3 bg-opacity-30 h-full">
                            <div class="h-80 overflow-y-auto pr-1 custom-scrollbar">
                                
                                <table id="table-hazard-1" class="table-auto text-white text-xs w-full">
                                    <thead>
                                        <tr>
                                            <th class="cursor-pointer p-1 bg-opacity-50 border-b border-white border-opacity-20 text-left" onclick="sortTable(0,'table-hazard-1')">Kabupaten/Kota <i class='my-auto mx-1 bx bxs-sort-alt'></i></th>
                                            <th class="cursor-pointer p-1 bg-opacity-50 border-b border-white border-opacity-20" onclick="sortTable(1,'table-hazard-1')">Status <i class='my-auto mx-1 bx bxs-sort-alt'></i></th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-hazard">

                                    </tbody>
                                </table>
                            </div>                        
                        </div>
                        <div class="panel-2 tab-content active py-2 ml-3 h-full bg-opacity-30">
                            <div class="h-80 overflow-y-auto pr-1 custom-scrollbar">
                                
                                <table id="table-hazard-2" class="table-auto text-white text-xs w-full">
                                    <thead>
                                        <tr>
                                            <th class="cursor-pointer p-1 bg-opacity-50 border-b border-white border-opacity-20 text-left" onclick="sortTable(0,'table-hazard-2')">Kabupaten/Kota <i class='my-auto mx-1 bx bxs-sort-alt'></i></th>
                                            <th class="cursor-pointer p-1 bg-opacity-50 border-b border-white border-opacity-20" onclick="sortTable(1,'table-hazard-2')">Status <i class='my-auto mx-1 bx bxs-sort-alt'></i></th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-hazard">

                                    </tbody>
                                </table>
                            </div>
                        </div>
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
    
    moment.locale('id');
    addMapLayer('menu-title','topleft');
    addMapLayer('map-summary','topleft');
    addMapLayer('info-summary','topright');
    addMapLayer('menu-layer','bottomleft');
    addMapLayer('map-zoom','bottomright');
    initSidebar();
    var hazardMarkerContainer;
    var weatherLayer;
    var layerData;
    var filterSignature = {
        kategori1: false,
        kategori2: false,
        kategori3: false,
        kategori4: false,
        kategori5: false,
        kategori6: false,
        kategori7: false,
        kategori8: false,
        kategori9: false,
        kategori10: false
    };
    var filterWRF = {
        kategori1: false,
        kategori2: false,
        kategori3: false
    };
    var filterGFS = {
        kategori1: false,
        kategori2: false,
        kategori3: false
    };    
    var dataTmp = [];
    initExtremeWeather();
    getHazard();
    var storagePath = "{{ asset("") }}";
    document.getElementById("menu-hydrometeorology").classList.add("bg-white");
    document.getElementById("menu-hydrometeorology").childNodes[1].childNodes[1].classList.remove("text-white");
    document.getElementById("menu-hydrometeorology").childNodes[1].childNodes[1].classList.add("text-orange-500");
    document.getElementById("menu-hydrometeorology").childNodes[1].childNodes[3].classList.remove("text-white");
    document.getElementById("menu-hydrometeorology").childNodes[1].childNodes[3].classList.add("text-orange-500");
    const tabs = document.querySelectorAll(".tabs");
    const tab = document.querySelectorAll(".tab");
    const panel = document.querySelectorAll(".tab-content");

    function onTabClick(event) {

      // deactivate existing active tabs and panel

      for (let i = 0; i < tab.length; i++) {
        tab[i].classList.remove("active");
        tab[i].classList.remove("bg-orange-500");
      }

      for (let i = 0; i < panel.length; i++) {
        panel[i].classList.remove("active");
      }

      // activate new tabs and panel
      event.target.classList.add('active');
      let classString = event.target.getAttribute('data-target');
      document.getElementById('panels').getElementsByClassName(classString)[0].classList.add("active")
      event.target.classList.add("bg-orange-500")
    }

    for (let i = 0; i < tab.length; i++) {
      tab[i].addEventListener('click', onTabClick, false);
    }

</script>
@endsection