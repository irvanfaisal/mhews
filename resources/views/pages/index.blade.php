@extends('layouts.master')
@section('content')
@include('layouts.loader')
@section('head')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.21.0/moment-with-locales.js"></script>
@endsection
<!-- @include('layouts.header') -->
<main class="bg-white">
    <section class="accordion">
        <ul>
            <li>
                <input id="rad1" type="radio" name="rad" onchange="changeIcon(0)">
                <label class="bg-orange-900 hover:bg-orange-700" for="rad1">
                    <div class="h-full items-center flex justify-center">
                        <div class="h-full flex flex-col justify-between">
                            <p class="p-2" style="writing-mode: vertical-rl;transform: rotate(-180deg);opacity: 0;">Fitur Pelengkap</p>
                            <i class='bx bxs-right-arrow mx-auto'></i>
                            <p class="p-2" style="writing-mode: vertical-rl;transform: rotate(-180deg);">Fitur Pelengkap</p>
                        </div>
                    </div>
                </label>

                <div class="accslide">
                    <div class="content bg-white bg-home h-screen p-5 flex flex-col justify-between">
                        <div class="mt-2">
                            <div class="grid grid-cols-1 md:grid-cols-2 items-start pb-5">
                                <div class="h-full">
                                    <div class="flex items-start">
                                        <img src="{{ asset('img/BNPB.png') }}" class="h-20 mr-2">
                                        <img src="{{ asset('img/logo-dark.png') }}" class="h-20 mr-2">
                                    </div>
                                </div>
                            </div>
                            <div class="grid gap-4 grid-cols-1 md:grid-cols-3">
                                <div class="px-1">
                                    <div class="card-container flex justify-content-center">
                                        <a href="{{ url('satellite') }}" id="feature-1" class="card-feature bg-orange-800">
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                            <div class="card-content p-2">
                                                <h2 class="text-2xl">01</h2>
                                                <i class='text-white bx bx-rss text-4xl'></i>
                                                <h6 class="my-auto font-bold uppercase text-white">Observasi Satelit</h6>
                                                <hr class="my-2 w-20 mx-auto">
                                                <p class="text-xs text-white">Pengamatan satelit secara <i>real-time</i> untuk observasi cuaca dan kebakaran hutan</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="px-1">
                                    <div class="card-container flex justify-content-center">
                                        <a href="{{ url('radar') }}" id="feature-2" class="card-feature bg-orange-700">
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                            <div class="card-content p-2">
                                                <h2 class="text-2xl">02</h2>
                                                <i class='text-white bx bx-radar text-4xl'></i>
                                                <h6 class="my-auto font-bold uppercase text-white">Radar Cuaca</h6>
                                                <hr class="my-2 w-20 mx-auto">
                                                <p class="text-xs text-white">Pengamatan curah hujan secara <i>real-time</i> menggunakan teknologi radar cuaca</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="px-1">
                                    <div class="card-container flex justify-content-center">
                                        <a href="{{ url('observation') }}" id="feature-3" class="card-feature bg-orange-600">
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                            <div class="card-content p-2">
                                                <h2 class="text-2xl">03</h2>
                                                <i class='text-white bx bx-bar-chart text-4xl'></i>
                                                <h6 class="my-auto font-bold uppercase text-white">Tinggi Muka Air</h6>
                                                <hr class="my-2 w-20 mx-auto">
                                                <p class="text-xs text-white">Pengamatan tinggi muka air secara <i>real-time</i> di seluruh Indonesia</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="px-1">
                                    <div class="card-container flex justify-content-center">
                                        <a href="{{ url('dibi') }}" id="feature-4" class="card-feature bg-orange-500">
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                            <div class="card-content p-2">
                                                <h2 class="text-2xl">04</h2>
                                                <i class='text-white bx bx-book text-4xl'></i>
                                                <h6 class="my-auto font-bold uppercase text-white">DIBI</h6>
                                                <hr class="my-2 w-20 mx-auto">
                                                <p class="text-xs text-white">Data Informasi Bencana Indonesia</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="px-1">
                                    <div class="card-container flex justify-content-center">
                                        <a href="{{ url('inarisk') }}" id="feature-4" class="card-feature bg-orange-400">
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                            <div class="card-content p-2">
                                                <h2 class="text-2xl">05</h2>
                                                <i class='text-white bx bx-bookmark text-4xl'></i>
                                                <h6 class="my-auto font-bold uppercase text-white">inaRISK</h6>
                                                <hr class="my-2 w-20 mx-auto">
                                                <p class="text-xs text-white">Kajian risiko bencana Indonesia</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <input id="rad2" type="radio" name="rad" onchange="changeIcon(1)">
                <label class="bg-orange-800 hover:bg-orange-700" for="rad2">
                    <div class="h-full items-center flex justify-center">
                        <div class="h-full flex flex-col justify-between">
                            <p class="p-2" style="writing-mode: vertical-rl;transform: rotate(-180deg);opacity: 0;">Gunung Api</p>
                            <i class='bx bxs-right-arrow mx-auto'></i>
                            <p class="p-2" style="writing-mode: vertical-rl;transform: rotate(-180deg);">Gunung Api</p>
                        </div>
                    </div>
                </label>
                <div class="accslide">
                    <div class="content bg-white bg-home h-screen p-5 flex flex-col justify-between">
                        <div class="mt-2">
                            <div class="flex justify-between">
                                <div>
                                    <h1 class="font-bold uppercase"><span class="text-3xl">Pengamatan</span><br><span class="text-5xl">Gunung Api</span></h1>
                                    <hr class="w-5/6 my-2">
                                    <p>Monitoring <span class="text-red-500 font-bold">gunung api</span> terkini secara <span class="text-blue-500 font-bold"><i>real-time</i></span> di Indonesia</p>
                                </div>
                                <div class="flex">
                                    <img src="{{ asset('img/BNPB.png') }}" class="h-20 mr-2">
                                    <img src="{{ asset('img/logo-dark.png') }}" class="h-20 mr-2">
                                </div>
                            </div>
                            <div class="grid gap-2 grid-cols-1 md:grid-cols-2 my-2">
                                <div>
                                    <div class="flex flex-col flex-nowrap">
                                        <div>
                                            <nav class="tabs-volcano flex flex-col sm:flex-row">
                                                <button data-target="panel-1-volcano" class="tab-volcano font-bold active bg-orange-500 whitespace-nowrap text-xs py-2 px-4 block hover:bg-orange-500 hover:bg-opacity-50 focus:outline-none flex-grow border-b border-r border-orange-500">
                                                    Laporan Aktivitas
                                                </button>
                                                <button data-target="panel-2-volcano" class="tab-volcano font-bold whitespace-nowrap text-xs py-2 px-4 block hover:bg-orange-500 hover:bg-opacity-50 focus:outline-none flex-grow border-b border-orange-500">
                                                    Informasi Letusan
                                                </button>
                                            </nav>
                                        </div>
                                        <div id="panels-volcano" class="flex-grow flex-nowrap">
                                            <div class="panel-1-volcano active tab-volcano-content h-48">
                                                <div class="p-2">
                                                    <div class="h-72 overflow-y-auto">
                                                        <livewire:activity />
                                                    </div>
                                                    <hr class="border-orange-500 border-opacity-50 mt-2">
                                                </div> 
                                            </div>
                                            <div class="panel-2-volcano tab-volcano-content h-48">
                                                <div class="p-2">
                                                    <div class="h-72 overflow-y-auto">
                                                        <livewire:eruption />
                                                    </div>
                                                    <hr class="border-orange-500 border-opacity-50 mt-2">
                                                </div> 
                                            </div>
                                        </div>   
                                    </div>                    
                                </div>                                
                                <div class="grid gap-5 grid-cols-1 md:grid-cols-2 mb-2">
                                    <div class="border-2 border-red-500 bg-animation-red bg-opacity-70 border-opacity-70 shadow-paleblue p-2 my-auto h-full">
                                        <div class="flex justify-between">
                                            <div class="my-auto">
                                                <p class="text-black uppercase font-bold">Awas</p>
                                                <p class="text-black text-5xl"><i class='bx bxs-flame'></i></p>
                                            </div>
                                            <div class="my-auto">
                                                <p class="text-black text-4xl text-right font-bold count-hazard">{{ $data['volcano']->where("ga_status",4)->count() }}</p>
                                                <p class="text-black text-sm text-right">Gunung</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border-2 border-orange-700 bg-orange-700 bg-opacity-70 border-opacity-70 shadow-paleblue p-2 my-auto h-full">
                                        <div class="flex justify-between">
                                            <div class="my-auto">
                                                <p class="text-black uppercase font-bold">Siaga</p>
                                                <p class="text-black text-5xl"><i class='bx bxs-flame'></i></p>
                                            </div>
                                            <div class="my-auto">
                                                <p class="text-black text-4xl text-right font-bold count-hazard">{{ $data['volcano']->where("ga_status",3)->count() }}</p>
                                                <p class="text-black text-sm text-right">Gunung</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border-2 border-yellow-400 bg-yellow-400 bg-opacity-70 border-opacity-70 shadow-paleblue p-2 my-auto h-full">
                                        <div class="flex justify-between">
                                            <div class="my-auto">
                                                <p class="text-black uppercase font-bold">Waspada</p>
                                                <p class="text-black text-5xl"><i class='bx bxs-flame'></i></p>
                                            </div>
                                            <div class="my-auto">
                                                <p class="text-black text-4xl text-right font-bold count-hazard">{{ $data['volcano']->where("ga_status",2)->count() }}</p>
                                                <p class="text-black text-sm text-right">Gunung</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border-2 border-green-600 bg-green-600 bg-opacity-70 border-opacity-70 shadow-paleblue p-2 my-auto h-full">
                                        <div class="flex justify-between">
                                            <div class="my-auto">
                                                <p class="text-black uppercase font-bold">Normal</p>
                                                <p class="text-black text-5xl"><i class='bx bxs-flame'></i></p>
                                            </div>
                                            <div class="my-auto">
                                                <p class="text-black text-4xl text-right font-bold count-hazard">{{ $data['volcano']->where("ga_status",1)->count() }}</p>
                                                <p class="text-black text-sm text-right">Gunung</p>
                                            </div>
                                        </div>
                                    </div>                                                
                                </div>
                            </div>                            
                        </div>
                        <div>
                            <a href="{{ url('volcano
                            ') }}" onclick="leavingAnimation();" class="inline-block text-white bg-orange-500 px-10 py-3 mt-5 float-right hover:bg-orange-600 shadow-md shadow-paleblue">Dashboard</a>
                        </div>
                    </div>
                </div>
            </li>            
            <li>
                <input id="rad3" type="radio" name="rad" onchange="changeIcon(2)">
                <label class="bg-orange-700 hover:bg-orange-700" for="rad3">
                    <div class="h-full items-center flex justify-center">
                        <div class="h-full flex flex-col justify-between">
                            <p class="p-2" style="writing-mode: vertical-rl;transform: rotate(-180deg);opacity: 0;">Gempa Bumi</p>
                            <i class='bx bxs-right-arrow mx-auto'></i>
                            <p class="p-2" style="writing-mode: vertical-rl;transform: rotate(-180deg);">Gempa Bumi</p>
                        </div>
                    </div>
                </label>
                <div class="accslide">
                    <div class="content bg-white bg-home h-screen p-5 flex flex-col justify-between">
                        <div class="mt-2">
                            <div class="flex justify-between">
                                <div>
                                    <h1 class="font-bold uppercase"><span class="text-3xl">Pengamatan</span><br><span class="text-5xl">Gempa Bumi</span></h1>
                                    <hr class="w-5/6 my-2">
                                    <p>Monitoring <span class="text-green-500 font-bold">gempa bumi</span> terkini secara <span class="text-blue-500 font-bold"><i>real-time</i></span> di Indonesia</p>
                                </div>
                                <div class="flex justify-end">
                                    <img src="{{ asset('img/BNPB.png') }}" class="h-20 mr-2">
                                    <img src="{{ asset('img/logo-dark.png') }}" class="h-20 mr-2">
                                </div>
                            </div>
                            <div class="grid gap-1 grid-cols-1 md:grid-cols-2 flex-grow mt-5 items-start justify-between">
                                <div class="mt-4">
                                    <div class="flex flex-col flex-nowrap">
                                        <div>
                                            <nav class="tabs-earthquake flex flex-col sm:flex-row">
                                                <button data-target="panel-1-earthquake" class="tab-earthquake font-bold whitespace-nowrap text-xs text-black py-2 px-4 block hover:bg-orange-500 hover:bg-opacity-50 focus:outline-none flex-grow border-b border-r border-orange-500">
                                                    24 Jam Terakhir
                                                </button>
                                                <button data-target="panel-2-earthquake" class="tab-earthquake active bg-orange-500 font-bold whitespace-nowrap text-xs text-black py-2 px-4 block hover:bg-orange-500 hover:bg-opacity-50 focus:outline-none flex-grow border-b border-r border-orange-500">
                                                    7 Hari Terakhir
                                                </button>
                                                <button data-target="panel-3-earthquake" class="tab-earthquake font-bold whitespace-nowrap text-xs text-black py-2 px-4 block hover:bg-orange-500 hover:bg-opacity-50 focus:outline-none flex-grow border-b border-orange-500">
                                                    1 Bulan Terakhir
                                                </button>
                                            </nav>
                                        </div>
                                        <div id="panels-earthquake" class="flex-grow flex-nowrap">
                                            <div class="panel-1-earthquake tab-earthquake-content">
                                                <div class="p-2">
                                                    <div class="h-52 overflow-y-auto">                           
                                                        <table class="table-auto text-black text-xs w-full">
                                                            <thead>
                                                                <tr>
                                                                    <th class="p-1 bg-green-600 bg-opacity-80 border-b border-gray-400 border-opacity-20 text-center">No</th>
                                                                    <th class="p-1 bg-green-600 bg-opacity-80 border-b border-gray-400 border-opacity-20 text-left">Lokasi</th>
                                                                    <th class="p-1 bg-green-600 bg-opacity-80 border-b border-gray-400 border-opacity-20 text-center">Magnitude</th>
                                                                    <th class="p-1 bg-green-600 bg-opacity-80 border-b border-gray-400 border-opacity-20 text-center">Kedalaman</th>
                                                                    <th class="p-1 bg-green-600 bg-opacity-80 border-b border-gray-400 border-opacity-20 text-center">Tanggal</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>

                                                                @php $count=1 @endphp
                                                                @foreach($data['bmkg'] as $key => $value)
                                                                @php $toggle = false @endphp
                                                                    @if(\Carbon\Carbon::parse($value['waktu']) >= \Carbon\Carbon::now()->subhour(24))
                                                                        <tr class="table-bmkg {{ ($toggle ? 'bg-gray-400 bg-opacity-30' : '') }}">
                                                                            <td class="p-1 border-b border-gray-400 border-opacity-20 text-center">{{$count}}</td>
                                                                            <td class="p-1 border-b border-gray-400 border-opacity-20">{{ $value['Keterangan'] }}</td>
                                                                            <td class="p-1 border-b border-gray-400 border-opacity-20 text-center">{{ number_format($value['Magnitude'],1,",",".") }}</td>
                                                                            <td class="p-1 border-b border-gray-400 border-opacity-20 text-center">{{ number_format(floatVal($value['Kedalaman']),1,",",".") }} KM</td>
                                                                            <td class="p-1 border-b border-gray-400 border-opacity-20 text-center whitespace-nowrap">{{ \Carbon\Carbon::parse($value['waktu']) }}</td>
                                                                        </tr>
                                                                        @php $toggle = ($toggle == true) ? false : true @endphp
                                                                        @php $count++ @endphp
                                                                    @endif
                                                                @endforeach   
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <hr class="border-orange-500 border-opacity-50 mt-2">
                                                </div> 
                                            </div>
                                            <div class="panel-2-earthquake active tab-earthquake-content">
                                                <div class="p-2 bg-opacity-50">
                                                    <div class="h-52 overflow-y-auto">                           
                                                        <table class="table-auto text-black text-xs w-full">
                                                            <thead>
                                                                <tr>
                                                                    <th class="p-1 bg-green-600 bg-opacity-80 border-b border-gray-400 border-opacity-20 text-center">No</th>
                                                                    <th class="p-1 bg-green-600 bg-opacity-80 border-b border-gray-400 border-opacity-20 text-left">Lokasi</th>
                                                                    <th class="p-1 bg-green-600 bg-opacity-80 border-b border-gray-400 border-opacity-20 text-center">Magnitude</th>
                                                                    <th class="p-1 bg-green-600 bg-opacity-80 border-b border-gray-400 border-opacity-20 text-center">Kedalaman</th>
                                                                    <th class="p-1 bg-green-600 bg-opacity-80 border-b border-gray-400 border-opacity-20 text-center">Tanggal</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>

                                                                @php $count=1 @endphp
                                                                @php $toggle = false @endphp
                                                                @foreach($data['bmkg'] as $key => $value)
                                                                    @if(\Carbon\Carbon::parse($value['waktu']) >= \Carbon\Carbon::now()->subday(7))
                                                                        <tr class="table-bmkg {{ ($toggle ? 'bg-gray-400 bg-opacity-30' : '') }}">
                                                                            <td class="p-1 border-b border-gray-400 border-opacity-20 text-center">{{$count}}</td>
                                                                            <td class="p-1 border-b border-gray-400 border-opacity-20">{{ $value['Keterangan'] }}</td>
                                                                            <td class="p-1 border-b border-gray-400 border-opacity-20 text-center">{{ number_format($value['Magnitude'],1,",",".") }}</td>
                                                                            <td class="p-1 border-b border-gray-400 border-opacity-20 text-center">{{ number_format(floatVal($value['Kedalaman']),1,",",".") }} KM</td>
                                                                            <td class="p-1 border-b border-gray-400 border-opacity-20 text-center whitespace-nowrap">{{ \Carbon\Carbon::parse($value['waktu']) }}</td>
                                                                        </tr>
                                                                        @php $toggle = ($toggle == true) ? false : true @endphp
                                                                        @php $count++ @endphp
                                                                    @endif
                                                                @endforeach   
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <hr class="border-orange-500 border-opacity-50 mt-2">
                                                </div> 
                                            </div>
                                            <div class="panel-3-earthquake tab-earthquake-content">
                                                <div class="p-2 bg-opacity-50">
                                                    <div class="h-52 overflow-y-auto">                           
                                                        <table class="table-auto text-black text-xs w-full">
                                                            
                                                            <thead>
                                                                <tr>
                                                                    <th class="p-1 bg-green-600 bg-opacity-80 border-b border-gray-400 border-opacity-20 text-center">No</th>
                                                                    <th class="p-1 bg-green-600 bg-opacity-80 border-b border-gray-400 border-opacity-20 text-left">Lokasi</th>
                                                                    <th class="p-1 bg-green-600 bg-opacity-80 border-b border-gray-400 border-opacity-20 text-center">Magnitude</th>
                                                                    <th class="p-1 bg-green-600 bg-opacity-80 border-b border-gray-400 border-opacity-20 text-center">Kedalaman</th>
                                                                    <th class="p-1 bg-green-600 bg-opacity-80 border-b border-gray-400 border-opacity-20 text-center">Tanggal</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @php $count=1 @endphp
                                                                @php $toggle = false @endphp
                                                                @foreach($data['bmkg'] as $key => $value)
                                                                    @if(\Carbon\Carbon::parse($value['waktu']) >= \Carbon\Carbon::now()->submonth(1))
                                                                        <tr class="table-bmkg {{ ($toggle ? 'bg-gray-400 bg-opacity-30' : '') }}">
                                                                            <td class="p-1 border-b border-gray-400 border-opacity-20 text-center">{{$count}}</td>
                                                                            <td class="p-1 border-b border-gray-400 border-opacity-20">{{ $value['Keterangan'] }}</td>
                                                                            <td class="p-1 border-b border-gray-400 border-opacity-20 text-center">{{ number_format($value['Magnitude'],1,",",".") }}</td>
                                                                            <td class="p-1 border-b border-gray-400 border-opacity-20 text-center">{{ number_format(floatVal($value['Kedalaman']),1,",",".") }} KM</td>
                                                                            <td class="p-1 border-b border-gray-400 border-opacity-20 text-center whitespace-nowrap">{{ \Carbon\Carbon::parse($value['waktu']) }}</td>
                                                                        </tr>
                                                                        @php $toggle = ($toggle == true) ? false : true @endphp
                                                                        @php $count++ @endphp
                                                                    @endif
                                                                @endforeach 
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <hr class="border-orange-500 border-opacity-50 mt-2">
                                                </div> 
                                            </div>
                                        </div>   
                                    </div>                    
                                </div> 
                                <div class="bg-animation-red p-2">
                                    <div>
                                        <div class="py-1 justify-between flex">
                                            <div>
                                                
                                                <p class="font-bold text-white uppercase">Informasi Gempa Bumi Terkini</p>
                                                <!-- <p class="text-white">{{ \Carbon\Carbon::now()->translatedFormat("d F Y") }}</p> -->
                                                <p class="text-white text-sm">{{ $data['earthquakeLatest']['Tanggal'] }} {{ $data['earthquakeLatest']['Jam'] }}</p>
                                                <p class="text-white text-sm">{{ $data['earthquakeLatest']['Wilayah'] }}</p>
                                            </div>
                                        </div>
                                        <div id="info-container">
                                            <div class="grid gap-1 grid-cols-1 md:grid-cols-3 flex-grow">
                                                <div class="bg-gray-200 bg-opacity-80 text-center p-1">
                                                    <p class="text-sm font-bold flex text-center justify-center align-middle"><i class='text-4xl text-red bx bx-pulse'></i> <span class="ml-1 my-auto">{{ $data['earthquakeLatest']['Magnitude'] }} SR</span></p>
                                                    <p class="text-sm">Magnitude</p>
                                                </div>
                                                <div class="bg-gray-200 bg-opacity-80 text-center p-1">
                                                    <p class="text-sm font-bold flex text-center justify-center align-middle"><i class='text-4xl text-blue bx bx-water'></i> <span class="ml-1 my-auto">{{ $data['earthquakeLatest']['Kedalaman'] }}</span></p>
                                                    <p class="text-sm">Kedalaman</p>
                                                </div>
                                                <div class="bg-gray-200 bg-opacity-80 text-center p-1">
                                                    <p class="text-sm font-bold flex text-center justify-center align-middle"><i class='text-4xl text-green bx bx-map-pin'></i> <span class="ml-1 my-auto"></span></p>
                                                    <p class="text-sm">{{ $data['earthquakeLatest']['Lintang'] }}, {{ $data['earthquakeLatest']['Bujur'] }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>                                               
                            </div>                            
                                            <div>
                            <a href="{{ url('earthquake
                            ') }}" onclick="leavingAnimation();" class="inline-block text-white bg-orange-500 px-10 py-3 mt-5 float-right hover:bg-orange-600 shadow-md shadow-paleblue">Dashboard</a>
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <input id="rad4" type="radio" name="rad"/ onchange="changeIcon(3)">
                <label class="bg-orange-600 hover:bg-orange-700" for="rad4">
                    <div class="h-full items-center flex justify-center">
                        <div class="h-full flex flex-col justify-between">
                            <p class="p-2" style="writing-mode: vertical-rl;transform: rotate(-180deg);opacity: 0;">Prediksi Cuaca</p>
                            <i class='bx bxs-right-arrow mx-auto'></i>
                            <p class="p-2" style="writing-mode: vertical-rl;transform: rotate(-180deg);">Prediksi Cuaca</p>
                        </div>
                    </div>
                </label>
                <div class="accslide">
                    <div class="content bg-white bg-home h-screen p-5 flex flex-col justify-between">
                        <div class="mt-2">
                            <div class="flex justify-between">
                                <div>
                                    <h1 class="font-bold uppercase"><span class="text-5xl">Prediksi Cuaca</span></h1>
                                    <hr class="w-5/6 my-2">
                                    <p class="w-5/6 mb-2">Sistem prediksi <span class="font-bold text-blue-400">Cuaca</span> di Indonesia dengan <span class="font-bold text-orange-600">akurasi</span> dan <span class="font-bold text-red-600">resolusi tinggi</span> hingga <span class="font-bold text-green-500">6 hari</span> kedepan</p>
                                </div>
                                <div class="flex justify-end">
                                    <img src="{{ asset('img/BNPB.png') }}" class="h-20 mr-2">
                                    <img src="{{ asset('img/logo-dark.png') }}" class="h-20 mr-2">
                                </div>
                            </div>
                            <div class="inline-block mb-4 bg-green-600 bg-opacity-80 p-2">
                                <div>
                                    <div class="flex flex-col justify-start">                      
                                        <h1 class="text-white text-2xl font-bold">{{$weather['location']->name}}</h1>
                                    </div>
                                    <div class="flex justify-between">
                                        <div>
                                            <h2 class="text-white ml-1">{{ \Carbon\Carbon::create($weather['date'])->translatedFormat("d F Y H:i") }}</h2>
                                            <p class="text-white text-3xl font-bold text-red-700 ml-1">{{ number_format($weather['hourly']->where('date',">=",\Carbon\Carbon::now()->format('Y-m-d H:00:00'))->first()->temperature, 1) }}<sup>o</sup>C</p>
                                            <p class="text-white uppercase font-bold text-yellow-300 ml-1">{{ ($weather['hourly']->where('date',">=",\Carbon\Carbon::now()->format('Y-m-d H:00:00'))->first()->rain < 1 ? "Cerah" : ($weather['hourly']->where('date',">=",\Carbon\Carbon::now()->format('Y-m-d H:00:00'))->first()->rain < 5 ? "Hujan Ringan" : ($weather['hourly']->where('date',">=",\Carbon\Carbon::now()->format('Y-m-d H:00:00'))->first()->rain < 10 ? "Hujan Sedang" : ($weather['hourly']->where('date',">=",\Carbon\Carbon::now()->format('Y-m-d H:00:00'))->first()->rain < 20 ? "Hujan Lebat" : "Hujan Sangat Lebat")))) }}</p>
                                            <button class="modal-daily-open text-white text-sm ml-1 hover:bg-yellow-400 hover:text-black" onclick="getWeatherTable('{{ \Carbon\Carbon::now()->format("Y-m-d")}}')"><i class='bx bx-book-reader'></i> Cuaca Hari Ini</button>
                                        </div>
                                        <div class="ml-10 my-auto">
                                            <img src="{{ asset('img/' . ($weather['hourly']->where('date','>=',\Carbon\Carbon::now()->format('Y-m-d H:00:00'))->first()->rain < 1 ? 'day-clear' : 'day-rain') . '.png') }}" class="flex w-28">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div></div>
                            <div class="block">                    
                                <p class="text-black uppercase font-bold text-blue">Prediksi Cuaca 24 Jam ke Depan</p>
                                <div class="flex mt-2">
                                    <button class="left left-button-daily forecast-container mr-1 border border-opacity-25 shadow-xs shadow-paleblue bg-opacity-80 bg-green-600 hover:bg-green-700" style="width:20px;">
                                        <p class="my-auto text-white font-bold"><</p>
                                    </button>                                      
                                    <div id="forecast-daily" class="flex grabbable grabbable-daily w-full" style="overflow-x: scroll;">
                                            @foreach($weather['hourly']->where('date',">=",\Carbon\Carbon::now()->format('Y-m-d H:00:00'))->where('date',"<=",\Carbon\Carbon::now()->addHours(24)->format('Y-m-d H:00:00')) as $value)
                                                @if ($loop->first) @continue @endif
                                                <div class="text-center mx-1 p-1 px-2 border bg-green-600 bg-opacity-80 border-green-600 border-opacity-25 hover:bg-green-700 hover:bg-opacity-80" style="min-width:120px;">
                                                    <div>
                                                        <p class="my-auto text-white text-sm">{{ \Carbon\Carbon::create($value->date)->translatedFormat("d F") }}</p>
                                                        <p class="my-auto text-white text-sm">{{ \Carbon\Carbon::create($value->date)->translatedFormat("H:i") }}</p>
                                                        <img src="{{ URL::asset('img/' . ($value->rain < 1 ? 'day-clear' : 'day-rain') . '.png') }}" class="h-10 m-auto">
                                                        <p class="my-auto text-white text-sm">{{ ($value->rain < 1 ? "Cerah" : ($value->rain < 5 ? "Hujan Ringan" : ($value->rain < 10 ? "Hujan Sedang" : ($value->rain < 20 ? "Hujan Lebat" : "Hujan Sangat Lebat")))) }}</p>
                                                        <p class="my-auto text-white text-sm"><span>{{ number_format($value->temperature, 1) }}</span><span><sup>o</sup>C</span></p>
                                                    </div>
                                                </div>
                                            @endforeach
                                    </div>

                                    <button class="right right-button-daily forecast-container ml-1 border border-opacity-25 shadow-xs shadow-paleblue bg-opacity-80 bg-green-600 hover:bg-green-700" style="width:20px;">
                                        <p class="my-auto text-white font-bold">></p>
                                    </button>                                    
                                </div>
                            </div>
                            <div></div>
                            <a href="{{ url('weather') }}" onclick="leavingAnimation();" class="inline-block mt-2 text-white bg-orange-500 px-10 py-3 float-right hover:bg-orange-600 shadow-md shadow-paleblue">Dashboard</a>                            
                        </div>
                    </div>
                </div>
                 <!--Modal-->
                  <div class="modal-daily opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center z-10">
                    <div class="modal-daily-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>
                    
                    <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">
                      
                      <!-- Add margin if you want to see some of the overlay behind the modal-->
                      <div class="modal-content py-4 text-left px-2">
                        <!--Title-->
                        <div class="flex justify-between items-center pb-3">
                            <div>
                                <p class="text-xl font-bold uppercase" id="daily-title">-</p>
                                <p class="text-xs font-bold" id="daily-date">-</p>
                            </div>
                          <div class="modal-daily-close cursor-pointer z-50">
                            <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                              <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
                            </svg>
                          </div>
                        </div>
                            <div class="modal-body custom-scrollbar" style="max-height: 75vh;overflow-y: auto;">
                        <!--Body-->
                                <table class="w-full text-xs">
                                    <thead>
                                        <tr class="border-b border-opacity-10 border-white bg-orange-500 text-white">
                                            <th class="text-center px-2 py-1">Waktu</th>
                                            <th class="text-center px-2 py-1">Temperatur (<sup>o</sup>C)</th>
                                            <th class="text-center px-2 py-1">Curah Hujan (mm)</th>
                                            <th class="text-center px-2 py-1">Kondisi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-daily-content"></tbody>
                                </table>
                            </div>
                      </div>
                    </div>
                  </div>

            </li>
            <li>
                <input id="rad5" type="radio" name="rad" onchange="changeIcon(4)">
                <label class="bg-orange-500 hover:bg-orange-700" for="rad5">
                    <div class="h-full items-center flex justify-center">
                        <div class="h-full flex flex-col justify-between">
                            <p class="p-2" style="writing-mode: vertical-rl;transform: rotate(-180deg);opacity: 0;">Kebakaran Hutan & Lahan</p>
                            <i class='bx bxs-right-arrow mx-auto'></i>
                            <p class="p-2" style="writing-mode: vertical-rl;transform: rotate(-180deg);">Kebakaran Hutan & Lahan</p>
                        </div>
                    </div>
                </label>
                <div class="accslide">
                    <div class="content bg-white bg-home h-screen p-5 flex flex-col justify-between">
                        <div class="mt-2">
                            <div class="flex justify-between">
                                <div>
                                    <h1 class="font-bold uppercase"><span class="text-3xl">Potensi Kebakaran</span><br><span class="text-5xl">Hutan & Lahan</span></h1>
                                    <hr class="w-5/6 my-2">
                                    <p class="w-5/6">Deteksi <span class="text-red-600 font-extrabold">titik panas</span> serta potensi <span class="text-yellow-500 font-extrabold">kebakaran hutan dan lahan</span> di Indonesia untuk rekomendasi langkah pencegahan dan penanganan secara <span class="text-blue-600 font-extrabold">tepat</span> dan <span class="text-green-600 font-extrabold">akurat</span></p>
                                </div>
                                <div class="flex justify-end">
                                    <img src="{{ asset('img/BNPB.png') }}" class="h-20 mr-2">
                                    <img src="{{ asset('img/logo-dark.png') }}" class="h-20 mr-2">
                                </div>
                            </div>
                            <div class="grid gap-2 grid-cols-1 md:grid-cols-2">

                                <div class="my-2 flex flex-col">
                                    <div class="my-1 max-w-xs">
                                        <div class="flex justify-between mb-1">                                
                                            <p>Total Titik Panas</p>
                                            <p class="text-blue-600 font-bold">{{ collect($data['hotspot'])->count() }}</p>
                                        </div>
                                        <div class="border border-blue-600 border-opacity-25 rounded-xl">
                                            <div style="height:5px;width:100%;" class="bg-blue-600 rounded-xl"></div>
                                        </div>
                                    </div>
                                    <div class="my-1 max-w-xs">
                                        <div class="flex justify-between mb-1">                                
                                            <p>Tinggi</p>
                                            <p class="text-red-600 font-bold">{{ collect($data['hotspot'])->where('level',3)->count() }}</p>
                                        </div>
                                        <div class="border border-red-600 border-opacity-25 rounded-xl">
                                            <div style="height:5px;width:{{ collect($data['hotspot'])->where('level',3)->count()/count(collect($data['hotspot']))*100 }}%;" class="bg-red-600 rounded-xl"></div>
                                        </div>
                                    </div>
                                    <div class="my-1 max-w-xs">
                                        <div class="flex justify-between mb-1">                                
                                            <p>Sedang</p>
                                            <p class="text-yellow-500 font-bold">{{ collect($data['hotspot'])->where('level',2)->count() }}</p>
                                        </div>
                                        <div class="border border-yellow-500 border-opacity-25 rounded-xl">
                                            <div style="height:5px;width:{{ collect($data['hotspot'])->where('level',2)->count()/count(collect($data['hotspot']))*100 }}%;" class="bg-yellow-500 rounded-xl"></div>
                                        </div>
                                    </div>
                                    <div class="my-1 max-w-xs">
                                        <div class="flex justify-between mb-1">                                
                                            <p>Rendah</p>
                                            <p class="text-green-600 font-bold">{{ collect($data['hotspot'])->where('level',1)->count() }}</p>
                                        </div>
                                        <div class="border border-green-600 border-opacity-25 rounded-xl">
                                            <div style="height:5px;width:{{ collect($data['hotspot'])->where('level',1)->count()/count(collect($data['hotspot']))*100 }}%;" class="bg-green-600 rounded-xl"></div>
                                        </div>
                                    </div>
                                    <p class="text-xs text-opacity-75">Sumber Data: VIIRS {{\Carbon\Carbon::now()->addDays(-1)->translatedFormat('d F Y') }}</p>
                                </div>                                
                                <img src="{{ asset('img/mockup.png') }}">
                            </div>
                        </div>
                        <div>
                            <a href="{{ url('forestfire') }}" onclick="leavingAnimation();" class="inline-block text-white bg-orange-500 px-10 py-3 float-right hover:bg-orange-600 shadow-md shadow-paleblue">Dashboard</a>
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <input id="rad6" type="radio" name="rad" onchange="changeIcon(5)">
                <label class="bg-orange-400 hover:bg-orange-700" for="rad6">
                    <div class="h-full items-center flex justify-center">
                        <div class="h-full flex flex-col justify-between">
                            <p class="p-2" style="writing-mode: vertical-rl;transform: rotate(-180deg);opacity: 0;">Hidrometeorologi</p>
                            <i class='bx bxs-right-arrow mx-auto'></i>
                            <p class="p-2" style="writing-mode: vertical-rl;transform: rotate(-180deg);">Hidrometeorologi</p>
                        </div>
                    </div>
                </label>
                <div class="accslide">
                    <div class="content bg-white bg-home h-screen p-5 flex flex-col justify-between">
                        <div class="mt-2">
                            <div class="flex justify-between">
                                <div>
                                    <h1 class="font-bold uppercase"><span class="text-3xl">Prediksi Bencana</span><br><span class="text-5xl">Hidrometeorologi</span></h1>
                                    <hr class="w-5/6 my-2">
                                    <p class="w-5/6">Sistem prediksi <span class="font-bold text-blue-400">Bencana Hidrometeorologi</span> di Indonesia yang meliputi bencana <span class="font-bold text-red-400">cuaca ekstrem</span> dan <span class="font-bold text-yellow-400">banjir</span> dengan tingkat ketepatan tinggi yang hingga <span class="font-bold text-green-500">7 hari</span> kedepan</p>
                                </div>
                                <div class="flex justify-end">
                                    <img src="{{ asset('img/BNPB.png') }}" class="h-20 mr-2">
                                    <img src="{{ asset('img/logo-dark.png') }}" class="h-20 mr-2">
                                </div>
                            </div>
                            <div class="grid gap-2 grid-cols-1 md:grid-cols-2">
                                <div class="w-full">
                                    <div>
                                        <nav class="tabs flex flex-col sm:flex-row">
                                            <button data-target="panel-1" class="tab active bg-orange-500 whitespace-nowrap font-bold uppercase text-xs text-black py-2 px-4 block hover:bg-orange-500 hover:bg-opacity-50 focus:outline-none flex-grow border-b border-r border-orange-500">
                                                Cuaca Ekstrem
                                            </button>
                                            <button data-target="panel-2" class="tab whitespace-nowrap font-bold uppercase text-xs text-black py-2 px-4 block hover:bg-orange-500 hover:bg-opacity-50 focus:outline-none flex-grow border-b border-orange-500">
                                                Banjir
                                            </button>
                                        </nav>
                                    </div>
                                    <div id="panels" class="flex-grow flex-nowrap">
                                        <div class="panel-1 active tab-content">
                                            <div class="py-2">
                                                <div class="h-48 overflow-y-auto">                           
                                                    <table class="table-auto text-black text-xs w-full">
                                                        <thead>
                                                            <tr>
                                                                <th class="p-1 text-center bg-green-600 bg-opacity-80 border-b border-gray-400 border-opacity-20">No</th>
                                                                <th class="p-1 bg-green-600 bg-opacity-80 border-b border-gray-400 border-opacity-20 text-left">Kabupaten/Kota</th>
                                                                <th class="p-1 bg-green-600 bg-opacity-80 border-b border-gray-400 border-opacity-20 text-left">Provinsi</th>
                                                                <th class="p-1 text-center bg-green-600 bg-opacity-80 border-b border-gray-400 border-opacity-20">Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php $categories = ["","Waspada","Siaga","Awas"] @endphp
                                                            @php $idx = 1 @endphp
                                                            @php $toggle = false @endphp
                                                            @foreach($hazardData["CUACA_EKSTREM"] as $key => $value)
                                                            <tr class="{{ ($toggle ? 'bg-gray-400 bg-opacity-30' : '') }}">
                                                                <td class="p-1 text-center border-b border-gray-400 border-opacity-20 text-center">{{ $idx }}</td>
                                                                <td class="p-1 border-b border-gray-400 border-opacity-20">{{ $value[0]['KABUPATEN'] }}</td>
                                                                <td class="p-1 border-b border-gray-400 border-opacity-20">{{ $value[0]['PROVINSI'] }}</td>
                                                                <td class="p-1 text-center border-b border-gray-400 border-opacity-20 text-center"><span class="text-white p-1 {{ $categories[collect($value)->max('category')] == 'Waspada' ? 'bg-green-600' : ($categories[collect($value)->max('category')] == 'Siaga' ? 'bg-orange-500' : 'bg-red-600') }}">{{ $categories[collect($value)->max("category")] }}</span></td>
                                                            </tr>
                                                            @php $toggle = ($toggle == true) ? false : true @endphp
                                                            @php $idx++ @endphp
                                                            @endforeach                                            
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div> 
                                        </div>
                                        <div class="panel-2 tab-content">
                                            <div class="py-2">
                                                <div class="h-48 overflow-y-auto">                           
                                                    <table class="table-auto text-black text-xs w-full">
                                                        <thead>
                                                            <tr>
                                                            <th class="p-1 text-center bg-green-600 bg-opacity-80 border-b border-gray-400 border-opacity-20">No</th>
                                                            <th class="p-1 bg-green-600 bg-opacity-80 border-b border-gray-400 border-opacity-20 text-left">Kabupaten/Kota</th>
                                                                <th class="p-1 bg-green-600 bg-opacity-80 border-b border-gray-400 border-opacity-20 text-left">Provinsi</th>
                                                            <th class="p-1 text-center bg-green-600 bg-opacity-80 border-b border-gray-400 border-opacity-20">Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php $categories = ["","Waspada","Siaga","Awas"] @endphp
                                                            @php $idx = 1 @endphp
                                                            @php $toggle = false @endphp
                                                            @foreach($hazardData["BANJIR"] as $key => $value)
                                                            <tr class="{{ ($toggle ? 'bg-gray-400 bg-opacity-30' : '') }}">
                                                                <td class="p-1 text-center border-b border-gray-400 border-opacity-20 text-center">{{ $idx }}</td>
                                                                <td class="p-1 border-b border-gray-400 border-opacity-20">{{ $value[0]['KABUPATEN'] }}</td>
                                                                <td class="p-1 border-b border-gray-400 border-opacity-20">{{ $value[0]['KABUPATEN'] }}</td>
                                                                <td class="p-1 text-center border-b border-gray-400 border-opacity-20 text-center"><span class="text-white p-1 {{ $categories[collect($value)->max('category')] == 'Waspada' ? 'bg-green-600' : ($categories[collect($value)->max('category')] == 'Siaga' ? 'bg-orange-500' : 'bg-red-600') }}">{{ $categories[collect($value)->max("category")] }}</span></td>
                                                            </tr>
                                                            @php $toggle = ($toggle == true) ? false : true @endphp
                                                            @php $idx++ @endphp
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div> 
                                        </div>
                                    </div>   
                                    <hr class="border-orange-500 border-opacity-50 my-2">                                
                                    <p class="bg-animation-red px-2 py-1 text-xs text-white inline-block float-right">Prediksi {{ \Carbon\Carbon::now()->translatedFormat("d F Y") }}</p> 
                                </div>
                                <img src="{{ asset('img/mockup.png') }}">
                            </div>
                        </div>
                        <div>
                            <a href="{{ url('hydrometeorology') }}" onclick="leavingAnimation();" class="inline-block text-white bg-orange-500 px-10 py-3 float-right hover:bg-orange-600 shadow-md shadow-paleblue">Dashboard</a>
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <input id="rad7" type="radio" name="rad" checked="checked" onchange="changeIcon(6)">
                <label class="bg-orange-300 hover:bg-orange-700" for="rad7">
                    <div class="h-full items-center flex justify-center">
                        <div class="h-full flex flex-col justify-between">
                            <p class="p-2" style="writing-mode: vertical-rl;transform: rotate(-180deg);opacity: 0;">Beranda</p>
                            <i class='bx bxs-right-arrow mx-auto'></i>
                            <p class="p-2" style="writing-mode: vertical-rl;transform: rotate(-180deg);">Beranda</p>
                        </div>
                    </div>
                </label>

                <div class="accslide">
                    <div class="content bg-white bg-home h-screen p-5 flex flex-col justify-between">
                        <div class="mt-2">
                            <div class="grid grid-cols-1 md:grid-cols-2 items-start pb-5">
                                <div class="h-full">
                                    <div class="flex items-start">
                                        <img src="{{ asset('img/BNPB.png') }}" class="h-20 mr-2">
                                        <img src="{{ asset('img/logo-dark.png') }}" class="h-20 mr-2">
                                        <!-- <div> -->
<!--                                             <h1 class="font-bold uppercase text-6xl">INA-MHEWS</h1>
                                            <hr class="my-2">
                                            <p class="text-xl">Indonesia Multi-Hazards Early Warning System</p>
                                        </div> -->
                                    </div>
                                    <p class="text-black mt-5">Sebagai upaya mendukung aktivitas operasional penanggulangan dan mitigasi bencana di Indonesia, <span class="text-red-600 font-bold">INA-MHEWS</span> telah diciptakan untuk <span class="text-yellow-600 font-bold">deteksi</span>, <span class="text-yellow-600 font-bold">monitoring</span>, sekaligus <span class="text-yellow-600 font-bold">prediksi</span> dan <span class="text-yellow-600 font-bold">estimasi</span> besaran dampak  dari bencana <span class="text-green-600 font-bold">banjir</span>, <span class="text-green-600 font-bold">cuaca ekstrem</span>, <span class="text-orange-600 font-bold">kebakaran hutan & lahan</span>, <span class="text-blue-600 font-bold">gempa bumi</span>, dan <span class="text-yellow-500 font-bold">gunung api</span>.</p>
                                </div>
                                <div>
                                    <img src="{{ asset('img/mockup.png') }}" class="h-full mr-4">
                                </div>
                            </div>
                            <div class="grid grid-rows-1 grid-flow-col gap-2">
                                <a href="{{ url('hydrometeorology') }}" class="card-custom" data-aos="fade-right" data-aos-duration="1000">
                                    <div class="face face1" style="background-color: rgb(255, 179, 0);">
                                        <div class="content text-center">
                                            <i class='text-white bx bx-water text-5xl'></i>
                                            <p class="text-white font-bold uppercase">Hidrometeorologi</p>
                                        </div>
                                    </div>
                                    <div class="face face2">
                                        <div class="content text-center">
                                            <p class="my-auto text-xs">Prediksi banjir dan cuaca ekstrem hingga 6 hari kedepan</p>
                                        </div>
                                    </div>
                                </a>
                                <a href="{{ url('forestfire') }}" class="card-custom" data-aos="fade-right" data-aos-duration="1000" data-aos-delay="500">
                                    <div class="face face1" style="background-color: rgb(255, 143, 0);">
                                        <div class="content text-center">
                                            <i class='text-white bx bxs-tree text-5xl'></i>
                                            <p class="text-white font-bold uppercase">Karhutla</p>
                                        </div>
                                    </div>
                                    <div class="face face2">
                                        <div class="content text-center">
                                            <p class="my-auto text-xs">Deteksi titik panas serta potensi kebakaran hutan dan lahan</p>
                                        </div>
                                    </div>
                                </a>
                                <a href="{{ url('weather') }}" class="card-custom" data-aos="fade-right" data-aos-duration="1000" data-aos-delay="1000">
                                    <div class="face face1" style="background-color: rgb(255, 111, 0);">
                                        <div class="content text-center">
                                            <i class='text-white bx bx-cloud-rain text-5xl'></i>
                                            <p class="text-white font-bold uppercase">Prediksi Cuaca</p>
                                        </div>
                                    </div>
                                    <div class="face face2">
                                        <div class="content text-center">
                                            <p class="my-auto text-xs">Prediksi cuaca hingga 6 hari kedepan</p>
                                        </div>
                                    </div>
                                </a>
                                <a href="{{ url('volcano') }}" class="card-custom" data-aos="fade-right" data-aos-duration="1000" data-aos-delay="1500">
                                    <div class="face face1" style="background-color: rgb(180, 63, 0);">
                                        <div class="content text-center">
                                            <i class='text-white bx bxs-flame text-5xl'></i>
                                            <p class="text-white font-bold uppercase">Gunung Api</p>
                                        </div>
                                    </div>
                                    <div class="face face2">
                                        <div class="content text-center">
                                            <p class="my-auto text-xs">Monitoring gunung api terkini secara real-time di Indonesia</p>
                                        </div>
                                    </div>
                                </a>
                                <a href="{{ url('earthquake') }}" class="card-custom" data-aos="fade-right" data-aos-duration="1000" data-aos-delay="1500">
                                    <div class="face face1" style="background-color: rgb(200, 83, 0);">
                                        <div class="content text-center">
                                            <i class='text-white bx bx-globe text-5xl'></i>
                                            <p class="text-white font-bold uppercase">Gempa Bumi</p>
                                        </div>
                                    </div>
                                    <div class="face face2">
                                        <div class="content text-center">
                                            <p class="my-auto text-xs">Monitoring gempa bumi terkini secara real-time di Indonesia</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </li>            
        </ul>
    </section>
  

</main>
@endsection

@section('js')
    <script type="text/javascript">

    moment.locale('id');
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
    const grabbableDaily = document.querySelector('.grabbable-daily');
    let isDown = false;
    let startX;
    let scrollLeft;

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

        function changeIcon(value){
            elements = document.getElementsByClassName('accordion')[0].getElementsByTagName('label');
            for (var i = 0; i < elements.length; i++) {
                if(value >= i){
                    elements[i].getElementsByTagName("i")[0].classList.remove("bxs-left-arrow");
                    elements[i].getElementsByTagName("i")[0].classList.add("bxs-right-arrow");
                }else{
                    elements[i].getElementsByTagName("i")[0].classList.add("bxs-left-arrow");
                    elements[i].getElementsByTagName("i")[0].classList.remove("bxs-right-arrow");
                }
            }
        };
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

        const tabs_earthquake = document.querySelectorAll(".tabs-earthquake");
        const tab_earthquake = document.querySelectorAll(".tab-earthquake");
        const panel_earthquake = document.querySelectorAll(".tab-earthquake-content");

        function onTabClickEarthquake(event) {
          // deactivate existing active tabs and panel

          for (let i = 0; i < tab_earthquake.length; i++) {
            tab_earthquake[i].classList.remove("active");
            tab_earthquake[i].classList.remove("bg-orange-500");
          }

          for (let i = 0; i < panel_earthquake.length; i++) {
            panel_earthquake[i].classList.remove("active");
          }

          // activate new tabs and panel
          event.target.classList.add('active');
          let classString = event.target.getAttribute('data-target');
          document.getElementById('panels-earthquake').getElementsByClassName(classString)[0].classList.add("active")
          event.target.classList.add("bg-orange-500")
        }

        for (let i = 0; i < tab_earthquake.length; i++) {
          tab_earthquake[i].addEventListener('click', onTabClickEarthquake, false);
        }

        const tabs_volcano = document.querySelectorAll(".tabs-volcano");
        const tab_volcano = document.querySelectorAll(".tab-volcano");
        const panel_volcano = document.querySelectorAll(".tab-volcano-content");

        function onTabClickVolcano(event) {
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
          tab_volcano[i].addEventListener('click', onTabClickVolcano, false);
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
            data = {!! json_encode($weather['hourly']) !!};
            console.log(data)
            data = data.filter(item => {return new Date(item.date).getDate() == new Date(date).getDate()});
            content = "";
            for (var i = 0; i < data.length; i++) {
                content += "<tr class='border-b border-orange-500 border-opacity-50'><td class='text-center py-1 px-2'>" + moment(data[i].date).format('HH:mm') + "</td><td class='text-center py-1 px-2'>" + parseFloat(data[i].temperature.toFixed(1)).toLocaleString('id') + "</td><td class='text-center py-1 px-2'>" + parseFloat(parseFloat(data[i].rain).toFixed(1)).toLocaleString('id') + "</td><td class='text-center py-1 px-2'>" + (parseFloat(data[i].rain) < 1 ? "Cerah" : (parseFloat(data[i].rain) < 5 ? "Hujan Ringan" : (parseFloat(data[i].rain) < 10 ? "Hujan Sedang" : (parseFloat(data[i].rain) < 20 ? "Hujan Lebat" : "Hujan Sangat Lebat")))) + "</td></tr>"
            }
            document.getElementById('table-daily-content').innerHTML = content;
            document.getElementById('daily-title').innerHTML = 'Prediksi Cuaca ' + {!! json_encode($weather['location']->name) !!};
            document.getElementById('daily-date').innerHTML = moment({!! json_encode($weather['date']) !!}).format("DD MMMM YYYY");
        }
    </script>
@endsection