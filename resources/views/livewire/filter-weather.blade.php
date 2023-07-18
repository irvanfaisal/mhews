<div wire:init="init">
    <div wire:loading.flex>
        <div id="loader" class="absolute display-animation show-animation h-full w-full bg-black bg-opacity-75" style="z-index:99999;">
            <div class="flex h-full w-full">
                <div class="loadingio-spinner-blocks-jxpdx88qyb m-auto">
                    <div class="ldio-p0zx0zppdri">
                        <div style='left:38px;top:38px;animation-delay:0s'></div>
                        <div style='left:80px;top:38px;animation-delay:0.125s'></div>
                        <div style='left:122px;top:38px;animation-delay:0.25s'></div>
                        <div style='left:38px;top:80px;animation-delay:0.875s'></div>
                        <div style='left:122px;top:80px;animation-delay:0.375s'></div>
                        <div style='left:38px;top:122px;animation-delay:0.75s'></div>
                        <div style='left:80px;top:122px;animation-delay:0.625s'></div>
                        <div style='left:122px;top:122px;animation-delay:0.5s'></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="flex flex-col flex-nowrap">
        <div class="bg-black bg-opacity-30 shadow-paleblue p-2">
            <div>
                <div class="flex flex-col justify-start">
                    <button onclick="toggleDropdown()" class="text-left bg-white bg-opacity-50 p-1 inline-block"><i class="bx bx-search"></i> Pencarian Lokasi</button>
                    <div class="dropdown">
                        <div id="myDropdown" class="dropdown-content">
                            <input type="text" placeholder="Pencarian Lokasi" id="locationInput" class="px-2 py-2 relative z-30" onkeyup="filterFunction();" onfocusout="toggleDropdown();">
                            <div class="custom-scroll" style="max-height:200px;overflow-y: auto;">
                                @foreach($regencies as $regency)
                                <button style="z-index: 999999;" class="bg-paleblue hover:bg-orange-500 text-xs w-full text-left block border border-gray-400 border-opacity-20 py-1 px-2  text-bold" wire:click="getLocation({{$regency->id}})">{{$regency->name}}</button>
                                @endforeach
                            </div>
                        </div>
                    </div>                        
                    <h1 class="text-white text-xl font-bold">{{$location->name}}</h1>
                </div>
                <div class="flex justify-between">
                    <div>
                        <h2 class="text-white text-xs font-thin ml-1">{{ \Carbon\Carbon::create($date)->translatedFormat("d F Y H:i") }}</h2>
                        <p class="text-white text-3xl font-bold text-red-700 ml-1">{{ number_format($currentForecast->temperature, 1,',','.') }}<sup>o</sup>C</p>
                        <p class="text-white uppercase font-bold text-yellow-400 ml-1">{{ ($currentForecast->rain < 1 ? "Cerah" : ($currentForecast->rain < 5 ? "Hujan Ringan" : ($currentForecast->rain < 10 ? "Hujan Sedang" : ($currentForecast->rain < 20 ? "Hujan Lebat" : "Hujan Sangat Lebat")))) }}</p>
                        <button class="modal-daily-open text-white text-sm ml-1 hover:bg-yellow-400 hover:text-black" onclick="getWeatherTable('{{ \Carbon\Carbon::now()->format("Y-m-d")}}')"><i class='bx bx-book-reader'></i> Cuaca Hari Ini</button>
                    </div>
                    <div class="ml-10 my-auto">
                        <img src="{{ asset('img/' . ($currentForecast->rain < 1 ? 'day-clear' : 'day-rain') . '.png') }}" class="flex w-28">
                    </div>
                </div>
            </div>
            <div style="max-width:300px;">                   
                <div>                    
                    <p class="text-white uppercase font-bold text-blue">Prediksi Cuaca 6 Hari ke Depan</p>
                    <div class="flex mt-2">
                        <button class="left left-button-daily forecast-container mr-1 border border-paleblue border-opacity-25 shadow-xs shadow-paleblue hover:bg-orange-500 hover:bg-opacity-50" style="width:20px;">
                            <p class="my-auto text-white font-bold"><</p>
                        </button>                        
                        <div id="forecast-daily" class="flex grabbable grabbable-daily w-full" style="overflow-x: scroll;">
                            @foreach($daily as $value)
                            @if ($loop->first) @continue @endif
                            <div class="modal-daily-open text-center mx-1 p-1 border border-paleblue border-opacity-25 shadow-xs shadow-paleblue hover:bg-orange-500 hover:bg-opacity-50" style="min-width:90px;" onclick="getWeatherTable('{{ \Carbon\Carbon::create($value['date'])->format("Y-m-d")}}')">
                                <div>
                                    <p class="my-auto text-xs text-white">{{ \Carbon\Carbon::create($value['date'])->translatedFormat("j F") }}</p>
                                    <img src="{{ URL::asset('img/' . ($value['rain'] < 1 ? 'day-clear' : 'day-rain') . '.png') }}" class="h-10 m-auto">
                                    <p class="my-auto text-xs text-white">{{ ($value['rain'] < 1 ? "Cerah" : ($value['rain'] < 5 ? "Hujan Ringan" : ($value['rain'] < 10 ? "Hujan Sedang" : ($value['rain'] < 20 ? "Hujan Lebat" : "Hujan Sangat Lebat")))) }}</p>
                                    <p class="my-auto text-xs text-white"><span>{{ number_format($value['temperature'], 1,',','.') }}</span><span><sup>o</sup>C</span></p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <button class="right right-button-daily forecast-container ml-1 border border-paleblue border-opacity-25 shadow-xs shadow-paleblue hover:bg-orange-500 hover:bg-opacity-50" style="width:20px;">
                            <p class="my-auto text-white font-bold">></p>
                        </button>
                    </div>                   
                </div>
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
                        <p class="text-xl font-bold uppercase" id="daily-title">Prediksi Cuaca  {{ $location->name }}</p>
                        <p class="text-xs font-bold" id="daily-date">-</p>
                    </div>
                    <div class="modal-daily-close cursor-pointer z-50">
                        <svg class="fill-current text-white" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
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
                        <tbody id="table-daily-content">
                            @foreach($hourly as $data)
                            <tr class='border-b border-orange-500 border-opacity-50'>
                                <td class='text-center py-1 px-2'>{{ \Carbon\Carbon::parse($data->date)->format("Y-m-d H:i") }}</td>
                                <td class='text-center py-1 px-2'>{{ $data->temperature }}</td>
                                <td class='text-center py-1 px-2'>{{ $data->rain }}</td>
                                <td class='text-center py-1 px-2'>{{ ($data->rain < 1 ? "Cerah" : ($data->rain < 5 ? "Hujan Ringan" : ($data->rain < 10 ? "Hujan Sedang" : ($data->rain < 20 ? "Hujan Lebat" : "Hujan Sangat Lebat") ) ) ) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>            
</div>
