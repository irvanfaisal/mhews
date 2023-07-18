 <nav class="flex flex-wrap items-center justify-between px-5 bg-orange-500 fixed w-full z-30">      
        <div>
            <p class="text-white font-bold uppercase">Tangkal Bencana</p>
        </div>
        <div class="flex md:hidden">
            <button id="hamburger">
                <img class="toggle block" src="https://img.icons8.com/fluent-systems-regular/2x/menu-squared-2.png" width="40" height="40" />
                <img class="toggle hidden" src="https://img.icons8.com/fluent-systems-regular/2x/close-window.png" width="40" height="40" />
            </button>
        </div>      
        <div class="toggle hidden w-full md:w-auto md:flex text-right text-bold mt-5 md:mt-0 border-t-2 border-blue-900 md:border-none">        
            <a href="{{ url('') }}" class="text-sm block md:inline-block text-orange-500 bg-white hover:text-orange-500 hover:bg-white px-3 py-3 border-b-2 border-gray-500 md:border-none">Beranda</a>
            <a href="{{ url('hydrometeorology') }}" class="text-sm block md:inline-block text-white hover:text-orange-500 hover:bg-white px-3 py-3 border-b-2 border-gray-500 md:border-none">Hidrometeorologi</a>
            <a href="{{ url('weather') }}" class="text-sm block md:inline-block text-white hover:text-orange-500 hover:bg-white px-3 py-3 border-b-2 border-gray-500 md:border-none">Prediksi Cuaca</a>
            <a href="{{ url('earthquake') }}" class="text-sm block md:inline-block text-white hover:text-orange-500 hover:bg-white px-3 py-3 border-b-2 border-gray-500 md:border-none">Gempa Bumi</a>
            <a href="{{ url('volcano') }}" class="text-sm block md:inline-block text-white hover:text-orange-500 hover:bg-white px-3 py-3 border-b-2 border-gray-500 md:border-none">Gunung Api</a>
        </div>
        <div>
            <a class="text-white text-sm" href="{{ url('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ Auth::user()->name }}</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>  
        </div>
    </nav>  