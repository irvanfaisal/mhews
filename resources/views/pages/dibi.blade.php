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
                <div id="map-title">
                    <div class="flex">
                        <img src="{{ asset('img/BNPB-white.png') }}" style="max-height:60px;">    
                        <img src="{{ asset('img/logo-white.png') }}" class="ml-2" style="max-height:60px;">
                    </div>
                    <div class="my-auto mt-2 pt-1 border-t">
                        <p class="text-white font-bold text-xl uppercase" style="text-shadow: 0 0 10px rgba(0, 0, 0,1);">Data Informasi Bencana Indonesia</p>
                        <p id="map-date" class="text-white uppercase font-bold" style="text-shadow: 0 0 10px rgba(0, 0, 0,1);">-</p>
                    </div>
                </div>
                <div id="map-zoom">
                    <div>
                        <div id='in' class="bg-white bg-opacity-30 mb-1 flex hover:bg-orange-500 cursor-pointer" style="width: 25px;height: 25px;"><p class="cursor-pointer m-auto text-white font-bold">+</p></div>
                        <div id='out' class="bg-white bg-opacity-30 flex hover:bg-orange-500 cursor-pointer" style="width: 25px;height: 25px;"><p class="cursor-pointer m-auto text-white font-bold">-</p></div>                  
                    </div>
                </div>
                <div id="menu-layer">
                    <div class="bg-black bg-opacity-25 p-2 rounded-sm my-1 shadow-xs shadow-paleblue inline-block">
                        <div class="flex">
                            <div class="mr-2 flex">
                                <input type="radio" class="my-auto" name="map-index" value="province" onchange="changeMap();">
                                <label class="ml-1 my-auto text-white text-xs">Provinsi</label>
                            </div>
                            <div class="mr-2 flex">
                                <input type="radio" class="my-auto" name="map-index" value="regency" onchange="changeMap();" checked>
                                <label class="ml-1 my-auto text-white text-xs">Kabupaten/Kota</label>
                            </div>
                        </div>
                    </div>
                    <div>
                    
                        <select id="year" class="text-white bg-black bg-opacity-25 p-2 my-1 rounded-sm shadow-xs shadow-paleblue" onchange="getDibi()">
                            @for ($i = 2022; $i >= 2008; $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                        <select id="month" class="text-white bg-black bg-opacity-25 p-2 ml-1 my-1 rounded-sm shadow-xs shadow-paleblue" onchange="getDibi()">
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
                        <select id="hazard" class="text-white bg-black bg-opacity-25 p-2 ml-1 my-1 rounded-sm shadow-xs shadow-paleblue" onchange="getDibi()">
                            <option selected value="all">SEMUA BENCANA</option>
                            @foreach($hazards as $hazard)
                                <option value="{{ $hazard }}">{{ $hazard }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div id="menu-top-right" class="flex flex-col flex-nowrap bg-black bg-opacity-30 shadow-paleblue">
                    <div>
                        <nav class="tabs flex flex-col sm:flex-row">
                            <button data-target="panel-1" class="tab whitespace-nowrap text-xs text-white py-2 px-2 block hover:bg-orange-500 hover:bg-opacity-50 focus:outline-none flex-grow border-b">
                                Provinsi
                            </button>
                            <button data-target="panel-2" class="tab active bg-orange-500 whitespace-nowrap text-xs text-white py-2 px-2 block hover:bg-orange-500 hover:bg-opacity-50 focus:outline-none flex-grow border-b">
                                Kabupaten/Kota
                            </button>
                        </nav>
                    </div>
                    <div id="panels" class="flex-grow flex-nowrap">
                        <div class="panel-1 tab-content py-2 ml-3 bg-opacity-30 h-full">
                            <div id="table-provinsi" class="h-80 overflow-y-auto pr-1 custom-scrollbar">

                            </div>
                        </div>
                        <div class="panel-2 tab-content active py-2 ml-3 h-full bg-opacity-30">
                            <div id="table-kabupaten" class="h-80 overflow-y-auto pr-1 custom-scrollbar">

                            </div>
                        </div>
                    </div>   
                </div>              
                  
            </div>
        </div>
    </section>
    <div class="modal-kabupaten opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center z-10">
        <div class="modal-kabupaten-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>

        <div class="modal-container bg-white w-11/12 md:max-w-4xl mx-auto rounded shadow-lg z-50 overflow-y-auto">
            <div class="modal-content py-4 text-left px-2" style="width:100% !important;max-width: 98%;">
                <!--Title-->
                <div class="flex justify-between items-center pb-3">
                    <div>
                        <p class="text-xl font-bold uppercase" id="modal-regency-title">-</p>
                    </div>
                    <div class="modal-kabupaten-close cursor-pointer z-50">
                        <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                            <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
                        </svg>
                    </div>
                </div>
                <div class="modal-body custom-scrollbar" style="max-height: 75vh;overflow-y: auto;">
                    <div class="grid gap-2 grid-cols-1 md:grid-cols-2">
                        <div id="chart-regency-1"></div>
                        <div id="chart-regency-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-provinsi opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center z-10">
        <div class="modal-provinsi-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>

        <div class="modal-container bg-white w-11/12 md:max-w-4xl mx-auto rounded shadow-lg z-50 overflow-y-auto">
            <div class="modal-content py-4 text-left px-2" style="width:100% !important;max-width: 98%;">
                <!--Title-->
                <div class="flex justify-between items-center pb-3">
                    <div>
                        <p class="text-xl font-bold uppercase" id="modal-province-title">-</p>
                    </div>
                    <div class="modal-provinsi-close cursor-pointer z-50">
                        <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                            <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
                        </svg>
                    </div>
                </div>
                <div class="modal-body custom-scrollbar" style="max-height: 75vh;overflow-y: auto;">
                    <div class="grid gap-2 grid-cols-1 md:grid-cols-2">
                        <div id="chart-province-1"></div>
                        <div id="chart-province-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
      "colors": ['#d53e4f','#f46d43','#fdae61','#fee08b','#e6f598','#abdda4','#66c2a5','#3288bd'],
      "chart": {
        "style": {
          "fontFamily": "Source Sans Pro",
          "color": "#fff"
        }
      },
      "xAxis": {
        "gridLineWidth": 1,
        "gridLineColor": "rgba(0,0,0,.075)",
        "lineColor": "rgba(0,0,0,.075)",
        "minorGridLineColor": "rgba(0,0,0,.075)",
        "tickColor": "rgba(0,0,0,.075)",
        "tickWidth": 1
      },
      "yAxis": {
        "gridLineColor": "rgba(0,0,0,.075)",
        "lineColor": "rgba(0,0,0,.075)",
        "minorGridLineColor": "rgba(0,0,0,.075)",
        "tickColor": "rgba(0,0,0,.075)",
        "tickWidth": 1
      },
      "legendBackgroundColor": "rgba(0, 0, 0, 0.5)",
      "background2": "#505053",
      "dataLabelsColor": "#B0B0B3",
      "textColor": "#C0C0C0",
      "contrastTextColor": "#F0F0F3",
      "maskColor": "rgba(255,255,255,0.3)"
    }
    Highcharts.setOptions(Highcharts.theme);    
    initMap();
    addMapLayer('map-title','topleft');
    addMapLayer('menu-top-right','topright');
    addMapLayer('menu-layer','bottomleft');
    addMapLayer('map-zoom','bottomright');
    initSidebar();
    initProvinceMap();
    initRegencyMap();
    var storagePath = "{{ asset("") }}";
    document.getElementById("menu-dibi").classList.add("bg-white");
    document.getElementById("menu-dibi").childNodes[1].childNodes[1].classList.remove("text-white");
    document.getElementById("menu-dibi").childNodes[1].childNodes[1].classList.add("text-orange-500");
    document.getElementById("menu-dibi").childNodes[1].childNodes[3].classList.remove("text-white");
    document.getElementById("menu-dibi").childNodes[1].childNodes[3].classList.add("text-orange-500");

    var openmodal = document.querySelectorAll('.modal-kabupaten-open')
    for (var i = 0; i < openmodal.length; i++) {
      openmodal[i].addEventListener('click', function(event){
        event.preventDefault()
        toggleModalKabupaten()
      })
    }
    
    const overlayKabupaten = document.querySelector('.modal-kabupaten-overlay')
    overlayKabupaten.addEventListener('click', toggleModalKabupaten)
    
    var closemodal = document.querySelectorAll('.modal-kabupaten-close')
    for (var i = 0; i < closemodal.length; i++) {
      closemodal[i].addEventListener('click', toggleModalKabupaten)
    }
    
    function toggleModalKabupaten () {
      const body = document.querySelector('body')
      const modal = document.querySelector('.modal-kabupaten')
      modal.classList.toggle('opacity-0')
      modal.classList.toggle('pointer-events-none')
      body.classList.toggle('modal-kabupaten-active')
    }
      
    var openmodal = document.querySelectorAll('.modal-provinsi-open')
    for (var i = 0; i < openmodal.length; i++) {
      openmodal[i].addEventListener('click', function(event){
        event.preventDefault()
        toggleModalProvinsi()
      })
    }
    
    const overlayProvinsi = document.querySelector('.modal-provinsi-overlay')
    overlayProvinsi.addEventListener('click', toggleModalProvinsi)
    
    var closemodal = document.querySelectorAll('.modal-provinsi-close')
    for (var i = 0; i < closemodal.length; i++) {
      closemodal[i].addEventListener('click', toggleModalProvinsi)
    }
    
    function toggleModalProvinsi () {
      const body = document.querySelector('body')
      const modal = document.querySelector('.modal-provinsi')
      modal.classList.toggle('opacity-0')
      modal.classList.toggle('pointer-events-none')
      body.classList.toggle('modal-provinsi-active')
    }
      


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