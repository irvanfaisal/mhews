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
    <link rel="stylesheet" href="{{ asset('css/leaflet-velocity.css') }}">
    <script type="text/javascript" src="{{ asset('js/leaflet-velocity.js') }}"></script>
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
        <div class="grid grid-cols-1 flex-grow">
            <div id="map" class="min-h-screen">
                <div id="menu-title">
                    <div class="flex">
                        <img src="{{ asset('img/BNPB-white.png') }}" style="max-height:60px;">    
                        <img src="{{ asset('img/logo-white.png') }}" class="ml-2" style="max-height:60px;">
                    </div>
                    <div class="my-auto mt-2 pt-1 border-t">
                        <p id="map-title" class="text-white font-bold text-xl uppercase" style="text-shadow: 0 0 10px rgba(0, 0, 0,1);">Prediksi Cuaca di Indonesia</p>
                        <p id="map-date" class="text-white" style="text-shadow: 0 0 10px rgba(0, 0, 0,1);">-</p>
                    </div>
                </div>

                <div id="menu-layer" class="flex flex-col justify-end">
                    
                    <div class="flex justify-end">
                        <div class="flex grow-0 bg-black bg-opacity-30 p-2 my-auto rounded-sm shadow-xs shadow-paleblue">
                            <div class="mr-2 flex">
                                <input type="radio" name="menu-layer" class="my-auto" data-text="Hujan" checked value='rain' onclick="getWeather();">
                                <label class="ml-1 my-auto text-white text-xs">Hujan</label>
                            </div>
                            <div class="mr-2 flex">
                                <input type="radio" name="menu-layer" class="my-auto" data-text="Kecepatan Angin" value='wspd' onclick="getWeather();">
                                <label class="ml-1 my-auto text-white text-xs">Kecepatan Angin</label>
                            </div>
                            <div class="mr-2 flex">
                                <input type="radio" name="menu-layer" class="my-auto" data-text="Temperatur" value='temp' onclick="getWeather();">
                                <label class="ml-1 my-auto text-white text-xs">Temperatur</label>
                            </div>
                            <div class="mr-2 flex">
                                <input type="radio" name="menu-layer" class="my-auto" data-text="Kelembapan" value='rh' onclick="getWeather();">
                                <label class="ml-1 my-auto text-white text-xs">Kelembapan</label>
                            </div>
                        </div>
                    </div>
                    <div id="weather-legend-container"></div>                    
                </div>              
                <div id="map-zoom" class="flex items-end">
                    <div class="mr-2">
                        <input id="date" type='date' class="bg-white bg-opacity-75 p-1" required value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" onchange="getWeather();">
                        <select id="time" class="bg-white bg-opacity-75 p-1" onfocus='this.size=10;' onblur='this.size=1;' onchange='this.size=1; this.blur();getWeather();'>
                            @for($i = 0;$i<24;$i++)

                                <option value="{{ sprintf('%02d',$i); }}" class="text-center" {{ ($i == \Carbon\Carbon::now()->format('G') ? 'selected' : '') }}>{{ sprintf('%02d',$i); }}:00</option>
                            @endfor
                        </select>                          
                    </div>                    
                    <div>
                        <div id='in' class="bg-white bg-opacity-30 mb-1 flex hover:bg-orange-500 cursor-pointer" style="width: 25px;height: 25px;"><p class="cursor-pointer m-auto text-white font-bold">+</p></div>
                        <div id='out' class="bg-white bg-opacity-30 flex hover:bg-orange-500 cursor-pointer" style="width: 25px;height: 25px;"><p class="cursor-pointer m-auto text-white font-bold">-</p></div>                  
                    </div>
                </div>
                <div id="menu-top-right">
                    @livewire('filter-weather')
                    
                </div>
                <div id="menu-bottom-left">                   
                    <div id="weather-chart" style="z-index: 1;height:200px;width: 400px;"></div>
                </div>
            </div>

        </div>
    </section>
 <!--Modal-->
  <div class="modal-daily opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center z-50">
    <div class="modal-daily-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>
    
    <div class="modal-container shadow-paleblue text-white w-11/12 md:max-w-md mx-auto rounded shadow-lg overflow-y-auto">
      
      <!-- Add margin if you want to see some of the overlay behind the modal-->
      <div class="modal-content py-4 text-left px-6">
        <!--Title-->
        <div class="flex justify-between items-center pb-3">
            <div>
                <p class="text-xl font-bold text-paleblue-light uppercase" id="daily-title">-</p>
                <p class="text-xs font-bold text-white" id="daily-date">-</p>
            </div>
          <div class="modal-daily-close cursor-pointer z-50">
            <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
              <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
            </svg>
          </div>
        </div>
            <div class="modal-body border-t border-white custom-scrollbar" style="max-height: 75vh;overflow-y: auto;">
        <!--Body-->
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-opacity-10 border-black">
                            <th class="text-center px-2 py-1">Waktua</th>
                            <th class="text-center px-2 py-1">Temperatur</th>
                            <th class="text-center px-2 py-1">Curah Hujan</th>
                            <th class="text-center px-2 py-1">Kondisi</th>
                        </tr>
                    </thead>
                    <tbody id="table-daily-content"></tbody>
                </table>
            </div>
      </div>
    </div>
  </div>

