@extends('layouts.master')

@section('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
    <script src='//api.tiles.mapbox.com/mapbox.js/plugins/leaflet-omnivore/v0.3.1/leaflet-omnivore.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.21.0/moment-with-locales.js"></script>
@endsection
@section('content')

<main class="bg-main">
                <div id="map" style="width:800px;height: 600px;">
<!--                     <div id="map-zoom">
                        <div>
                            <div id='in' class="bg-white bg-opacity-25 mb-1 flex hover:bg-red-dark cursor-pointer" style="width: 25px;height: 25px;"><p class="cursor-pointer m-auto text-white font-bold">+</p></div>
                            <div id='out' class="bg-white bg-opacity-25 flex hover:bg-red-dark cursor-pointer" style="width: 25px;height: 25px;"><p class="cursor-pointer m-auto text-white font-bold">-</p></div>                  
                        </div>
                    </div> -->  
                    <div id="logo-container">
                        <img src="{{ asset('img/logo.png') }}" style="max-height:120px;">
                    </div>            
<!--                     <div id="legend-earthquake" class="bg-white p-2 mr-2 my-auto rounded-sm">
                            <p class="text-dark text-base font-bold">Magnitude: </p>
                            <p class="text-dark text-base" for="magnitude1"><span class="text-red-700">&#9679;</span> > 7</p>
                            <p class="text-dark text-base" for="magnitude2"><span class="text-yellow-600">&#9679;</span> 6-7</p>
                            <p class="text-dark text-base" for="magnitude3"><span class="text-yellow-300">&#9679;</span> 5-6</p>
                            <p class="text-dark text-base" for="magnitude4"><span class="text-green-500">&#9679;</span> 4-5</p>
                            <p class="text-dark text-base" for="magnitude5"><span class="text-blue-500">&#9679;</span> < 4</p>
                    </div>  --> 
                    <div id="text-container" class="bg-gray-200 bg-opacity-70 p-2 rounded-sm max-w-sm">
                            <h2 class="text-dark text-xl font-bold uppercase flex align-middle"><i class='text-yellow-400 text-4xl bx bx-broadcast my-auto' style="text-shadow:1px 1px rgba(0,0,0,.3)"></i> <span class="ml-1 my-auto">Gempa Terkini:</span> </h2>
                            <p class="text-base">Telah terjadi gempa bumi pada <span class="font-bold">{{ $data['Infogempa']['gempa']['Tanggal'] }} pukul {{ $data['Infogempa']['gempa']['Jam'] }}</span> dengan <span class="font-bold text-red">magnitude {{ $data['Infogempa']['gempa']['Magnitude'] }}</span> <span class="font-bold text-blue">kedalaman {{ $data['Infogempa']['gempa']['Kedalaman'] }}</span> di <span class="font-bold text-yellow-700">{{ $data['Infogempa']['gempa']['Wilayah'] }}</span>. {{ ($data['Infogempa']['gempa']['Dirasakan'] == "-") ? "" : "Wilayah yang berpotensi terdampak antara lain adalah <span class='font-bold'>" . $data['Infogempa']['gempa']['Dirasakan'] . "</span>" }}</p>
                            <p class="mt-2">Sumber: <span class="font-bold">BMKG</span></p>
                    </div>
                    <div id="info-container" style="width: 780px;">
                        <p class="text-white font-bold text-xl my-1">{{ $data['Infogempa']['gempa']['Tanggal'] }} {{ $data['Infogempa']['gempa']['Jam'] }}</p>
                        <div class="grid gap-1 grid-cols-1 md:grid-cols-3 flex-grow">
                            <div class="bg-gray-200 bg-opacity-80 text-center p-4">
                                <p class="text-xl font-bold flex text-center justify-center align-middle"><i class='text-4xl text-red bx bx-pulse'></i> <span class="ml-1 my-auto">{{ $data['Infogempa']['gempa']['Magnitude'] }} SR</span></p>
                                <p>Magnitude</p>
                            </div>
                            <div class="bg-gray-200 bg-opacity-80 text-center p-4">
                                <p class="text-xl font-bold flex text-center justify-center align-middle"><i class='text-4xl text-blue bx bx-water'></i> <span class="ml-1 my-auto">{{ $data['Infogempa']['gempa']['Kedalaman'] }}</span></p>
                                <p>Kedalaman</p>
                            </div>
                            <div class="bg-gray-200 bg-opacity-80 text-center p-4">
                                <p class="text-xl font-bold flex text-center justify-center align-middle"><i class='text-4xl text-green bx bx-map-pin'></i> <span class="ml-1 my-auto">{{ $data['Infogempa']['gempa']['Lintang'] }}, {{ $data['Infogempa']['gempa']['Bujur'] }}</span></p>
                                <p>Koordinat</p>
                            </div>
                        </div>
                    </div>
                </div>


</main>
@endsection

@section('js')
<script type="text/javascript">
    var earthquakeLayer;
    var earthquakeBMKGContainer;
    initMap();
    addMapLayer('logo-container','topleft');
    addMapLayer('info-container','bottomleft');
    addMapLayer('text-container','topright');
    // addMapLayer('legend-earthquake','bottomright');
    data = {!! json_encode($data) !!};
    getLatestEarthquake();
</script>
@endsection