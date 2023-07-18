    <div class="sidebar bg-orange-500">
        <div class="logo-details">
            <a onclick="leavingAnimation();" href="{{ url('') }}" class="flex">
                <!-- <i class='text-white bx bxl-c-plus-plus icon my-auto'></i> -->
                <div class="logo_name my-auto">INA-MHEWS</div>
            </a>
            <i class='text-white bx bx-menu' id="btn"></i>
        </div>
        <ul class="nav-list">
<!--             <li id="menu-hazard">
                <a onclick="leavingAnimation();" href="#">
                    <i class='text-paleblue-light bx bx-grid-alt'></i>
                    <span class="links_name">Potensi Bencana</span>
                </a>
                <span class="tooltip">Potensi Bencana</span>
            </li>
            <li id="menu-report">
                <a onclick="leavingAnimation();" href="#">
                    <i class='text-paleblue-light bx bxs-report'></i>
                    <span class="links_name">Laporan Bencana</span>
                </a>
                <span class="tooltip">Laporan Bencana</span>
            </li> -->
            <li id="menu-hydrometeorology">
                <a onclick="leavingAnimation();" href="{{ url('hydrometeorology') }}">
                    <i class='text-white bx bx-water'></i>
                    <span class="links_name text-white">Hidrometeorologi</span>
                </a>
                <span class="tooltip">Hidrometeorologi</span>
            </li>
            <li id="menu-forestfire">
                <a onclick="leavingAnimation();" href="{{ url('forestfire') }}">
                    <i class='text-white bx bxs-tree'></i>
                    <span class="links_name text-white">Karhutla</span>
                </a>
                <span class="tooltip">Karhutla</span>
            </li>
            <li id="menu-weather">
                <a onclick="leavingAnimation();" href="{{ url('weather') }}">
                    <i class='text-white bx bx-cloud-rain'></i>
                    <span class="links_name text-white">Prediksi Cuaca</span>
                </a>
                <span class="tooltip">Prediksi Cuaca</span>
            </li>
            <li id="menu-volcano">
                <a onclick="leavingAnimation();" href="{{ url('volcano') }}">
                    <i class='text-white bx bxs-flame'></i>
                    <span class="links_name text-white">Gunung Api</span>
                </a>
                <span class="tooltip">Gunung Api</span>
            </li>
            <li id="menu-earthquake">
                <a onclick="leavingAnimation();" href="{{ url('earthquake') }}">
                    <i class='text-white bx bx-globe'></i>
                    <span class="links_name text-white">Gempa Bumi</span>
                </a>
                <span class="tooltip">Gempa Bumi</span>
            </li>
            <li id="menu-inarisk">
                <a onclick="leavingAnimation();" href="{{ url('inarisk') }}">
                    <i class='text-white bx bx-bookmark'></i>
                    <span class="links_name text-white">inaRISK</span>
                </a>
                <span class="tooltip">inaRISK</span>
            </li>
            <li id="menu-dibi">
                <a onclick="leavingAnimation();" href="{{ url('dibi') }}">
                    <i class='text-white bx bx-book'></i>
                    <span class="links_name text-white">DIBI</span>
                </a>
                <span class="tooltip">DIBI</span>
            </li>
            <li id="menu-observation">
                <a onclick="leavingAnimation();" href="{{ url('observation') }}">
                    <i class='text-white bx bx-bar-chart'></i>
                    <span class="links_name text-white">Tinggi Muka Air</span>
                </a>
                <span class="tooltip">Tinggi Muka Air</span>
            </li>
            <li id="menu-radar">
                <a onclick="leavingAnimation();" href="{{ url('radar') }}">
                    <i class='text-white bx bx-radar'></i>
                    <span class="links_name text-white">Radar</span>
                </a>
                <span class="tooltip">Radar</span>
            </li>
            <li id="menu-satellite">
                <a onclick="leavingAnimation();" href="{{ url('satellite') }}">
                    <i class='text-white bx bx-rss'></i>
                    <span class="links_name text-white">Satelit</span>
                </a>
                <span class="tooltip">Satelit</span>
            </li>
            <li class="profile">
                <div class="profile-details">
                    <!--<img src="profile.jpg" alt="profileImg">-->
                    <div class="name_job">
                        <div class="name">{{ Auth::User()->name }}</div>
                        <div class="job">{{ Auth::User()->role }}</div>
                    </div>
                </div>
                <a href="{{ url('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class='text-white bx bx-log-out' id="log_out"></i></a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>                
                
            </li>
        </ul>
    </div>