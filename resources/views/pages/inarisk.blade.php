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
                        <div class="my-auto mt-2 border-t">
                            <img src="{{ asset('img/logo-inaRISK.png') }}" class="mt-4" style="max-height:60px;">
                        </div>                        
                    </div>
                    <div id="map-zoom">
                        <div class="flex">
                            <div>
                                <div id='in' class="bg-white bg-opacity-30 mb-1 flex hover:bg-orange-500 cursor-pointer" style="width: 25px;height: 25px;"><p class="cursor-pointer m-auto text-white font-bold">+</p></div>
                                <div id='out' class="bg-white bg-opacity-30 flex hover:bg-orange-500 cursor-pointer" style="width: 25px;height: 25px;"><p class="cursor-pointer m-auto text-white font-bold">-</p></div>                  
                            </div>
                            <div class="bg-white bg-opacity-30 px-2 mt-auto ml-1 h-full">
                                
                                <img src="{{ asset('img/logo-inaRISK-full.png') }}">
                            </div>
                        </div>
                    </div>
                    <div id="menu-top-right" class="flex items-end">
                        
                        <div class="bg-black bg-opacity-25 p-2 mr-2 rounded-sm shadow-xs shadow-paleblue text-right">
                            <p class="text-white font-bold mr-2 mb-1 text-orange-500">Layer inaRISK</p>
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
                            <hr class="my-1 border-gray-300 border-opacity-30">
                            <div class="grid grid-cols-2">
                                <div class="mr-2 my-1 flex">
                                    <input type="radio" name="layer-inarisk-hazard" class="my-auto" data-text="Banjir" checked value='banjir' onclick="getInariskHazard(this.value);">
                                    <label class="ml-1 my-auto text-white text-xs">Banjir</label>
                                </div>
                                <div class="mr-2 my-1 flex">
                                    <input type="radio" name="layer-inarisk-hazard" class="my-auto" data-text="Banjir Bandang" value='banjir_bandang' onclick="getInariskHazard(this.value);">
                                    <label class="ml-1 my-auto text-white text-xs">Banjir Bandang</label>
                                </div>
                                <div class="mr-2 my-1 flex">
                                    <input type="radio" name="layer-inarisk-hazard" class="my-auto" data-text="Tanah Longsor" value='tanah_longsor' onclick="getInariskHazard(this.value);">
                                    <label class="ml-1 my-auto text-white text-xs">Tanah Longsor</label>
                                </div>
                                <div class="mr-2 my-1 flex">
                                    <input type="radio" name="layer-inarisk-hazard" class="my-auto" data-text="Cuaca Ekstrem" value='cuaca_ekstrim' onclick="getInariskHazard(this.value);">
                                    <label class="ml-1 my-auto text-white text-xs">Cuaca Ekstrem</label>
                                </div>
                                <div class="mr-2 my-1 flex">
                                    <input type="radio" name="layer-inarisk-hazard" class="my-auto" data-text="Karhutla" value='kebakaran_hutan_dan_lahan' onclick="getInariskHazard(this.value);">
                                    <label class="ml-1 my-auto text-white text-xs">Karhutla</label>
                                </div>
                                <div class="mr-2 my-1 flex">
                                    <input type="radio" name="layer-inarisk-hazard" class="my-auto" data-text="Gunung Api" value='letusan_gunungapi' onclick="getInariskHazard(this.value);">
                                    <label class="ml-1 my-auto text-white text-xs">Gunung Api</label>
                                </div>
                                <div class="mr-2 my-1 flex">
                                    <input type="radio" name="layer-inarisk-hazard" class="my-auto" data-text="Gempa Bumi" value='gempabumi' onclick="getInariskHazard(this.value);">
                                    <label class="ml-1 my-auto text-white text-xs">Gempa Bumi</label>
                                </div>
                                <div class="mr-2 my-1 flex">
                                    <input type="radio" name="layer-inarisk-hazard" class="my-auto" data-text="Tsunami" value='tsunami' onclick="getInariskHazard(this.value);">
                                    <label class="ml-1 my-auto text-white text-xs">Tsunami</label>
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
<script type="text/javascript">
function toggleInfo() {
    let info = document.getElementById("info-detail");
    info.classList.toggle("hidden");
}

    var earthquakeLayer;
    var earthquakeBMKGContainer;
    var layerData;
    initMap();
    addMapLayer('menu-top-right','topright');
    addMapLayer('map-zoom','bottomleft');
    addMapLayer('menu-title','topleft');
    initSidebar();
    moment.locale('id');
    document.getElementById("menu-inarisk").classList.add("bg-white");
    document.getElementById("menu-inarisk").childNodes[1].childNodes[1].classList.remove("text-white");
    document.getElementById("menu-inarisk").childNodes[1].childNodes[1].classList.add("text-orange-500");
    document.getElementById("menu-inarisk").childNodes[1].childNodes[3].classList.remove("text-white");
    document.getElementById("menu-inarisk").childNodes[1].childNodes[3].classList.add("text-orange-500");

</script>
@endsection