</main>
@endsection

@section('js')
@livewireScripts
<script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/highcharts-3d.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
<script src="https://code.highcharts.com/stock/modules/accessibility.js"></script>
  <script>
    function toggleDropdown() {
      document.getElementById("myDropdown").classList.toggle("show");
    }

    function filterFunction() {
      var input, filter, ul, li, a, i;
      input = document.getElementById("locationInput");
      filter = input.value.toUpperCase();
      div = document.getElementById("myDropdown");
      a = div.getElementsByTagName("button");
      for (i = 0; i < a.length; i++) {
        txtValue = a[i].textContent || a[i].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
          a[i].style.display = "";
        } else {
          a[i].style.display = "none";
        }
      }
    }

    var openmodal = document.querySelectorAll('.modal-daily-open')
    for (var i = 0; i < openmodal.length; i++) {
      openmodal[i].addEventListener('click', function(event){
        event.preventDefault()
        toggleModalDaily()
      })
    }
    
    const overlayDaily = document.querySelector('.modal-daily-overlay')
    overlayDaily.addEventListener('click', toggleModalDaily)
    
    var closemodal = document.querySelectorAll('.modal-daily-close')
    for (var i = 0; i < closemodal.length; i++) {
      closemodal[i].addEventListener('click', toggleModalDaily)
    }
    
    function toggleModalDaily () {
      const body = document.querySelector('body')
      const modal = document.querySelector('.modal-daily')
      modal.classList.toggle('opacity-0')
      modal.classList.toggle('pointer-events-none')
      body.classList.toggle('modal-daily-active')
    }
    function getWeatherTable(date){
        document.getElementById('daily-date').innerHTML = moment(date).format("DD MMMM YYYY");
        let table = document.getElementById('table-daily-content');
        let tr = table.getElementsByTagName('tr');
        for (var i = 0; i < tr.length; i++) {
            if(moment(date).isSame(moment(tr[i].cells[0].innerHTML), 'day')){
                tr[i].style.display = "";
            }else{
                tr[i].style.display = "none";
            }
        }        
    }
    
    document.onkeydown = function(evt) {
      evt = evt || window.event
      var isEscape = false
      if ("key" in evt) {
        isEscape = (evt.key === "Escape" || evt.key === "Esc")
      } else {
        isEscape = (evt.keyCode === 27)
      }
      if (isEscape && document.body.classList.contains('modal-daily-active')) {
        toggleModalDaily()
      }      
    };
    
