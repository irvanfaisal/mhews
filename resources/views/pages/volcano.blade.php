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
    @livewireStyles
@endsection
@section('content')


@include('layouts.loader')

<main class="bg-main">
    @include('layouts.sidebar')
    <section class="flex flex-col home-section">


        <div class="grid grid-cols-1 md:grid-cols-1 flex-grow">
            <div id="map" class="min-h-screen">
                <div id="map-title">
                    <div class="flex">
                        <img src="{{ asset('img/BNPB-white.png') }}" style="max-height:60px;">    
                        <img src="{{ asset('img/logo-white.png') }}" class="ml-2" style="max-height:60px;">
                    </div>
                    <div class="my-auto mt-2 pt-1 border-t">
                        <h1 class="text-white text-2xl uppercase font-bold">Pengamatan Gunung Api</h1>
                        <p id="map-date" class="text-white" style="text-shadow: 0 0 10px rgba(0, 0, 0,1);">{{ \Carbon\Carbon::now()->translatedFormat("d F Y")}}</p>
                    </div>
                </div>                

                <div id="map-summary" class="grid gap-4 grid-cols-1 md:grid-cols-4 mb-2">
                    <div class="border-2 bg-black bg-opacity-30 border-green-500 shadow-paleblue">
                        <div class="flex justify-between">
                            <div class="my-auto px-1 flex">
                                <i class='text-green-500 text-4xl my-auto bx bxs-landscape mr-1'></i>
                                <p class="text-green-500 text-sm uppercase font-bold my-auto">Normal</p>
                            </div>
                            <div class="my-auto bg-green-500 py-2 px-4">
                                <p class="text-black text-xl text-center font-bold count-hazard">{{ $data->where("ga_status",1)->count() }}</p>
                                <p class="text-black text-xs text-center">Gunung</p>
                            </div>
                        </div>
                    </div>
                    <div class="border-2 bg-black bg-opacity-30 border-yellow-300 shadow-paleblue">
                        <div class="flex justify-between">
                            <div class="my-auto px-1 flex">
                                <i class='text-yellow-300 text-4xl my-auto bx bxs-landscape mr-1'></i>
                                <p class="text-yellow-300 text-sm uppercase font-bold my-auto">Waspada</p>
                            </div>
                            <div class="my-auto bg-yellow-300 py-2 px-4">
                                <p class="text-black text-xl text-center font-bold count-hazard">{{ $data->where("ga_status",2)->count() }}</p>
                                <p class="text-black text-xs text-center">Gunung</p>
                            </div>
                        </div>
                    </div>
                    <div class="border-2 bg-black bg-opacity-30 border-orange-500 shadow-paleblue">
                        <div class="flex justify-between">
                            <div class="my-auto px-1 flex">
                                <i class='text-orange-500 text-4xl my-auto bx bxs-landscape mr-1'></i>
                                <p class="text-orange-500 text-sm uppercase font-bold my-auto">Siaga</p>
                            </div>
                            <div class="my-auto bg-orange-500 py-2 px-4">
                                <p class="text-black text-xl text-center font-bold count-hazard">{{ $data->where("ga_status",3)->count() }}</p>
                                <p class="text-black text-xs text-center">Gunung</p>
                            </div>
                        </div>
                    </div>
                    <div class="border-2 bg-black bg-opacity-30 border-red-600 shadow-paleblue">
                        <div class="flex justify-between">
                            <div class="my-auto px-1 flex">
                                <i class='text-red-600 text-4xl my-auto bx bxs-landscape mr-1'></i>
                                <p class="text-red-600 text-sm uppercase font-bold my-auto">Awas</p>
                            </div>
                            <div class="my-auto bg-red-600 py-2 px-4">
                                <p class="text-white text-xl text-center font-bold count-hazard">{{ $data->where("ga_status",4)->count() }}</p>
                                <p class="text-white text-xs text-center">Gunung</p>
                            </div>
                        </div>
                    </div>                                                
                </div>
                <div id="map-zoom">
                    <div>
                        <div id='in' class="bg-white bg-opacity-30 mb-1 flex hover:bg-orange-500 cursor-pointer" style="width: 25px;height: 25px;"><p class="cursor-pointer m-auto text-white font-bold">+</p></div>
                        <div id='out' class="bg-white bg-opacity-30 flex hover:bg-orange-500 cursor-pointer" style="width: 25px;height: 25px;"><p class="cursor-pointer m-auto text-white font-bold">-</p></div>                  
                    </div>
                </div>
                <div id="menu-layer" class="flex items-end">
                    <div class="inline-block bg-black bg-opacity-30 p-2 shadow-xs shadow-paleblue rounded-sm">
                        <p class="text-white text-xs mb-1">Tingkat Aktivitas: </p>
                        <p class="text-white text-xs" for="magnitude1"><span class="text-red-700">&#9679;</span> Level IV (Awas)</p>
                        <p class="text-white text-xs" for="magnitude2"><span class="text-yellow-600">&#9679;</span> Level III (Siaga)</p>
                        <p class="text-white text-xs" for="magnitude3"><span class="text-yellow-300">&#9679;</span> Level II (Waspada)</p>
                        <p class="text-white text-xs" for="magnitude4"><span class="text-green-500">&#9679;</span> Level I (Normal)</p>
                    </div>

                    <div class="bg-black bg-opacity-25 p-2 ml-2 my-1 rounded-sm shadow-xs shadow-paleblue">
                        <p class="text-white font-bold mb-1 text-orange-500">Layer inaRISK</p>
                        <div class="flex">
                            <div class="mr-2 flex">
                                <input type="radio" name="layer-inarisk-hazard" class="my-auto" data-text="Banjir" checked value='letusan_gunungapi' onclick="getInariskHazard(this.value);">
                                <label class="ml-1 my-auto text-white text-xs">Letusan Gunung Api</label>
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
                    <div class="bg-black bg-opacity-30 p-2 mt-2 ml-2 rounded-sm shadow-xs shadow-paleblue">
                        <div class="flex">
                            <div class="mr-2 flex">
                                <input id="check-krb" checked type="checkbox" name="menu-layer" class="my-auto" onchange="getKRB();">
                                <label class="ml-1 my-auto text-white text-xs">Kawasan Risiko Bencana (KRB)</label>
                            </div>
                        </div>
                    </div> 

                </div>

                <div id="menu-top-left" class="bg-black bg-opacity-30" style="max-width: 300px;">
                    <div>
                        <nav class="tabs-volcano flex flex-col sm:flex-row">
                            <button data-target="panel-volcano-1" class="tab-volcano active bg-orange-500 whitespace-nowrap text-xs text-white py-2 px-4 block hover:bg-orange-500 hover:bg-opacity-50 focus:outline-none flex-grow border-b border-r border-paleblue">
                                Awas
                            </button>
                            <button data-target="panel-volcano-2" class="tab-volcano whitespace-nowrap text-xs text-white py-2 px-4 block hover:bg-orange-500 hover:bg-opacity-50 focus:outline-none flex-grow border-b border-r border-paleblue">
                                Siaga
                            </button>
                            <button data-target="panel-volcano-3" class="tab-volcano whitespace-nowrap text-xs text-white py-2 px-4 block hover:bg-orange-500 hover:bg-opacity-50 focus:outline-none flex-grow border-b border-r border-paleblue">
                                Waspada
                            </button>
                            <button data-target="panel-volcano-4" class="tab-volcano whitespace-nowrap text-xs text-white py-2 px-4 block hover:bg-orange-500 hover:bg-opacity-50 focus:outline-none flex-grow border-b border-paleblue">
                                Normal
                            </button>
                        </nav>
                    </div>
                    <div id="panels-volcano">
                        <div class="panel-volcano-1 tab-volcano-content p-2 active">
                            <div class="max-h-56 overflow-y-auto pr-1 custom-scrollbar">                                    
                                <table id="table-volcano-1" class="table-auto text-white text-xs w-full">
                                    <thead>
                                        <tr>
                                            <!-- <th class="cursor-pointer p-1 bg-orange-500 bg-opacity-50 border-b border-white border-opacity-20">No</th> -->
                                            <th class="cursor-pointer p-1 bg-orange-500 bg-opacity-50 border-b border-white border-opacity-20 text-left" onclick="sortTable(0,'table-volcano-1')">Nama</th>
                                            <!-- <th class="cursor-pointer p-1 bg-orange-500 bg-opacity-50 border-b border-white border-opacity-20 text-left" onclick="sortTable(1,'table-volcano-1')">Provinsi</th> -->
                                            <th class="cursor-pointer p-1 bg-orange-500 bg-opacity-50 border-b border-white border-opacity-20 text-left" onclick="sortTable(2,'table-volcano-1')">Kabupaten/Kota</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-volcano">
                                        @if($data->where("ga_status",4)->count() == 0)
                                            <tr>
                                                <td colspan=3 class="p-1 border-b border-white border-opacity-20 text-center">Tidak ada gunung api dengan Status Awas</td>
                                            </tr>
                                        @else
                                            @foreach($data->where("ga_status",4) as $row)
                                                <tr>
                                                    <td class="p-1 border-b border-white border-opacity-20 whitespace-nowrap">{{ $row['ga_nama_gapi'] }}</td>
                                                    <!-- <td class="p-1 border-b border-white border-opacity-20">{{ $row['ga_prov_gapi'] }}</td> -->
                                                    <td class="p-1 border-b border-white border-opacity-20">{{ $row['ga_kab_gapi'] }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="panel-volcano-2 tab-volcano-content p-2">
                            <div class="max-h-56 overflow-y-auto pr-1 custom-scrollbar">                                    
                                <table id="table-volcano-2" class="table-auto text-white text-xs w-full">
                                    <thead>
                                        <tr>
                                            <!-- <th class="cursor-pointer p-1 bg-orange-500 bg-opacity-50 border-b border-white border-opacity-20">No</th> -->
                                            <th class="cursor-pointer p-1 bg-orange-500 bg-opacity-50 border-b border-white border-opacity-20 text-left" onclick="sortTable(0,'table-volcano-2')">Nama</th>
                                            <!-- <th class="cursor-pointer p-1 bg-orange-500 bg-opacity-50 border-b border-white border-opacity-20 text-left" onclick="sortTable(1,'table-volcano-2')">Provinsi</th> -->
                                            <th class="cursor-pointer p-1 bg-orange-500 bg-opacity-50 border-b border-white border-opacity-20 text-left" onclick="sortTable(2,'table-volcano-2')">Kabupaten/Kota</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-volcano">
                                        @if($data->where("ga_status",3)->count() == 0)
                                            <tr>
                                                <td colspan=3 class="p-1 border-b border-white border-opacity-20 text-center">Tidak ada gunung api dengan Status Siaga</td>
                                            </tr>
                                        @else
                                            @foreach($data->where("ga_status",3) as $row)
                                                <tr>
                                                    <td class="p-1 border-b border-white border-opacity-20 whitespace-nowrap">{{ $row['ga_nama_gapi'] }}</td>
                                                    <!-- <td class="p-1 border-b border-white border-opacity-20">{{ $row['ga_prov_gapi'] }}</td> -->
                                                    <td class="p-1 border-b border-white border-opacity-20">{{ $row['ga_kab_gapi'] }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="panel-volcano-3 tab-volcano-content p-2">
                            <div class="max-h-56 overflow-y-auto pr-1 custom-scrollbar">                                    
                                <table id="table-volcano-3" class="table-auto text-white text-xs w-full">
                                    <thead>
                                        <tr>
                                            <!-- <th class="cursor-pointer p-1 bg-orange-500 bg-opacity-50 border-b border-white border-opacity-20">No</th> -->
                                            <th class="cursor-pointer p-1 bg-orange-500 bg-opacity-50 border-b border-white border-opacity-20 text-left" onclick="sortTable(0,'table-volcano-3')">Nama</th>
                                            <!-- <th class="cursor-pointer p-1 bg-orange-500 bg-opacity-50 border-b border-white border-opacity-20 text-left" onclick="sortTable(1,'table-volcano-3')">Provinsi</th> -->
                                            <th class="cursor-pointer p-1 bg-orange-500 bg-opacity-50 border-b border-white border-opacity-20 text-left" onclick="sortTable(2,'table-volcano-3')">Kabupaten/Kota</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-volcano">
                                        @if($data->where("ga_status",2)->count() == 0)
                                            <tr>
                                                <td colspan=3 class="p-1 border-b border-white border-opacity-20 text-center">Tidak ada gunung api dengan Status Waspada</td>
                                            </tr>
                                        @else
                                            @foreach($data->where("ga_status",2) as $row)
                                                <tr>
                                                    <td class="p-1 border-b border-white border-opacity-20 whitespace-nowrap">{{ $row['ga_nama_gapi'] }}</td>
                                                    <!-- <td class="p-1 border-b border-white border-opacity-20">{{ $row['ga_prov_gapi'] }}</td> -->
                                                    <td class="p-1 border-b border-white border-opacity-20">{{ $row['ga_kab_gapi'] }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="panel-volcano-4 tab-volcano-content p-2">
                            <div class="max-h-56 overflow-y-auto pr-1 custom-scrollbar">                                    
                                <table id="table-volcano-4" class="table-auto text-white text-xs w-full">
                                    <thead>
                                        <tr>
                                            <!-- <th class="cursor-pointer p-1 bg-orange-500 bg-opacity-50 border-b border-white border-opacity-20">No</th> -->
                                            <th class="cursor-pointer p-1 bg-orange-500 bg-opacity-50 border-b border-white border-opacity-20 text-left" onclick="sortTable(0,'table-volcano-4')">Nama</th>
                                            <!-- <th class="cursor-pointer p-1 bg-orange-500 bg-opacity-50 border-b border-white border-opacity-20 text-left" onclick="sortTable(1,'table-volcano-4')">Provinsi</th> -->
                                            <th class="cursor-pointer p-1 bg-orange-500 bg-opacity-50 border-b border-white border-opacity-20 text-left" onclick="sortTable(2,'table-volcano-4')">Kabupaten/Kota</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-volcano">
                                        @if($data->where("ga_status",1)->count() == 0)
                                            <tr>
                                                <td colspan=3 class="p-1 border-b border-white border-opacity-20 text-center">Tidak ada gunung api dengan Status Normal</td>
                                            </tr>
                                        @else
                                            @foreach($data->where("ga_status",1) as $row)
                                                <tr>
                                                    <td class="p-1 border-b border-white border-opacity-20 whitespace-nowrap">{{ $row['ga_nama_gapi'] }}</td>
                                                    <!-- <td class="p-1 border-b border-white border-opacity-20">{{ $row['ga_prov_gapi'] }}</td> -->
                                                    <td class="p-1 border-b border-white border-opacity-20">{{ $row['ga_kab_gapi'] }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>                        
                </div>
                <div id="menu-top-right" class="flex flex-col flex-nowrap bg-black bg-opacity-30" style="max-width: 300px;">
                    <div>
                        <nav class="tabs flex flex-col sm:flex-row">
                            <button data-target="panel-1" class="tab active bg-orange-500 whitespace-nowrap text-sm text-white py-2 px-4 block hover:bg-orange-500 hover:bg-opacity-50 focus:outline-none flex-grow border-b border-r border-paleblue">
                                Aktivitas
                            </button>
                            <button data-target="panel-2" class="tab whitespace-nowrap text-sm text-white py-2 px-4 block hover:bg-orange-500 hover:bg-opacity-50 focus:outline-none flex-grow border-b border-r border-paleblue">
                                Letusan
                            </button>
                        </nav>
                    </div>
                    <div id="panels" class="flex-grow flex-nowrap">
                        <div class="panel-1 active tab-content h-full">
                            <div class="p-2">
                                <div class=" h-96 overflow-y-auto custom-scrollbar pr-1 text-white">                            
                                    <livewire:activity /> 
                                </div>
                            </div> 
                        </div>
                        <div class="panel-2 tab-content h-full">
                            <div class="p-2">
                                <div class=" h-96 overflow-y-auto custom-scrollbar pr-1 text-white">
                                    <livewire:eruption /> 
                                </div>
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
    @livewireScripts
<script type="text/javascript">

    var volcanoLayer;
    var volcanoContainer;    
    initMap();
    initSidebar();
    // addMapLayer('menu-title','topleft');
    addMapLayer('map-zoom','bottomright');
    addMapLayer('map-title','topleft');
    addMapLayer('menu-top-left','topleft');
    addMapLayer('menu-top-right','topright');
    addMapLayer('map-summary','bottomleft');
    addMapLayer('menu-layer','bottomleft');
    // addMapLayer('map-legend','topright');
    // var hazardMarkerContainer;
    var layerData;
    var layerKRB;
    var storagePath = "{{ asset("") }}";

    data = {!! json_encode($data) !!};
    getVolcano();
    getKRB();
    document.getElementById("menu-volcano").classList.add("bg-white");
    document.getElementById("menu-volcano").childNodes[1].childNodes[1].classList.remove("text-white");
    document.getElementById("menu-volcano").childNodes[1].childNodes[1].classList.add("text-orange-500");
    document.getElementById("menu-volcano").childNodes[1].childNodes[3].classList.remove("text-white");
    document.getElementById("menu-volcano").childNodes[1].childNodes[3].classList.add("text-orange-500");

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
      event.target.classList.add("bg-orange-500");
    }

    for (let i = 0; i < tab.length; i++) {
      tab[i].addEventListener('click', onTabClick, false);
    }

    const tabs_volcano = document.querySelectorAll(".tabs-volcano");
    const tab_volcano = document.querySelectorAll(".tab-volcano");
    const panel_volcano = document.querySelectorAll(".tab-volcano-content");

    function onTabVolcanoClick(event) {

      // deactivate existing active tabs and panel

      for (let i = 0; i < tab_volcano.length; i++) {
        tab_volcano[i].classList.remove("active");
        tab_volcano[i].classList.remove("bg-orange-500");
      }

      for (let i = 0; i < panel_volcano.length; i++) {
        panel_volcano[i].classList.remove("active");
      }

      // activate new tabs and panel
      event.target.classList.add('active');
      let classString = event.target.getAttribute('data-target');
      document.getElementById('panels-volcano').getElementsByClassName(classString)[0].classList.add("active")
      event.target.classList.add("bg-orange-500")
    }

    for (let i = 0; i < tab_volcano.length; i++) {
      tab_volcano[i].addEventListener('click', onTabVolcanoClick, false);
    }    
</script>
@endsection