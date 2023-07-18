@extends('layouts.master')

@section('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
  <script src="https://unpkg.com/esri-leaflet@3.0.3/dist/esri-leaflet.js"
    integrity="sha512-kuYkbOFCV/SsxrpmaCRMEFmqU08n6vc+TfAVlIKjR1BPVgt75pmtU9nbQll+4M9PN2tmZSAgD1kGUCKL88CscA=="
    crossorigin=""></script>
    <script src='//api.tiles.mapbox.com/mapbox.js/plugins/leaflet-omnivore/v0.3.1/leaflet-omnivore.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.21.0/moment-with-locales.js"></script>
@endsection
@section('content')
@php $countUSGS=0 @endphp
@foreach($data['usgs']['features'] as $key => $value)
    @if(strpos($value['properties']['place'], "Indonesia") !== false)
        @if(\Carbon\Carbon::parse($value['properties']['time']/1000) >= \Carbon\Carbon::now()->subhour(24))
        @php $countUSGS++ @endphp
        @endif
    @endif
@endforeach  
@php $countBMKG=0 @endphp
@foreach($data['bmkg'] as $key => $value)
        @if(\Carbon\Carbon::parse($value['waktu']) >= \Carbon\Carbon::now()->subhour(24))
        @php $countBMKG++ @endphp
        @endif
@endforeach

@include('layouts.loader')
<main class="bg-main">
    @include('layouts.sidebar')
    <section class="flex flex-col home-section">
        <div class="grid grid-cols-1 md:grid-cols-1 flex-grow">
            <div class="flex flex-col">

                <div id="map" class="min-h-screen">
                    <div id="menu-title">
                        <div class="flex">
                            <img src="{{ asset('img/BNPB-white.png') }}" style="max-height:60px;">    
                            <img src="{{ asset('img/logo-white.png') }}" class="ml-2" style="max-height:60px;">
                        </div>
                        <div class="my-auto mt-2 pt-1 border-t">
                            <p id="map-title" class="text-white font-bold text-xl uppercase" style="text-shadow: 0 0 10px rgba(0, 0, 0,1);">Pengamatan Gempa Bumi</p>
                            <p id="map-date" class="text-white" style="text-shadow: 0 0 10px rgba(0, 0, 0,1);">{{ \Carbon\Carbon::now()->translatedFormat("d F Y")}}</p>
                        </div>
                    </div>
                    <div id="map-zoom">
                        <div>
                            <div id='in' class="bg-white bg-opacity-30 mb-1 flex hover:bg-orange-500 cursor-pointer" style="width: 25px;height: 25px;"><p class="cursor-pointer m-auto text-white font-bold">+</p></div>
                            <div id='out' class="bg-white bg-opacity-30 flex hover:bg-orange-500 cursor-pointer" style="width: 25px;height: 25px;"><p class="cursor-pointer m-auto text-white font-bold">-</p></div>                  
                        </div>
                    </div>
                    <div id="menu-bottom-right" class="flex items-end">
                        
                        <div class="bg-black bg-opacity-25 p-2 mr-2 rounded-sm shadow-xs shadow-paleblue text-right">
                            <p class="text-white font-bold mr-2 mb-1 text-orange-500">Layer inaRISK</p>
                            <div class="flex items-end justify-end">
                                <div class="mr-2 flex">
                                    <input type="radio" name="layer-inarisk-hazard" class="my-auto" data-text="Gempa Bumi" checked value='gempabumi' onclick="getInariskHazard(this.value);">
                                    <label class="ml-1 my-auto text-white text-xs">Gempa Bumi</label>
                                </div>
                                <div class="mr-2 flex">
                                    <input type="radio" name="layer-inarisk-hazard" class="my-auto" data-text="Tsunami" value='tsunami' onclick="getInariskHazard(this.value);">
                                    <label class="ml-1 my-auto text-white text-xs">Tsunami</label>
                                </div>
                                <div class="mr-2 flex">
                                    <input type="checkbox" id="check-faults" name="layer-faults" class="my-auto" data-text="Faults" value='faults' onclick="getInariskFault();">
                                    <label class="ml-1 my-auto text-white text-xs">Patahan</label>
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
                        <div id="legend-earthquake" class="bg-black bg-opacity-30 p-2 mr-2 my-auto rounded-sm">
                                <p class="text-white text-xs">Magnitude: </p>
                                <p class="text-white text-xs" for="magnitude1"><span class="text-red-700">&#9679;</span> > 7</p>
                                <p class="text-white text-xs" for="magnitude2"><span class="text-yellow-600">&#9679;</span> 6-7</p>
                                <p class="text-white text-xs" for="magnitude3"><span class="text-yellow-300">&#9679;</span> 5-6</p>
                                <p class="text-white text-xs" for="magnitude4"><span class="text-green-500">&#9679;</span> 4-5</p>
                                <p class="text-white text-xs" for="magnitude5"><span class="text-blue-500">&#9679;</span> < 4</p>
                        </div>
                    </div>

                    <div id="menu-timestep" class="bg-black bg-opacity-25 p-2 rounded-sm shadow-xs shadow-paleblue">
                        <div class="flex">
                            <div class="mr-2 flex">
                                <p class="text-white">Sumber Data: </p>
                            </div>
                            <div class="mr-2 flex">
                                <input type="radio" name="source-earthquake" class="my-auto" data-text="1 Hari Terakhir" value='bmkg' checked onclick="getEarthquake()">
                                <label class="ml-1 my-auto text-white text-xs">BMKG</label>
                            </div>
                            <div class="mr-2 flex">
                                <input type="radio" name="source-earthquake" class="my-auto" data-text="7 Hari Terakhir" value='usgs' onclick="getEarthquake()">
                                <label class="ml-1 my-auto text-white text-xs">USGS</label>
                            </div>
                        </div>
                        <hr class="my-1 border-gray-300 border-opacity-30">          
                        <div class="flex">
                            <div class="mr-2 flex">
                                <input type="radio" name="timestep-earthquake" class="my-auto" data-text="1 Hari Terakhir" value='day' onclick="getEarthquake()">
                                <label class="ml-1 my-auto text-white text-xs" for="timestep3">1 Hari Terakhir</label>
                            </div>
                            <div class="mr-2 flex">
                                <input type="radio" name="timestep-earthquake" class="my-auto" data-text="7 Hari Terakhir" checked value='week' onclick="getEarthquake()">
                                <label class="ml-1 my-auto text-white text-xs" for="timestep2">7 Hari Terakhir</label>
                            </div>
                            <div class="mr-2 flex">
                                <input type="radio" name="timestep-earthquake" class="my-auto" data-text="1 Bulan Terakhir" value='month' onclick="getEarthquake()">
                                <label class="ml-1 my-auto text-white text-xs" for="timestep1">1 Bulan Terakhir</label>
                            </div>
                        </div>
                    </div>
                    <div id="menu-top-left" style="max-width:450px;">
                        <div id="count-BMKG-earthquake" class="hidden p-2 bg-animation-red cursor-pointer" onclick="toggleInfo()">
                            <div class="flex justify-between">
                                <div>
                                    <p class="font-bold text-white text-xl uppercase">Kejadian Terakhir</p>
                                    <!-- <p class="text-white">{{ \Carbon\Carbon::now()->format("d F Y") }}</p> -->
                                    <p class="text-white font-bold">{{ $data['earthquakeLatest']['Infogempa']['gempa']['Tanggal'] }} {{ $data['earthquakeLatest']['Infogempa']['gempa']['Jam'] }}</p>
                                </div>
                                <i class='text-yellow-500 text-5xl my-auto bx bx-globe'></i>
                            </div>
                            <p class="text-white font-bold">Lokasi:</p>
                            <p class="text-white">{{ $data['earthquakeLatest']['Infogempa']['gempa']['Wilayah'] }}</p>
                            <div id="info-detail" class="hidden">
                                <p class="text-white font-bold">Potensi:</p>
                                <p class="text-white">{{ $data['earthquakeLatest']['Infogempa']['gempa']['Potensi'] }}</p>
                                <p class="text-white font-bold">Dirasakan:</p>
                                <p class="text-white">{{ $data['earthquakeLatest']['Infogempa']['gempa']['Dirasakan'] }}</p>
                            </div>
                            <div id="info-container" class="mt-2">
                                <div class="grid gap-1 grid-cols-1 md:grid-cols-3 flex-grow">
                                    <div class="bg-gray-200 bg-opacity-80 text-center p-1">
                                        <p class="text-sm font-bold flex text-center justify-center align-middle"><i class='text-red bx bx-pulse my-auto'></i> <span class="ml-1 my-auto">{{ $data['earthquakeLatest']['Infogempa']['gempa']['Magnitude'] }} SR</span></p>
                                        <p class="text-xs">Magnitude</p>
                                    </div>
                                    <div class="bg-gray-200 bg-opacity-80 text-center p-1">
                                        <p class="text-sm font-bold flex text-center justify-center align-middle"><i class='text-blue bx bx-water my-auto'></i> <span class="ml-1 my-auto">{{ $data['earthquakeLatest']['Infogempa']['gempa']['Kedalaman'] }}</span></p>
                                        <p class="text-xs">Kedalaman</p>
                                    </div>
                                    <div class="bg-gray-200 bg-opacity-80 text-center p-1">
                                        <p class="text-xs">Lintang: <span class="font-bold">{{ $data['earthquakeLatest']['Infogempa']['gempa']['Lintang'] }}</span></p>
                                        <p class="text-xs">Bujur: <span class="font-bold">{{ $data['earthquakeLatest']['Infogempa']['gempa']['Bujur'] }}</span></p>
                                    </div>
                                </div>
                            </div>                                                     
                        </div> 

                        <div id="count-USGS-earthquake" class="hidden border-2 border-paleblue border-opacity-50 shadow-paleblue p-2 {{ $countUSGS>0 ? 'bg-animation-red' : '' }}">
                            <div class="flex justify-between">
                                <div class="my-auto flex">
                                    <p class="text-white text-opacity-80 text-4xl"><i class='bx bx-globe'></i></p>
                                    <p class="{{ $countUSGS>0 ? 'text-white' : 'text-red' }}  uppercase font-bold ml-1 my-auto">Gempa Bumi Terkini</p>
                                </div>
                                <div class="my-auto flex ml-4">
                                    <p class="{{ $countUSGS>0 ? 'text-white' : 'text-red' }} text-2xl text-right font-bold">{{ $countUSGS }}</p>
                                    <p class="text-white text-xs my-auto ml-1">Kejadian</p>
                                </div>
                            </div>
                        </div>                                              
                    </div>
                </div>
            </div>
            <div id="menu-top-right" class="flex flex-col flex-nowrap bg-black bg-opacity-30">
                <div class="text-center bg-lime-300">
                    <p class="font-bold text-black text-xl uppercase">Daftar Kejadian Gempa</p>
                </div>
                <div class="mt-2">
                    <nav class="tabs flex flex-col sm:flex-row">
                        <button data-target="panel-1" data-timestep="day" class="tab whitespace-nowrap text-sm text-white py-1 px-2 block hover:bg-orange-500 hover:bg-opacity-50 focus:outline-none flex-grow border-b border-r border-paleblue">
                            24 Jam
                        </button>
                        <button data-target="panel-2" data-timestep="week" class="tab active bg-orange-500 whitespace-nowrap text-sm text-white py-1 px-2 block hover:bg-orange-500 hover:bg-opacity-50 focus:outline-none flex-grow border-b border-r border-paleblue">
                            7 Hari
                        </button>
                        <button data-target="panel-3" data-timestep="month" class="tab whitespace-nowrap text-sm text-white py-1 px-2 block hover:bg-orange-500 hover:bg-opacity-50 focus:outline-none flex-grow border-b border-paleblue">
                            1 Bulan
                        </button>
                    </nav>
                </div>
                <div id="panels" class="flex-grow flex-nowrap">
                    <div class="panel-1 tab-content h-full">
                        <div class="pl-2 py-2">
                            <div class="max-h-80 overflow-y-auto custom-scrollbar pr-1">                            
                                <table id="table-hazard-1" class="table-auto text-white text-xs w-full">
                                    <thead>
                                        <tr>
                                            <!-- <th class="p-1 bg-orange-500 bg-opacity-50 border-b border-white border-opacity-20">No</th> -->
                                            <th class="p-1 bg-orange-500 bg-opacity-50 border-b border-white border-opacity-20 text-left">Lokasi</th>
                                            <th class="cursor-pointer p-1 bg-orange-500 bg-opacity-50 border-b border-white border-opacity-20 flex justify-center" onclick="sortTable(1,'table-hazard-1')">Tanggal <i class='my-auto mx-1 bx bxs-sort-alt'></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $count=1 @endphp
                                        @foreach($data['usgs']['features'] as $key => $value)
                                            @if(strpos($value['properties']['place'], "Indonesia") !== false)
                                                @if(\Carbon\Carbon::parse($value['properties']['time']/1000) >= \Carbon\Carbon::now()->subhour(24))
                                                    <tr class="table-usgs table-earthquake hidden">
                                                        <!-- <td class="p-1 border-b border-white border-opacity-20 text-center">{{$count}}</td> -->
                                                        <td class="p-1 border-b border-white border-opacity-20">{{ $value['properties']['place'] }}</td>
                                                        <td class="p-1 border-b border-white border-opacity-20 text-center whitespace-nowrap">{{ \Carbon\Carbon::parse($value['properties']['time']/1000) }}</td>
                                                    </tr>                                                    
                                                    @php $count++ @endphp
                                                @endif
                                            @endif
                                        @endforeach                                        
                                        @php $count=1 @endphp
                                        @foreach($data['bmkg'] as $key => $value)
                                            @if(\Carbon\Carbon::parse($value['waktu']) >= \Carbon\Carbon::now()->subhour(24))
                                                <tr class="table-bmkg table-earthquake hidden">
                                                    <!-- <td class="p-1 border-b border-white border-opacity-20 text-center">{{$count}}</td> -->
                                                    <td class="p-1 border-b border-white border-opacity-20">{{ $value['Keterangan'] }}</td>
                                                    <td class="p-1 border-b border-white border-opacity-20 text-center whitespace-nowrap">{{ \Carbon\Carbon::parse($value['waktu'])->format('Y-m-d H:i') }}</td>
                                                </tr>                                                    
                                                @php $count++ @endphp
                                            @endif
                                        @endforeach                                        
                                    </tbody>
                                </table>
                            </div>
                        </div> 
                    </div>
                    <div class="panel-2 active tab-content h-full">
                        <div class="pl-2 py-2">
                            <div class="max-h-80 overflow-y-auto custom-scrollbar pr-1">                            
                                <table id="table-hazard-2" class="table-auto text-white text-xs w-full">
                                    <thead>
                                        <tr>
                                            <!-- <th class="p-1 bg-orange-500 bg-opacity-50 border-b border-white border-opacity-20">No</th> -->
                                            <th class="p-1 bg-orange-500 bg-opacity-50 border-b border-white border-opacity-20 text-left">Lokasi</th>
                                            <th class="cursor-pointer p-1 bg-orange-500 bg-opacity-50 border-b border-white border-opacity-20 flex justify-center" onclick="sortTable(1,'table-hazard-2')">Tanggal <i class='my-auto mx-1 bx bxs-sort-alt'></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $count=1 @endphp
                                        @foreach($data['usgs']['features'] as $key => $value)
                                            @if(strpos($value['properties']['place'], "Indonesia") !== false)
                                                @if(\Carbon\Carbon::parse($value['properties']['time']/1000) >= \Carbon\Carbon::now()->subday(7))
                                                    <tr class="table-usgs table-earthquake hidden">
                                                        <!-- <td class="p-1 border-b border-white border-opacity-20 text-center">{{$count}}</td> -->
                                                        <td class="p-1 border-b border-white border-opacity-20">{{ $value['properties']['place'] }}</td>
                                                        <td class="p-1 border-b border-white border-opacity-20 text-center whitespace-nowrap">{{ \Carbon\Carbon::parse($value['properties']['time']/1000) }}</td>
                                                    </tr>                                                    
                                                    @php $count++ @endphp
                                                @endif
                                            @endif
                                        @endforeach                                        
                                        @php $count=1 @endphp
                                        @foreach($data['bmkg'] as $key => $value)
                                            @if(\Carbon\Carbon::parse($value['waktu']) >= \Carbon\Carbon::now()->subday(7))
                                                <tr class="table-bmkg table-earthquake hidden">
                                                    <!-- <td class="p-1 border-b border-white border-opacity-20 text-center">{{$count}}</td> -->
                                                    <td class="p-1 border-b border-white border-opacity-20">{{ $value['Keterangan'] }}</td>
                                                    <td class="p-1 border-b border-white border-opacity-20 text-center whitespace-nowrap">{{ \Carbon\Carbon::parse($value['waktu'])->format('Y-m-d H:i') }}</td>
                                                </tr>                                                    
                                                @php $count++ @endphp
                                            @endif
                                        @endforeach                                        
                                    </tbody>
                                </table>
                            </div>
                        </div> 
                    </div>
                    <div class="panel-3 tab-content h-full">
                        <div class="pl-2 py-2">
                            <div class="max-h-80 overflow-y-auto custom-scrollbar pr-1">                            
                                <table class="table-auto text-white text-xs w-full">
                                    <thead>
                                        <tr>
                                            <!-- <th class="p-1 bg-orange-500 bg-opacity-50 border-b border-white border-opacity-20">No</th> -->
                                            <th class="p-1 bg-orange-500 bg-opacity-50 border-b border-white border-opacity-20 text-left">Lokasi</th>
                                            <th class="cursor-pointer p-1 bg-orange-500 bg-opacity-50 border-b border-white border-opacity-20 flex justify-center" onclick="sortTable(1,'table-hazard-3')">Tanggal <i class='my-auto mx-1 bx bxs-sort-alt'></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $count=1 @endphp
                                        @foreach($data['usgs']['features'] as $key => $value)
                                            @if(strpos($value['properties']['place'], "Indonesia") !== false)
                                                <tr class="table-usgs table-earthquake hidden">
                                                    <!-- <td class="p-1 border-b border-white border-opacity-20 text-center">{{$count}}</td> -->
                                                    <td class="p-1 border-b border-white border-opacity-20">{{ $value['properties']['place'] }}</td>
                                                    <td class="p-1 border-b border-white border-opacity-20 text-center whitespace-nowrap">{{ \Carbon\Carbon::parse($value['properties']['time']/1000) }}</td>
                                                </tr>
                                                @php $count++ @endphp
                                            @endif
                                        @endforeach                                        
                                        @php $count=1 @endphp
                                        @foreach($data['bmkg'] as $key => $value)
                                            @if(\Carbon\Carbon::parse($value['waktu']) >= \Carbon\Carbon::now()->submonth(1))
                                                <tr class="table-bmkg table-earthquake hidden">
                                                    <!-- <td class="p-1 border-b border-white border-opacity-20 text-center">{{$count}}</td> -->
                                                    <td class="p-1 border-b border-white border-opacity-20">{{ $value['Keterangan'] }}</td>
                                                    <td class="p-1 border-b border-white border-opacity-20 text-center whitespace-nowrap">{{ \Carbon\Carbon::parse($value['waktu'])->format('Y-m-d H:i') }}</td>
                                                </tr>                                                    
                                                @php $count++ @endphp
                                            @endif
                                        @endforeach                                          
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
function toggleInfo() {
    let info = document.getElementById("info-detail");
    info.classList.toggle("hidden");
}

    var earthquakeLayer;
    var earthquakeBMKGContainer;
    var layerData;
    var staticLayer;
    initMap();
    addMapLayer('menu-bottom-right','bottomright');
    addMapLayer('menu-timestep','bottomleft');
    addMapLayer('map-zoom','bottomleft');
    addMapLayer('menu-title','topleft');
    addMapLayer('menu-top-left','topleft');
    addMapLayer('menu-top-right','topright');
    initSidebar();
    moment.locale('id');
    data = {!! json_encode($data) !!};
    // console.log(data['earthquakeLatest']['Infogempa']['gempa']['Coordinates']);
    map.setView({lat:data['earthquakeLatest']['Infogempa']['gempa']['Coordinates'].split(',')[0], lng:data['earthquakeLatest']['Infogempa']['gempa']['Coordinates'].split(',')[1]}, 8)
    getEarthquake();
    document.getElementById("menu-earthquake").classList.add("bg-white");
    document.getElementById("menu-earthquake").childNodes[1].childNodes[1].classList.remove("text-white");
    document.getElementById("menu-earthquake").childNodes[1].childNodes[1].classList.add("text-orange-500");
    document.getElementById("menu-earthquake").childNodes[1].childNodes[3].classList.remove("text-white");
    document.getElementById("menu-earthquake").childNodes[1].childNodes[3].classList.add("text-orange-500");

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
      
      let timestep = event.target.getAttribute('data-timestep');
      if (timestep == 'day') {
        document.getElementsByName("timestep-earthquake")[0].checked = true
      }
      if (timestep == 'week') {
        document.getElementsByName("timestep-earthquake")[1].checked = true
      }
      if (timestep == 'month') {
        document.getElementsByName("timestep-earthquake")[2].checked = true
      }
      getEarthquake();
    }

    for (let i = 0; i < tab.length; i++) {
      tab[i].addEventListener('click', onTabClick, false);
    }

</script>
@endsection