</script>
<script type="text/javascript">
    Highcharts.theme = {
        "colors": ["#348f41","#7eb258","#bfd577","#fff89e","#f9bd6a","#ed8052","#d43d51"],
      "chart": {
        "style": {
          "fontFamily": "Source Sans Pro",
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
    window.addEventListener('reload-chart', event => {
        dataHourly = JSON.parse(JSON.stringify(event.detail.data));
        dataLocation = JSON.parse(JSON.stringify(event.detail.location));
        map.setView([dataLocation.lat,dataLocation.lon],11);
        Highcharts.chart('weather-chart', {
            chart: {
                zoomType: 'xy',
                backgroundColor:'rgba(0,0,0,.3)'
            },
            title: {
                text: 'Prediksi Cuaca ' + dataLocation.name + ' Hari Ini',
                style:{
                    color:'rgba(255,255,255,.75)'
                }
            },
            exporting: {
                enabled: true,
                buttons: {
                    contextButton: {
                        symbolStroke: 'rgba(255,255,255,.75)',
                        theme: {
                            fill: 'transparent',
                            stroke: 'transparent',
                            states:{
                                hover:{
                                    fill: 'rgba(0,0,0,.25)',
                                },
                                select:{
                                    fill: 'rgba(0,0,0,.25)',
                                },
                            }
                        }
                    }
                }
            },
            xAxis: [{
                categories: dataHourly.map(function (el) { return moment(el.date).format("HH:mm"); }),
                crosshair: true,
                tickInterval: 3,
            }],
            credits:false,
            yAxis: [{ // Primary yAxis
                labels: {
                    format: '{value}°C',
                    style: {
                        fontWeight:'bold',
                        color: Highcharts.getOptions().colors[5]
                    }
                },
                title: {
                    text: 'Temperatur',
                    style: {
                        color: Highcharts.getOptions().colors[5]
                    }
                }
            }, { // Secondary yAxis
                title: {
                    text: 'Curah Hujan',
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                },
                labels: {
                    format: '{value} mm',
                    style: {
                        fontWeight:'bold',
                        color: Highcharts.getOptions().colors[1]
                    }
                },
                opposite: true
            }],
            tooltip: {
                shared: true
            },
            plotOptions: {
                series: {
                    marker: {
                        radius: 3
                    }
                }
            },            
            legend: {
                layout: 'horizontal',
                margin:5,
                align: 'center',
                verticalAlign: 'top',
                floating: false,
                itemStyle:{
                    color:"rgba(255,255,255,.75)"
                }
            },
            series: [{
                name: 'Curah Hujan',
                type: 'column',
                yAxis: 1,
                data: dataHourly.map(function (el) { return parseFloat(el.rain); }),
                color: Highcharts.getOptions().colors[1],
                tooltip: {
                    valueSuffix: ' mm'
                }

            }, {
                name: 'Temperatur Udara',
                type: 'spline',
                data: dataHourly.map(function (el) { return parseFloat(el.temperature.toFixed(1)); }),
                color: Highcharts.getOptions().colors[5],
                tooltip: {
                    valueSuffix: '°C'
                }
            }]
        });

    })  
    var layerData;
    var velocityLayer;
    var storagePath = "{{ asset("") }}";
    initSidebar();
    moment.locale('id');
    initMap();
    getWeather();
    // addMapLayer('menu-title','topleft');
    addMapLayer('menu-layer','bottomright');
    addMapLayer('map-zoom','bottomright');
    addMapLayer('menu-title','topleft');
    addMapLayer('menu-top-right','topright');
    addMapLayer('menu-bottom-left','bottomleft');
    // autocomplete(document.getElementById("locationInput"), {!! json_encode($searchbar) !!});
    document.getElementById("menu-weather").classList.add("bg-white");
    document.getElementById("menu-weather").childNodes[1].childNodes[1].classList.remove("text-white");
    document.getElementById("menu-weather").childNodes[1].childNodes[1].classList.add("text-orange-500");
    document.getElementById("menu-weather").childNodes[1].childNodes[3].classList.remove("text-white");
    document.getElementById("menu-weather").childNodes[1].childNodes[3].classList.add("text-orange-500");

    // document.querySelector(".right-button-hourly").addEventListener("click", ()=>{
    //     document.querySelector(".grabbable-hourly").scrollBy({ 
    //         left: 100,  
    //         behavior: 'smooth' 
    //     });
    // });
    // document.querySelector(".left-button-hourly").addEventListener("click", ()=>{
    //     document.querySelector(".grabbable-hourly").scrollBy({ 
    //         left: -100,  
    //         behavior: 'smooth' 
    //     });
    // });

    document.querySelector(".right-button-daily").addEventListener("click", ()=>{
        document.querySelector(".grabbable-daily").scrollBy({ 
            left: 100,  
            behavior: 'smooth' 
        });
    });
    document.querySelector(".left-button-daily").addEventListener("click", ()=>{
        document.querySelector(".grabbable-daily").scrollBy({ 
            left: -100,  
            behavior: 'smooth' 
        });
    });


    // const grabbableHourly = document.querySelector('.grabbable-hourly');
    const grabbableDaily = document.querySelector('.grabbable-daily');
    let isDown = false;
    let startX;
    let scrollLeft;

    // grabbableHourly.addEventListener('mousedown', (e) => {
    //     isDown = true;
    //     grabbableHourly.classList.add('active');
    //     startX = e.pageX - grabbableHourly.offsetLeft;
    //     scrollLeft = grabbableHourly.scrollLeft;
    // });
    // grabbableHourly.addEventListener('mouseleave', () => {
    //     isDown = false;
    //     grabbableHourly.classList.remove('active');
    // });
    // grabbableHourly.addEventListener('mouseup', () => {
    //     isDown = false;
    //     grabbableHourly.classList.remove('active');
    // });
    // grabbableHourly.addEventListener('mousemove', (e) => {
    //     if(!isDown) return;
    //     e.preventDefault();
    //     const x = e.pageX - grabbableHourly.offsetLeft;
    //     const walk = (x - startX); //scroll-fast
    //     grabbableHourly.scrollLeft = scrollLeft - walk;
    // });

    grabbableDaily.addEventListener('mousedown', (e) => {
        isDown = true;
        grabbableDaily.classList.add('active');
        startX = e.pageX - grabbableDaily.offsetLeft;
        scrollLeft = grabbableDaily.scrollLeft;
    });
    grabbableDaily.addEventListener('mouseleave', () => {
        isDown = false;
        grabbableDaily.classList.remove('active');
    });
    grabbableDaily.addEventListener('mouseup', () => {
        isDown = false;
        grabbableDaily.classList.remove('active');
    });
    grabbableDaily.addEventListener('mousemove', (e) => {
        if(!isDown) return;
        e.preventDefault();
        const x = e.pageX - grabbableDaily.offsetLeft;
        const walk = (x - startX); //scroll-fast
        grabbableDaily.scrollLeft = scrollLeft - walk;
    });

    
</script>
@endsection