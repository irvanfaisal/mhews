require('./bootstrap');

window.addEventListener('load', function () {
    setTimeout(() => { 
        try {
            document.getElementById("loader").classList.remove("show-animation");  
        }
        catch(err) {
          console.log(err.message);
        }        
    }, 1000);
})

window.refreshMap = function()  {
    map.invalidateSize();
}

window.initSidebar = function () {
    let sidebar = document.querySelector(".sidebar");
    let closeBtn = document.querySelector("#btn");

    closeBtn.addEventListener("click", ()=>{
        sidebar.classList.toggle("open");
        menuBtnChange();//calling the function(optional)
    });
};

window.menuBtnChange = function () {
    let sidebar = document.querySelector(".sidebar");
    let closeBtn = document.querySelector("#btn");  
    if(sidebar.classList.contains("open")){
        closeBtn.classList.replace("bx-menu", "bx-menu-alt-right");//replacing the iocns class
    }else {
        closeBtn.classList.replace("bx-menu-alt-right","bx-menu");//replacing the iocns class
    }
    if (map) {
        setTimeout(() => {  refreshMap(); }, 250);    
    }
};

window.addMapLayer = function (container,position) {
    let layer = L.control({position:position});
    layer.onAdd = function(map){
        this._div = L.DomUtil.get(container)
        return this._div
    }
    layer.addTo(map);
    document.getElementById(container).addEventListener('mouseover', function () {
        map.dragging.disable();
        map.touchZoom.disable();
        map.doubleClickZoom.disable();
        map.scrollWheelZoom.disable();
        map.boxZoom.disable();
        map.keyboard.disable();
        if (map.tap) map.tap.disable();
        document.getElementById('map').style.cursor='default';
        action = true;
    });
    document.getElementById(container).addEventListener('mouseout', function () {
        map.dragging.enable();
        map.touchZoom.enable();
        map.doubleClickZoom.enable();
        map.scrollWheelZoom.enable();
        map.boxZoom.enable();
        map.keyboard.enable();
        if (map.tap) map.tap.enable();
        document.getElementById('map').style.cursor='grab';
        action = false;
    });
    if (container == 'map-zoom') {
        document.getElementById("in").addEventListener("click", function() {
            if (map.getZoom() < 13) {
                map.setZoom(map.getZoom() + 1)
            }
        });
        document.getElementById("out").addEventListener("click", function() {
            if (map.getZoom() > 3) {
                map.setZoom(map.getZoom() - 1)
            }
        });
    }

};

window.initMap = function () {
    var southWest = L.latLng(-30,80),
        northEast = L.latLng(30,160),
        bounds = L.latLngBounds(southWest, northEast);
    map = L.map('map',{zoomControl: false,attributionControl: false,maxBounds: bounds,minZoom: 5}).setView([-1.7081, 118.797175], 5);
    // var CartoDB_DarkMatterNoLabels  = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_nolabels/{z}/{x}/{y}{r}.png', {
 //        maxZoom: 20
 //    }).addTo(map);
    // var Stadia_AlidadeSmoothDark = L.tileLayer('https://tiles.stadiamaps.com/tiles/alidade_smooth_dark/{z}/{x}/{y}{r}.png?api_key=581a69ec-f6c0-4c6b-8403-d8a8d2082a28', {
    //     maxZoom: 12
    // }).addTo(map);  
    var mapLight = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
    }).addTo(map);   
    // let layerStatic = L.esri.dynamicMapLayer({
    //     url: "https://inarisk.bnpb.go.id:6443/arcgis/rest/services/basemap/batas_administrasi/MapServer",
    //     opacity: 0.75
    // }).addTo(map);
    province = L.geoJson(null, {style: style});
    omnivore.topojson(flagsUrl + 'data/provinsi.json', null, province).on('ready', function() {
            
    }).setZIndex(-999).bringToBack().addTo(map);
    
    function style(feature) {
        return {
            weight: 0.25,
            // opacity: 0.75,
            fillColor: '#545454',
            color: '#fff',
            fillOpacity: 0.2,
            opacity: 0.9
        };
    }    
    setTimeout(() => { refreshMap();  }, 250);
};

window.getInariskFault = function () {
    document.getElementById("loader").classList.add("show-animation");
        if(staticLayer){
            staticLayer.remove();
            staticLayer = null;
        }
        if (document.getElementById('check-faults').checked) {
            staticLayer = L.esri.dynamicMapLayer({
                url: "https://inarisk1.bnpb.go.id:6443/arcgis/rest/services/Basemap/Faults/MapServer",
                opacity: 0.5
            }).addTo(map);
        }
        setTimeout(() => { document.getElementById("loader").classList.remove("show-animation");  }, 250);  
};

window.getEarthquake = function () {
    document.getElementById("loader").classList.add("show-animation");
        document.getElementById("count-BMKG-earthquake").classList.add("hidden");
        document.getElementById("count-USGS-earthquake").classList.add("hidden");
        let tableEarthquake = document.getElementsByClassName('table-earthquake');
        for (var i = 0; i < tableEarthquake.length; i++) {
            tableEarthquake[i].classList.add("hidden");
        }
        let sources = document.getElementsByName("source-earthquake");
        let dataSource;
        for (var i = 0; i < sources.length; i++) {
            if (sources[i].checked) {
                dataSource = sources[i].value;
                break;
            }
        }
        const tabs = document.querySelectorAll(".tabs");
        const tab = document.querySelectorAll(".tab");
        const panel = document.querySelectorAll(".tab-content");

        for (let i = 0; i < tab.length; i++) {
            tab[i].classList.remove("active");
            tab[i].classList.remove("bg-blue-500");
        }
        for (let i = 0; i < panel.length; i++) {
            panel[i].classList.remove("active");
        }

        timesteps = document.getElementsByName("timestep-earthquake");
        for (var i = 0; i < timesteps.length; i++) {
            if (timesteps[i].checked) {
                let timestep = timesteps[i].value;
                if (timestep == "month") {
                    tab[2].classList.add('active');
                    tab[2].classList.add('bg-blue-500');
                    let classString = tab[2].getAttribute('data-target');
                    document.getElementById('panels').getElementsByClassName(classString)[0].classList.add("active")
                    var date = moment().subtract(1, "months");
                }
                if (timestep == "week") {
                    tab[1].classList.add('active');
                    tab[1].classList.add('bg-blue-500');
                    let classString = tab[1].getAttribute('data-target');
                    document.getElementById('panels').getElementsByClassName(classString)[0].classList.add("active")
                    var date = moment().subtract(7, "days");
                }

                if (timestep == "day") {
                    tab[0].classList.add('active');
                    tab[0].classList.add('bg-blue-500');
                    let classString = tab[0].getAttribute('data-target');
                    document.getElementById('panels').getElementsByClassName(classString)[0].classList.add("active")                    
                    var date = moment().subtract(24, "hours");
                }
                break;
            }
        }

        if (earthquakeLayer) {
            map.removeLayer(earthquakeLayer);
        }
        var earthquakeBMKG = [];
        if (earthquakeBMKGContainer) {
            earthquakeBMKGContainer.remove();
        }  
        if (dataSource == "bmkg") {        
            document.getElementById("count-BMKG-earthquake").classList.remove("hidden");
            let table = document.getElementsByClassName('table-bmkg');
            for (var i = 0; i < table.length; i++) {
                table[i].classList.remove("hidden");
            }
            for (let i = 0; i < data.bmkg.length; i++) {
                if (moment(data.bmkg[i].waktu) >= date) {
                    let radius;
                    let color;
                    let colorAnimation;
                    if (parseFloat(data.bmkg[i].Magnitude) > 4 && parseFloat(data.bmkg[i].Magnitude) <= 5) {
                        level = 2;
                        color = "#37b896";
                        colorAnimation = "green";
                    }else if (parseFloat(data.bmkg[i].Magnitude) > 5 && parseFloat(data.bmkg[i].Magnitude) <= 6) {
                        level = 3;
                        color = "#ffc107";
                        colorAnimation = "yellow";
                    }else if (parseFloat(data.bmkg[i].Magnitude) > 6 && parseFloat(data.bmkg[i].Magnitude) <= 7) {
                        level = 4;
                        color = "#dda15a";
                        colorAnimation = "orange";
                    }else if (parseFloat(data.bmkg[i].Magnitude) > 7) {
                        level = 5;
                        color = "#e02f22";
                        colorAnimation = "red";
                    }else{
                        level = 1;
                        color = "#4bb2e8";
                        colorAnimation = "light-blue";
                    }

                    let randomPulse = Math.floor((Math.random() * 5) + 1);
                    let myIcon_pulse = L.divIcon({
                        className: 'css-icon',
                        html: '<div class="marker-level' + level + ' flex justify-center pulse-animation-level' + level + '-' + randomPulse + '"><span class="align-self-center"></span></div>',
                        iconSize: [12.5,12.5],
                        iconAnchor: [9, 5]
                    });
                    let coordinates = data.bmkg[i].point.coordinates.split(',');

                    let point  = L.marker([coordinates[0],coordinates[1]], {
                        icon: myIcon_pulse
                    });
                    var customOptions =
                    {
                        'className' : 'custom-width'
                    };
                    url = "https://data.bmkg.go.id/DataMKG/TEWS/" + moment(data.bmkg[i].DateTime).format('YYYYMMDDHHmmss') + ".mmi.jpg";
                    point.bindPopup('<div class="custom-scrollbar flex" style="width:390px;max-height:200px;overflow-y:auto;"><img onclick="showModal(this)" style="max-width:150px;" src="https://data.bmkg.go.id/DataMKG/TEWS/' + moment(data.bmkg[i].DateTime).format('YYYYMMDDHHmmss') + '.mmi.jpg"><div><p class="my-1 p-1 uppercase font-bold bg-animation-red">' + data.bmkg[i].Keterangan + '</p><p class="my-1 p-1"><span class="text-blue-light font-bold">Magnitude:</span> ' + data.bmkg[i].Magnitude + '</p><p class="my-1 p-1 bg-white bg-opacity-20"><span class="text-blue-light font-bold">Kedalaman:</span> ' + data.bmkg[i].Kedalaman + '</p><p class="my-1 p-1"><span class="text-blue-light font-bold">Lokasi:</span> ' + data.bmkg[i].Wilayah + '</p><p class="my-1 p-1 my-1 p-1 bg-white bg-opacity-20"><span class="text-blue-light font-bold">Waktu:</span> ' + moment(data.bmkg[i].waktu).format('DD MMMM YYYY HH:mm:ss') + ' WIB</p><p class="my-1 p-1 text-xs"><span class="text-blue-light font-bold">Sumber:</span> BMKG</p></div></div>',customOptions);
                    point.addEventListener("click", function (e){
                      map.panTo(this.getLatLng());
                    });
                    earthquakeBMKG.push(point);
                }
            }
            earthquakeBMKGContainer = L.featureGroup(earthquakeBMKG).addTo(map);
        }

        if (dataSource == 'usgs') {  
            document.getElementById("count-USGS-earthquake").classList.remove("hidden");
            let table = document.getElementsByClassName('table-usgs');
            for (var i = 0; i < table.length; i++) {
                table[i].classList.remove("hidden");
            }    
            earthquakeLayer = L.geoJson(null, {
                filter: function(geoJsonFeature) {
                    // my custom filter function: do not display Point type features.
                    // return (geoJsonFeature.geometry.coordinates[1] > -20 && geoJsonFeature.geometry.coordinates[1] < 15 && geoJsonFeature.geometry.coordinates[0] > -85 && geoJsonFeature.geometry.coordinates[0] < 150);
                    return (geoJsonFeature.properties.place.includes("Indonesia") && geoJsonFeature.properties.time >= date)
                },          
                pointToLayer: function (feature, latlng) {              
                    let radius;
                    let color;
                    let colorAnimation;
                    if (feature.properties.mag > 4 && feature.properties.mag <= 5) {
                        level = 2;
                        color = "#10B981";
                        colorAnimation = "green";
                    }else if (feature.properties.mag > 5 && feature.properties.mag <= 6) {
                        level = 3;
                        color = "#FCD34D";
                        colorAnimation = "yellow";
                    }else if (feature.properties.mag > 6 && feature.properties.mag <= 7) {
                        level = 4;
                        color = "#D97706";
                        colorAnimation = "orange";
                    }else if (feature.properties.mag > 7) {
                        level = 5;
                        color = "#B91C1C";
                        colorAnimation = "red";
                    }else{
                        level = 1;
                        color = "#3B82F6";
                        colorAnimation = "light-blue";
                    }

                    let randomPulse = Math.floor((Math.random() * 5) + 1);
                    let myIcon_pulse = L.divIcon({
                        className: 'css-icon',
                        html: '<div class="marker-level' + level + ' d-flex justify-content-center pulse-animation-level' + level + '-' + randomPulse + '"><span class="align-self-center"></span></div>',
                        iconSize: [10,10],
                    });

                    let point  = L.marker(latlng, {
                        icon: myIcon_pulse
                    });
                    var customOptions =
                    {
                        'className' : 'custom'
                    };
                    point.bindPopup('<div"><p class="my-1 p-1 uppercase font-bold bg-animation-red">' + feature.properties.title + '</p><p class="my-1 p-1"><span class="text-blue-light font-bold">Magnitude:</span> ' + feature.properties.mag + '</p><p class="my-1 p-1 bg-white bg-opacity-20"><span class="text-blue-light font-bold">Kedalaman:</span> ' + feature.geometry.coordinates[2] + ' KM</p><p class="my-1 p-1"><span class="text-blue-light font-bold">Lokasi:</span> ' + feature.properties.place + '</p><p class="my-1 p-1 my-1 p-1 bg-white bg-opacity-20"><span class="text-blue-light font-bold">Waktu:</span> ' + moment(feature.properties.time).format('DD MMMM YYYY HH:mm:ss') + '</p><p class="my-1 p-1"><span class="text-blue-light font-bold">Update Terakhir:</span> ' + moment(feature.properties.updated).format('DD MMMM YYYY HH:mm:ss') + '</p><p class="my-1 p-1 bg-white bg-opacity-20"><span class="text-blue-light font-bold">Sumber:</span> USGS Earthquake Hazards Program</p></div>',customOptions);
                    return point;
                }
            }).addTo(map);
            earthquakeLayer.addData(data.usgs);
        }        

        setTimeout(() => { document.getElementById("loader").classList.remove("show-animation");  }, 250);
        
    
        // omnivore.geojson(data, null, earthquakeLayer).addTo(map);  
};

window.showModal = function (url) {
    var modal = document.getElementById("myModal");
    modal.style.display = "block";
    // Get the image and insert it inside the modal - use its "alt" text as a caption
    var modalImg = document.getElementById("img01");

      modalImg.src = url.getAttribute("src");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
      modal.style.display = "none";
    }
}

window.getLatestEarthquake = function () {
var Esri_WorldImagery = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
    attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
}).addTo(map);    
    let coordinates = data.Infogempa.gempa.Coordinates.split(',');
    map.setView(new L.LatLng(coordinates[0], parseFloat(coordinates[1])), 6);
        if (earthquakeLayer) {
            map.removeLayer(earthquakeLayer);
        }
        var earthquakeBMKG = [];
        if (earthquakeBMKGContainer) {
            earthquakeBMKGContainer.remove();
        }          

                let radius;
                let color;
                let colorAnimation;
                if (parseFloat(data.Infogempa.gempa.Magnitude) > 4 && parseFloat(data.Infogempa.gempa.Magnitude) <= 5) {
                    level = 5;
                    color = "#37b896";
                    colorAnimation = "green";
                }else if (parseFloat(data.Infogempa.gempa.Magnitude) > 5 && parseFloat(data.Infogempa.gempa.Magnitude) <= 6) {
                    level = 5;
                    color = "#ffc107";
                    colorAnimation = "yellow";
                }else if (parseFloat(data.Infogempa.gempa.Magnitude) > 6 && parseFloat(data.Infogempa.gempa.Magnitude) <= 7) {
                    level = 5;
                    color = "#dda15a";
                    colorAnimation = "orange";
                }else if (parseFloat(data.Infogempa.gempa.Magnitude) > 7) {
                    level = 5;
                    color = "#e02f22";
                    colorAnimation = "red";
                }else{
                    level = 5;
                    color = "#4bb2e8";
                    colorAnimation = "light-blue";
                }

                let randomPulse = Math.floor((Math.random() * 5) + 1);
                let myIcon_pulse = L.divIcon({
                    className: 'css-icon',
                    html: '<div class="marker-outline flex align-middle justify-center"><div class="my-auto marker-earthquake flex align-middle justify-center"><p class="my-auto text-2xl">' + data.Infogempa.gempa.Magnitude + '</p></div></div>',
                    iconSize: [125,125],
                    iconAnchor:[50,50]
                });
                

                let point  = L.marker([coordinates[0],coordinates[1]], {
                    icon: myIcon_pulse,
                    iconAnchor:[50,50]
                });
                var customOptions =
                {
                    'className' : 'custom'
                };

                point.bindPopup('<div class="custom-scrollbar" style="max-height:200px;overflow-y:auto;"><div><p class="my-1 p-1 uppercase font-bold bg-animation-red">' + data.Infogempa.gempa.Wilayah + '</p><p class="my-1 p-1"><span class="text-blue-light font-bold">Magnitude:</span> ' + data.Infogempa.gempa.Magnitude + '</p><p class="my-1 p-1 bg-white bg-opacity-20"><span class="text-blue-light font-bold">Kedalaman:</span> ' + data.Infogempa.gempa.Kedalaman + '</p><p class="my-1 p-1"><span class="text-blue-light font-bold">Lokasi:</span> ' + data.Infogempa.gempa.Wilayah + '</p><p class="my-1 p-1 my-1 p-1 bg-white bg-opacity-20"><span class="text-blue-light font-bold">Waktu:</span> ' + data.Infogempa.gempa.Tanggal + ' ' + data.Infogempa.gempa.Jam + '</p><p class="my-1 p-1"><span class="text-blue-light font-bold">Sumber:</span> BMKG</p></div>',customOptions);

                earthquakeBMKG.push(point);
        earthquakeBMKGContainer = L.featureGroup(earthquakeBMKG).addTo(map);
};


window.getObservations = function () {
    document.getElementById("loader").classList.add("show-animation");
    document.getElementById('chart').innerHTML = "";
    let data = { 
        date: document.getElementById("date").value,
        station: document.getElementById("station").value, 
    };    
    fetch('getObservations?' + new URLSearchParams(data), {
        method: 'get',
        headers: {
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.sensor_1.length > 0 || data.sensor_2.length > 0 || data.sensor_3.length > 0) {
            Highcharts.chart('chart', {
                chart: {
                    type: 'line',
                    backgroundColor:'rgba(0,0,0,.5)',
                    style: {
                        fontFamily: 'Source Sans Pro'
                    }
                },
                title: {
                    text: 'Stasiun ' + document.getElementById('station').options[document.getElementById('station').selectedIndex].innerHTML,
                    style:{
                        fontSize: '18px',
                        fontWeight: 'bold',
                        color:'#fff'
                    }
                },
                subtitle: {
                    text: document.getElementById("date").value,
                    style:{
                        fontSize: '12px',
                        fontWeight: 'bold',
                        color:'#fff'
                    }
                },                
                exporting:{
                    enabled: false
                },
                xAxis: {
                    type: 'datetime',
                    title: {
                        text: null
                    }
                },
                yAxis: {
                    title:{
                        text: 'Tinggi Muka Air (m)',
                        style:{
                            color:'#fff'
                        }                        
                    },
                    labels: {
                        style:{
                            fontSize: '8px',
                            fontWeight: 'bold',
                            color:'#fff'
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
                credits: {
                    enabled: false
                },
                series: [{
                    name: 'Sensor 1',
                    data: data.sensor_1,
                    color: Highcharts.getOptions().colors[0],
                    tooltip: {
                        valueDecimals: 2
                    }
                },{
                    name: 'Sensor 2',
                    data: data.sensor_2,
                    color: Highcharts.getOptions().colors[3],
                    tooltip: {
                        valueDecimals: 2
                    }
                },{
                    name: 'Sensor 3',
                    data: data.sensor_3,
                    color: Highcharts.getOptions().colors[5],
                    tooltip: {
                        valueDecimals: 2
                    }
                }]

            });


        }else{
            alert('Data Tidak Tersedia')
        }
        setTimeout(() => { document.getElementById("loader").classList.remove("show-animation");  }, 250);
    })
    .catch((error) => {
      console.error('Error:', error);
      setTimeout(() => { document.getElementById("loader").classList.remove("show-animation");  }, 250);
    });
};

window.getObservationStations = function () {
    document.getElementById("loader").classList.add("show-animation");
        var stationLists = [];
        if (stationContainer) {
            stationContainer.remove();
        }          
        now = moment().add(-24, 'hours');
        week = moment().add(-7, 'days');
            for (let i = 0; i < stations.length; i++) {
                    let myIcon_pulse = L.divIcon({
                        className: 'css-icon',
                        html: '<div class="marker-level-1 flex justify-center"><span class="align-self-center"></span></div>',
                        iconSize: [12.5,12.5],
                    });
                    if (stations[i].last) {

                        if (moment(stations[i].last) >= now) {
                            color = '#43a047';
                        }else if (moment(stations[i].last) >= week) {
                            color = '#eab308';
                        }else{
                            color = '#7f1d1d';
                        }
                    }else{
                        color = '#7f1d1d';
                    }
                    let point = L.circleMarker([stations[i].lat, stations[i].lon], {
                        radius: 5,
                        fillColor: color,
                        color: "#000",
                        weight: 0.25,
                        opacity: 1,
                        fillOpacity: 1
                    });
                    var customOptions =
                    {
                        'className' : 'custom'
                    };

                    point.bindPopup('<div><img class="my-1 p-1" src="https://tangkalbencana.id/img/station/' + stations[i].station_id + '_bangunan.jpg" style="max-height:80px;"><p class="my-1 p-1 bg-white bg-opacity-20"><span class="text-blue-400 font-bold">ID:</span> ' + stations[i].station_id + '</p><p class="my-1 p-1"><span class="text-blue-400 font-bold">Nama:</span> ' + stations[i].name + '</p><p class="my-1 p-1 bg-white bg-opacity-20"><span class="text-blue-400 font-bold">Data Terakhir:</span> ' + (stations[i].last ? moment(stations[i].last).format('YYYY-MM-DD HH:mm') : '-') + '</p>' + (stations[i].last ? '<button class="px-2 py-1 my-1 font-xs bg-yellow-600" onclick="getLastObservation(' + moment(stations[i].last) + ')">Lihat Data Terakhir</button>' : '') + '</div>',customOptions);
                    stationLists.push(point);
                    point.on('click', function(e) {
                        document.querySelector('#station [value="' + stations[i].station_id + '"]').selected = true;
                    });
            }
            stationContainer = L.featureGroup(stationLists).addTo(map);

        setTimeout(() => { document.getElementById("loader").classList.remove("show-animation");  }, 250);
        
    
        // omnivore.geojson(data, null, earthquakeLayer).addTo(map);  
};

window.getLastObservation = function(date) {
    document.getElementById('date').value = moment(date).format("YYYY-MM-DD");
    getObservations();
}

window.changeStation = function(station) {
    lat = station.options[station.selectedIndex].dataset.lat;
    lon = station.options[station.selectedIndex].dataset.lon;
    map.setView([lat,lon],9);
}

window.getDetailForecast = function(date) {
    document.getElementById("loader").classList.add("show-animation");
    let data = {
        date: date,
        id: document.getElementById('location-id').value
    };    
    fetch('getDetailForecast?' + new URLSearchParams(data), {
        method: 'get',
        headers: {
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {

        content = "";
        // document.getElementById("modal-title").innerHTML = "Prediksi Cuaca " + moment(date).format("D MMMM YYYY");
        document.getElementById("table-daily-content").innerHTML = content;
        count = 0;
        sum = 0;
        for (i = 0; i < data.forecast.length; i++) {
            if (parseFloat(data.forecast[i].rain) > 0) {
                count++;
                sum += parseFloat(data.forecast[i].rain);
            }
            content += "<tr class='border-b border-opacity-25 border-blue-500'>" + 
                    "<td class='text-center px-2 py-1'>" + moment(data.forecast[i].date).format("HH:mm") + "</td>" +
                    "<td class='text-center px-2 py-1'>" + parseFloat(data.forecast[i].temperature.toFixed(1)).toLocaleString("id") + "<sup>o</sup>C</td>" +
                    "<td class='text-center px-2 py-1'>" + (parseFloat(data.forecast[i].rain) > 0 ? parseFloat(parseFloat(data.forecast[i].rain).toFixed(1)).toLocaleString("id") + ' mm/jam' : '-' ) + "</td>" +
                    "<td class='text-center px-2 py-1'>" + (parseFloat(data.forecast[i].rain) < 1 ? "Cerah" : (parseFloat(data.forecast[i].rain) < 5 ? "Hujan Ringan" : (parseFloat(data.forecast[i].rain) < 10 ? "Hujan Sedang" : (parseFloat(data.forecast[i].rain) < 20 ? "Hujan Lebat" : "Hujan Sangat Lebat")))) + "</td>" +
            "</tr>"
        }
        document.getElementById("table-daily-content").innerHTML = content;;
        document.getElementById("daily-title").innerHTML = "Prediksi Cuaca " + data.regency;
        document.getElementById("daily-date").innerHTML = moment(data.date).format("D MMMM YYYY");
        // document.getElementById("daily-duration").innerHTML = count + ' Jam';
        // document.getElementById("daily-sum").innerHTML =  parseFloat(sum.toFixed(1)).toLocaleString("id") + ' mm/Hari';
        setTimeout(() => { document.getElementById("loader").classList.remove("show-animation");  }, 250);
    })
    .catch((error) => {
        setTimeout(() => { document.getElementById("loader").classList.remove("show-animation");  }, 250);
        console.error('Error:', error);
    });
}

window.getExtremeWeather = function () {
    weatherLayer.clearLayers();
    function groupBy(xs, f) {
        return xs.reduce((r, v, i, a, k = f(v)) => ((r[k] || (r[k] = [])).push(v), r), {});
    }    
    dataSource = document.getElementById("data-source").value;
    let checked = true;
    if (checked) {
        // function handler(data, status, xhr) {
        //    console.log(xhr.getResponseHeader('Last-Modified'));
        // }
        let currentDate = document.getElementById("date").value;
        weatherLayer.clearLayers();
        let dataUrl;
            if (dataSource == 'BMKG') {
                dataUrl = storagePath + '/storage/forecast/BMKG/CUACA_EKSTREM';
            }
            if (dataSource == 'WRF') {
                dataUrl = storagePath + '/storage/forecast/WRF/CUACA_EKSTREM';
            }
            if (dataSource == 'GFS') {
                dataUrl = storagePath + '/storage/forecast/GFS/CUACA_EKSTREM';
            }            

    fetch(dataUrl + '/area-' + moment(currentDate).format("YYYYMMDD") + '.json', {
        method: 'get',
        headers: {
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
            weatherLayer.addData(data);
            weatherLayer.bringToBack();
            province.bringToBack();
            
            fetch(dataUrl + '/' + moment(currentDate).format("YYYYMMDD") + '.json', {
                method: 'get',
                headers: {
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(dataList => {

                let filterLists = [];
                if (dataSource == 'BMKG') {
                    if(!filterSignature.kategori1){
                        filterLists.push(1);
                    }
                    if(!filterSignature.kategori2){
                        filterLists.push(2);
                    }
                    if(!filterSignature.kategori3){
                        filterLists.push(3);
                    }
                    if(!filterSignature.kategori4){
                        filterLists.push(4);
                    }
                    if(!filterSignature.kategori5){
                        filterLists.push(5);
                    }
                    if(!filterSignature.kategori6){
                        filterLists.push(6);
                    }
                    if(!filterSignature.kategori7){
                        filterLists.push(7);
                    }
                    if(!filterSignature.kategori8){
                        filterLists.push(8);
                    }
                    if(!filterSignature.kategori9){
                        filterLists.push(9);
                    }
                    if(!filterSignature.kategori10){
                        filterLists.push(10);
                    }
                }
                if (dataSource == 'WRF') {
                    if(!filterWRF.kategori1){
                        filterLists.push(1);
                    }
                    if(!filterWRF.kategori2){
                        filterLists.push(2);
                    }
                    if(!filterWRF.kategori3){
                        filterLists.push(3);
                    }
                }
                if (dataSource == 'GFS') {
                    if(!filterGFS.kategori1){
                        filterLists.push(1);
                    }
                    if(!filterGFS.kategori2){
                        filterLists.push(2);
                    }
                    if(!filterGFS.kategori3){
                        filterLists.push(3);
                    }
                }

                dataList = dataList.filter( i => filterLists.includes( parseInt(i.category) ) );

                feature_group = groupBy(dataList, (c) => c.feature_id);
                for (const [key, value] of Object.entries(feature_group)) {
                    tmp = groupBy(value, (c) => c.PROVINSI);
                    dataTmp[key] = [];
                    // console.log(tmp)
                    for (const [key2, value2] of Object.entries(tmp)) {
                        dataTmp[key][key2] = [...new Set(value.map(x => x.KABUPATEN)) ]
                    }
                }
                weatherLayer.eachLayer(function(layer) {
                    
                    feature = layer.toGeoJSON();
                    for (const [key, value] of Object.entries(dataTmp)) {
                        if (feature.properties.feature_id == key) {
                            feature.properties.impacted = value;
                            let content = "";
                            let count = 0;
                            var customOptions =
                            {
                                'className' : 'custom'
                            };  
                            content += '<div class="text-white p-2 custom-scrollbar" style="min-width:175px;min-width:175px;max-height:200px;overflow-y:auto;"><h5 class="font-bold my-auto p-1 uppercase text-black" style="' + (feature.properties.category <= 5 ? 'background:rgba(255,235,59 ,1);' : feature.properties.category <= 9 ? 'background:rgba(255,152,0 ,1);' : 'background:rgba(211,47,47 ,1);') + '">Cuaca ekstrem kategori ' + feature.properties.category + '</h5><p class="mt-1 mb-0">Kabupaten/Kota Terdampak:</p>';
                            Object.keys(feature.properties.impacted).forEach((key,index) => {
                                id1 = count;
                                count += 1;
                                id2 = count;
                                content += '<details><summary class="question cursor-pointer select-none w-full outline-none">' + (index+1) + ". " + key + '</summary>'
                                Object.keys(feature.properties.impacted[key]).forEach((key2,index2) => {
                                    id3 = count;
                                    count += 1;
                                    content += '<p class="my-auto ml-2">' + (index2+1) + ". " + feature.properties.impacted[key][key2] + '</p>'


                                        // for (var i = 0; i < feature.properties.impacted[key][key2].length; i++) {
                                        //     content += '<span class="collapse" id="collapse' + id3 + '">' + (i+1)  + '. ' + feature.properties.impacted[key][key2][i] + '</span><br>'

                                        // }
                                    });
                                content += '</details>';
                            });
                            content+='</div>';
                            layer.bindPopup(content,customOptions);
                        }
                    }
                });

                setTimeout(() => { document.getElementById("loader").classList.remove("show-animation");  }, 250);
            })
            .catch((error) => {
              console.error('Error:', error);
              setTimeout(() => { document.getElementById("loader").classList.remove("show-animation");  }, 250);
            });

        setTimeout(() => { document.getElementById("loader").classList.remove("show-animation");  }, 250);
    })
    .catch((error) => {
      console.error('Error:', error);
      setTimeout(() => { document.getElementById("loader").classList.remove("show-animation");  }, 250);
    }); 
  
    }else{
        weatherLayer.clearLayers();
    };

        setTimeout(() => { document.getElementById("loader").classList.remove("show-animation");  }, 250);
};

window.filterWeather = function(filter){
    function groupBy(xs, f) {
        return xs.reduce((r, v, i, a, k = f(v)) => ((r[k] || (r[k] = [])).push(v), r), {});
    }     
    let checked = document.getElementById('menu-extreme-weather').checked;
    let dataUrl;
        if (dataSource == 'BMKG') {
            filterSignature[filter] = !filterSignature[filter];
            dataUrl = storagePath + '/storage/forecast/BMKG/CUACA_EKSTREM';
        }
        if (dataSource == 'WRF') {
            filterWRF[filter] = !filterWRF[filter];
            dataUrl = storagePath + '/storage/forecast/WRF/CUACA_EKSTREM';
        }
        if (dataSource == 'GFS') {
            filterGFS[filter] = !filterGFS[filter];
            dataUrl = storagePath + '/storage/forecast/GFS/CUACA_EKSTREM';
        }
       if (checked) {
            // function handler(data, status, xhr) {
            //    console.log(xhr.getResponseHeader('Last-Modified'));
            // }
            let currentDate = document.getElementById("date").value;
            weatherLayer.clearLayers();

        fetch(dataUrl + '/area-' + moment(currentDate).format("YYYYMMDD") + '.json', {
            method: 'get',
            headers: {
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
                weatherLayer.addData(data);
                weatherLayer.bringToBack();
                province.bringToBack();

                
                fetch(dataUrl + '/' + moment(currentDate).format("YYYYMMDD") + '.json', {
                    method: 'get',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(dataList => {

                    let filterLists = [];
                    if (dataSource == 'BMKG') {
                        if(!filterSignature.kategori1){
                            filterLists.push(1);
                        }
                        if(!filterSignature.kategori2){
                            filterLists.push(2);
                        }
                        if(!filterSignature.kategori3){
                            filterLists.push(3);
                        }
                        if(!filterSignature.kategori4){
                            filterLists.push(4);
                        }
                        if(!filterSignature.kategori5){
                            filterLists.push(5);
                        }
                        if(!filterSignature.kategori6){
                            filterLists.push(6);
                        }
                        if(!filterSignature.kategori7){
                            filterLists.push(7);
                        }
                        if(!filterSignature.kategori8){
                            filterLists.push(8);
                        }
                        if(!filterSignature.kategori9){
                            filterLists.push(9);
                        }
                        if(!filterSignature.kategori10){
                            filterLists.push(10);
                        }
                    }
                    if (dataSource == 'WRF') {
                        if(!filterWRF.kategori1){
                            filterLists.push(1);
                        }
                        if(!filterWRF.kategori2){
                            filterLists.push(2);
                        }
                        if(!filterWRF.kategori3){
                            filterLists.push(3);
                        }
                    }
                    if (dataSource == 'GFS') {
                        if(!filterGFS.kategori1){
                            filterLists.push(1);
                        }
                        if(!filterGFS.kategori2){
                            filterLists.push(2);
                        }
                        if(!filterGFS.kategori3){
                            filterLists.push(3);
                        }
                    }
                    dataList = dataList.filter( i => filterLists.includes( parseInt(i.category) ) );

                    feature_group = groupBy(dataList, (c) => c.feature_id);
                    for (const [key, value] of Object.entries(feature_group)) {
                        tmp = groupBy(value, (c) => c.PROVINSI);
                        dataTmp[key] = [];
                        // console.log(tmp)
                        for (const [key2, value2] of Object.entries(tmp)) {
                            dataTmp[key][key2] = [...new Set(value.map(x => x.KABUPATEN)) ]
                        }
                    }
                    weatherLayer.eachLayer(function(layer) {
                        feature = layer.toGeoJSON();
                        for (const [key, value] of Object.entries(dataTmp)) {
                            if (feature.properties.feature_id == key) {
                                feature.properties.impacted = value;
                                let content = "";
                                let count = 0;
                                var customOptions =
                                {
                                    'className' : 'custom'
                                };  
                                content += '<div class="panel-group bg-dark-transparent text-white p-2" id="accordion1" style="min-width:175px;min-width:175px;max-height:200px;overflow-y:auto;"><div class="border-light-transparent py-2 px-1" style="position: relative;"><i class="fa fa-circle circle-dot font-small" style="position: absolute;top: -5;left: -4;"></i><i class="fa fa-circle circle-dot font-small" style="position: absolute;top: -5;right: -4;"></i><i class="fa fa-circle circle-dot font-small" style="position: absolute;bottom: -5;left: -4;"></i><i class="fa fa-circle circle-dot font-small" style="position: absolute;bottom: -5;right: -4;"></i><h5 class="my-auto p-1 text-uppercase text-black" style="' + (feature.properties.category <= 5 ? 'background:rgba(255,235,59 ,1);' : feature.properties.category <= 9 ? 'background:rgba(255,152,0 ,1);' : 'background:rgba(211,47,47 ,1);') + '">Cuaca ekstrem kategori ' + feature.properties.category + '</h5><p class="mt-1 mb-0">Kabupaten/Kota Terdampak:</p>';
                                Object.keys(feature.properties.impacted).forEach((key,index) => {
                                    id1 = count;
                                    count += 1;
                                    id2 = count;
                                    count += 1;
                                    content +=  '<div class="panel panel-default"><div class="panel-heading"><a data-toggle="collapse" data-parent="#accordion1" href="#collapse' + id1 + '">' + (index+1) + ". " + key + '</a></div><div id="collapse' + id1 + '" class="panel-collapse collapse"><div class="panel-body"><div class="collapse-group" id="collapse' + id2 + '">';
                                    Object.keys(feature.properties.impacted[key]).forEach((key2,index2) => {

                                        id3 = count;
                                        count += 1;
                                        content += '<p class="my-auto ml-2" data-toggle="collapse" data-parent="#collapse' + id2 + '">' + (index2+1) + ". " + feature.properties.impacted[key][key2] + '</p>'


                                            // for (var i = 0; i < feature.properties.impacted[key][key2].length; i++) {
                                            //     content += '<span class="collapse" id="collapse' + id3 + '">' + (i+1)  + '. ' + feature.properties.impacted[key][key2][i] + '</span><br>'

                                            // }
                                        });
                                    content += '</div></div></div></div>';
                                });
                                content+='</div>';
                                layer.bindPopup(content,customOptions);
                            }
                        }
                    });

                    setTimeout(() => { document.getElementById("loader").classList.remove("show-animation");  }, 250);
                })
                .catch((error) => {
                  console.error('Error:', error);
                  setTimeout(() => { document.getElementById("loader").classList.remove("show-animation");  }, 250);
                });

            setTimeout(() => { document.getElementById("loader").classList.remove("show-animation");  }, 250);
        })
        .catch((error) => {
          console.error('Error:', error);
          setTimeout(() => { document.getElementById("loader").classList.remove("show-animation");  }, 250);
        }); 
      
    }else{
        weatherLayer.clearLayers();
    };
};

window.getHazard = function () {
    let dataSource = document.getElementById("data-source").value;
    if (dataSource == 'BMKG') {
        document.getElementById('legend-bmkg').style.display = "";
        document.getElementById('legend-wrf').style.display = "none";
        document.getElementById('legend-gfs').style.display = "none";
        buttonWRF = document.getElementsByClassName('button-wrf');
        for (var i = 0; i < buttonWRF.length; i++) {
            buttonWRF[i].disabled = true;
        }
        buttonGFS = document.getElementsByClassName('button-gfs');
        for (var i = 0; i < buttonGFS.length; i++) {
            buttonGFS[i].disabled = true;
        }
        buttonBMKG = document.getElementsByClassName('button-bmkg');
        for (var i = 0; i < buttonBMKG.length; i++) {
            buttonBMKG[i].disabled = false;
        }
    }

    if (dataSource == 'WRF') {
        document.getElementById('legend-bmkg').style.display = "none";
        document.getElementById('legend-wrf').style.display = "";
        document.getElementById('legend-gfs').style.display = "none";
        buttonWRF = document.getElementsByClassName('button-wrf');
        for (var i = 0; i < buttonWRF.length; i++) {
            buttonWRF[i].disabled = false;
        }
        buttonGFS = document.getElementsByClassName('button-gfs');
        for (var i = 0; i < buttonGFS.length; i++) {
            buttonGFS[i].disabled = true;
        }        
        buttonBMKG = document.getElementsByClassName('button-bmkg');
        for (var i = 0; i < buttonBMKG.length; i++) {
            buttonBMKG[i].disabled = true;
        }
    } 
    if (dataSource == 'GFS') {
        document.getElementById('legend-bmkg').style.display = "none";
        document.getElementById('legend-wrf').style.display = "none";
        document.getElementById('legend-gfs').style.display = "";
        buttonWRF = document.getElementsByClassName('button-wrf');
        for (var i = 0; i < buttonWRF.length; i++) {
            buttonWRF[i].disabled = true;
        }
        buttonGFS = document.getElementsByClassName('button-gfs');
        for (var i = 0; i < buttonGFS.length; i++) {
            buttonGFS[i].disabled = false;
        }        
        buttonBMKG = document.getElementsByClassName('button-bmkg');
        for (var i = 0; i < buttonBMKG.length; i++) {
            buttonBMKG[i].disabled = true;
        }
    }     

    if(hazardMarkerContainer){
        Object.keys(hazardMarkerContainer).forEach(function(key) {
            hazardMarkerContainer[key].clearLayers();
        });
        hazardMarkers = [];
    }
    hazardMarkerContainer = {};
    document.getElementById("loader").classList.add("show-animation");
    document.getElementById("title-date").innerHTML = moment(document.getElementById("date").value).format("DD MMMM YYYY");
    document.getElementById("map-date").innerHTML = moment(document.getElementById("date").value).format("DD MMMM YYYY");
    let hazards = ["CUACA_EKSTREM","BANJIR"];
    let categories = ["","Waspada","Siaga","Awas"];
    let data = { 
        date: document.getElementById("date").value,
        source: document.getElementById("data-source").value, 
    };
    let countHazard = document.getElementsByClassName('count-hazard');
    for (var i = 0; i < countHazard.length; i++) {
        countHazard[i].innerHTML = "-";
    }
    let tableHazard = document.getElementsByClassName('table-hazard');
    for (var i = 0; i < tableHazard.length; i++) {
        tableHazard[i].innerHTML = "<tr><td  class='p-1 border-b border-white border-opacity-20 text-center' colspan='2'>-</td></tr>";
    }
    fetch('getHazard?' + new URLSearchParams(data), {
        method: 'get',
        headers: {
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        for (var i = 0; i < countHazard.length; i++) {
            countHazard[i].innerHTML = Object.keys(data[hazards[i]]).length;
        }
        for (var i = 0; i < tableHazard.length; i++) {
            tableHazard[i].innerHTML = "";
            count = 1;
            for (const value in data[hazards[i]]) {
                let maxValue = Math.max.apply(Math, data[hazards[i]][value].map(function(o) { return o.category; }));
                tableHazard[i].innerHTML += "<tr><td class='p-1 border-b border-white border-opacity-20'>" + data[hazards[i]][value][0]["KABUPATEN"] + "</td><td class='p-1 border-b border-white border-opacity-20 text-center'><span class='hidden'>" + maxValue + "</span><span class='px-1 " + (maxValue == 1 ? "bg-green-700" : (maxValue == 2 ? "bg-yellow-600" : "bg-red-500")) + "'>" + categories[maxValue] + "</span></td></tr>";
                count++;
            }
            sortTable(1,'table-hazard-' + (i+1),'text','desc');
            
        }

        hazardMarkers = [];
        for (var i = 0; i < hazards.length; i++) {
            hazardMarkers[hazards[i]] = [];
            for (var j = 1; j < categories.length; j++) {
                hazardMarkers[hazards[i]][categories[j]] = [];
            }
        }
        for (const hazard in data) {
            if (hazard != "CUACA_EKSTREM") {
                for (const regency in data[hazard]) {
                    for (const village in data[hazard][regency]) {
                        let color;
                        let colorAnimation;
                        if (data[hazard][regency][village]["category"] == 1) {
                            level = 1;
                            color = "rgba(124,179,66 ,1)";
                            colorAnimation = "green";
                        }else if (data[hazard][regency][village]["category"] == 2) {
                            level = 2;
                            color = "rgba(251,192,45 ,1)";
                            colorAnimation = "yellow";
                        }else if (data[hazard][regency][village]["category"] == 3) {
                            level = 3;
                            color = "rgba(198,40,40 ,1)";
                            colorAnimation = "red";
                        }
                        if (level == 3) {                        
                            let randomPulse = Math.floor((Math.random() * 5) + 1);
                            let myIcon_pulse = L.divIcon({
                                className: 'css-icon',
                                html: '<div class="marker-level' + 5 + ' flex justify-center pulse-animation-level' + 5 + '-' + randomPulse + '"><span class="align-self-center"></span></div>',
                                iconSize: [12.5,12.5],
                                iconAnchor: [9, 5]
                            });

                            let hazardMarker  = L.marker([data[hazard][regency][village]['lat'], data[hazard][regency][village]['lon']], {
                                icon: myIcon_pulse
                            });
                            customOptions = {
                                'className' : 'custom'
                            }; 
                            hazardMarker.bindPopup('<div">' + 
                                '<p class="my-1 p-1 uppercase font-bold text-black" style="background-color:' + color + '">Status: ' + categories[level] + '</p>' + 
                                '<p class="my-1 p-1 d-block uppercase font-bold text-white bg-blue-500" style="margin-top:5px !important;">Lokasi</p>' + 
                                '<p class="px-1 leading-5"><span class="text-blue-500">Provinsi:</span> ' + data[hazard][regency][village]["PROVINSI"] + '</p>' +
                                '<p class="px-1 leading-5"><span class="text-blue-500">Kabupaten/Kota:</span> ' + data[hazard][regency][village]["KABUPATEN"] + '</p>' +
                                '<p class="px-1 leading-5"><span class="text-blue-500">Kecamatan:</span> ' + data[hazard][regency][village]["KECAMATAN"] + '</p>' +
                                '<p class="px-1 leading-5"><span class="text-blue-500">Desa/Kelurahan:</span> ' + data[hazard][regency][village]["DESA_CLEAN"] + '</p>' +
                                '<p class="my-1 p-1 d-block uppercase font-bold text-white bg-yellow-600" style="margin-top:5px !important;">Potensi Dampak</p>' + 
                                '<p class="px-1 leading-5"><span class="text-yellow-500">Luas:</span> ' + (data[hazard][regency][village]["count"]/100).toLocaleString("id") + ' km<sup>2</sup></p>' +
                                '<p class="px-1 leading-5"><span class="text-yellow-500">Populasi Terdampak:</span> ' + data[hazard][regency][village]["sum"].toLocaleString("id") + ' jiwa</p>' +
                                '<p class="px-1 leading-5"><span class="text-yellow-500">Fasdik Terdampak:</span> ' + data[hazard][regency][village]["fasdik"].toLocaleString("id") + ' unit</p>' +
                                '<p class="px-1 leading-5"><span class="text-yellow-500">Faskes Terdampak:</span> ' + data[hazard][regency][village]["faskes"].toLocaleString("id") + ' unit</p>' +
                                '<p class="px-1 leading-5"><span class="text-yellow-500">Fasum Terdampak:</span> ' + data[hazard][regency][village]["fasum"].toLocaleString("id") + ' unit</p>' + 
                                '</div>',customOptions);                  
                            hazardMarkers[hazard][categories[level]].push(hazardMarker);                            
                        }else{
                            let hazardMarker = L.circleMarker([data[hazard][regency][village]['lat'], data[hazard][regency][village]['lon']], {
                                radius: 4,
                                fillColor: color,
                                color: "#000",
                                weight: 0.25,
                                opacity: 1,
                                fillOpacity: 1
                            });
                            customOptions = {
                                'className' : 'custom'
                            }; 
                            hazardMarker.bindPopup('<div">' + 
                                '<p class="my-1 p-1 uppercase font-bold text-black" style="background-color:' + color + '">Status: ' + categories[level] + '</p>' + 
                                '<p class="my-1 p-1 d-block uppercase font-bold text-white bg-blue-500" style="margin-top:5px !important;">Lokasi</p>' + 
                                '<p class="px-1 leading-5"><span class="text-blue-500">Provinsi:</span> ' + data[hazard][regency][village]["PROVINSI"] + '</p>' +
                                '<p class="px-1 leading-5"><span class="text-blue-500">Kabupaten/Kota:</span> ' + data[hazard][regency][village]["KABUPATEN"] + '</p>' +
                                '<p class="px-1 leading-5"><span class="text-blue-500">Kecamatan:</span> ' + data[hazard][regency][village]["KECAMATAN"] + '</p>' +
                                '<p class="px-1 leading-5"><span class="text-blue-500">Desa/Kelurahan:</span> ' + data[hazard][regency][village]["DESA_CLEAN"] + '</p>' +
                                '<p class="my-1 p-1 d-block uppercase font-bold text-white bg-yellow-600" style="margin-top:5px !important;">Potensi Dampak</p>' + 
                                '<p class="px-1 leading-5"><span class="text-yellow-500">Luas:</span> ' + (data[hazard][regency][village]["count"]/100).toLocaleString("id") + ' km<sup>2</sup></p>' +
                                '<p class="px-1 leading-5"><span class="text-yellow-500">Populasi Terdampak:</span> ' + data[hazard][regency][village]["sum"].toLocaleString("id") + ' jiwa</p>' +
                                '<p class="px-1 leading-5"><span class="text-yellow-500">Fasdik Terdampak:</span> ' + data[hazard][regency][village]["fasdik"].toLocaleString("id") + ' unit</p>' +
                                '<p class="px-1 leading-5"><span class="text-yellow-500">Faskes Terdampak:</span> ' + data[hazard][regency][village]["faskes"].toLocaleString("id") + ' unit</p>' +
                                '<p class="px-1 leading-5"><span class="text-yellow-500">Fasum Terdampak:</span> ' + data[hazard][regency][village]["fasum"].toLocaleString("id") + ' unit</p>' + 
                                '</div>',customOptions);                  
                            hazardMarkers[hazard][categories[level]].push(hazardMarker);                            
                        }
                    }                
                }
            }
        }
        // earthquakeBMKGContainer = L.featureGroup(earthquakeBMKG).addTo(map);
        layers = document.getElementsByName("menu-layer");
        for (var i = 0; i < layers.length; i++) {
            if (layers[i].checked) {
                layer = layers[i].value;
                break;
            }
        }

        getExtremeWeather();
        changeHazard("BANJIR")
        setTimeout(() => { document.getElementById("loader").classList.remove("show-animation");  }, 250);
    })
    .catch((error) => {
      console.error('Error:', error);
      setTimeout(() => { document.getElementById("loader").classList.remove("show-animation");  }, 250);
    });    
};


window.initExtremeWeather = function () {
        dataSource = document.getElementById("data-source").value;

        weatherLayer = L.geoJSON(null,{
            filter: function (feature){
                if (dataSource == 'BMKG') {
                    if (!filterSignature.kategori1) {
                        if (feature.properties.category == 1) return true
                    }
                    if (!filterSignature.kategori2) {
                        if (feature.properties.category == 2) return true
                    }
                    if (!filterSignature.kategori3) {
                        if (feature.properties.category == 3) return true
                    }
                    if (!filterSignature.kategori4) {
                        if (feature.properties.category == 4) return true
                    }
                    if (!filterSignature.kategori5) {
                        if (feature.properties.category == 5) return true
                    }
                    if (!filterSignature.kategori6) {
                        if (feature.properties.category == 6) return true
                    }
                    if (!filterSignature.kategori7) {
                        if (feature.properties.category == 7) return true
                    }
                    if (!filterSignature.kategori8) {
                        if (feature.properties.category == 8) return true
                    }
                    if (!filterSignature.kategori9) {
                        if (feature.properties.category == 9) return true
                    }
                    if (!filterSignature.kategori10) {
                        if (feature.properties.category == 10) return true
                    }
                };
                if (dataSource == 'WRF') {
                    if (!filterWRF.kategori1) {
                        if (feature.properties.category == 1) return true
                    }
                    if (!filterWRF.kategori2) {
                        if (feature.properties.category == 2) return true
                    }
                    if (!filterWRF.kategori3) {
                        if (feature.properties.category == 3) return true
                    }
                };
                if (dataSource == 'GFS') {
                    if (!filterGFS.kategori1) {
                        if (feature.properties.category == 1) return true
                    }
                    if (!filterGFS.kategori2) {
                        if (feature.properties.category == 2) return true
                    }
                    if (!filterGFS.kategori3) {
                        if (feature.properties.category == 3) return true
                    }
                };
            },
            style: function (feature) {
                if (dataSource == 'BMKG') {                
                    if (feature.properties.category <= 5) {
                        color = 'rgba(255,235,59 ,1)';
                    }else if(feature.properties.category <= 9){
                        color = 'rgba(255,152,0 ,1)';
                    }else{
                        color = 'rgba(211,47,47 ,1)';
                    }
                }

                if (dataSource == 'WRF') {                
                    if (feature.properties.category <= 1) {
                        color = 'rgba(255,235,59 ,1)';
                    }else if(feature.properties.category <= 2){
                        color = 'rgba(255,152,0 ,1)';
                    }else{
                        color = 'rgba(211,47,47 ,1)';
                    }
                }              
                return {
                    weight: 0.25,
                    // opacity: 0.75,
                    fillColor: color,
                    color: '#949494',
                    fillOpacity: 0.5,
                    opacity: 0.6
                    };
            }
        }).setZIndex(99).addTo(map);    
}


window.changeHazard = function (layer) {
    document.getElementById("loader").classList.add("show-animation");
    if(hazardMarkerContainer){
        Object.keys(hazardMarkerContainer).forEach(function(key) {
            hazardMarkerContainer[key].clearLayers();
        });
    }
    let level = document.getElementsByName('variable-level');
    for (var i = 0; i < level.length; i++) {
        if (level[i].checked) {
            hazardMarkerContainer[level[i].value] = L.layerGroup(hazardMarkers[layer][level[i].value]).setZIndex(999).addTo(map);

        }
    }
    setTimeout(() => { document.getElementById("loader").classList.remove("show-animation");  }, 250);    
};

window.changeHazardLevel = function (layer) {
    document.getElementById("loader").classList.add("show-animation");
    if(hazardMarkerContainer){
        Object.keys(hazardMarkerContainer).forEach(function(key) {
            hazardMarkerContainer[key].clearLayers();
        });
    }
    // layers = document.getElementsByName("menu-layer");
    // for (var i = 0; i < layers.length; i++) {
    //     if (layers[i].checked) {
    //         layer = layers[i].value;
    //         break;
    //     }
    // };
    layer = "BANJIR";
    let level = document.getElementsByName('variable-level');
    for (var i = 0; i < level.length; i++) {
        if (level[i].checked) {
            hazardMarkerContainer[level[i].value] = L.layerGroup(hazardMarkers[layer][level[i].value]).setZIndex(999).addTo(map);

        }
    }
    setTimeout(() => { document.getElementById("loader").classList.remove("show-animation");  }, 250);
};

window.initRegencyMap = function () {

    regency = L.geoJson(null, {style: style});
    omnivore.topojson(flagsUrl + 'data/kabupaten.json', null, regency).on('ready', function() {
            getDibi();
    }).addTo(map); ;

    function style(feature) {
        return {
            weight: 0.25,
            // opacity: 0.75,
            fillColor: '#545454',
            color: '#949494',
            fillOpacity: 0.6,
            opacity: 0.6
        };
    }
    map.invalidateSize();
}

window.initProvinceMap = function () {
    province = L.geoJson(null, {style: style});
    omnivore.topojson(flagsUrl + 'data/provinsi.json', null, province); ;

    function style(feature) {
        return {
            weight: 0.25,
            // opacity: 0.75,
            fillColor: '#545454',
            color: '#949494',
            fillOpacity: 0.6,
            opacity: 0.6
        };
    }
    map.invalidateSize();
}


window.changeMap = function (){
    map.removeLayer(regency);
    map.removeLayer(province);
    lists = document.getElementsByName('map-index');
    for (var i = 0; i < lists.length; i++) {
        if (lists[i].checked) {
            if (lists[i].value == 'province') {
                map.addLayer(province);
            }
            if (lists[i].value == 'regency') {
                map.addLayer(regency);
            }
            break;
        }
    }
}

window.getDibi = function () {
    document.getElementById("loader").classList.add("show-animation");
    document.getElementById("map-date").innerHTML = document.getElementById("hazard").options[document.getElementById("hazard").selectedIndex].innerHTML + " " + document.getElementById("month").options[document.getElementById("month").selectedIndex].innerHTML + " " + document.getElementById("year").options[document.getElementById("year").selectedIndex].innerHTML;
    let data = { 
        year: document.getElementById("year").value,
        month: document.getElementById("month").value,
        hazard: document.getElementById("hazard").value 
    };
    fetch('getDibi?' + new URLSearchParams(data), {
        method: 'get',
        headers: {
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        dataRegency = [];
        regency.eachLayer(function(layer) {
            layer.unbindPopup();
            var feature = layer.toGeoJSON();
            let regencyCount = data.filter(function (val) {
                return val.regency_id == feature.properties.ID_KAB_CLE;
            });
            if (document.getElementById("month").value == 'all') {
                if (regencyCount.length < 10) {
                    color = "#1a9641";
                }else if (regencyCount.length < 25) {
                    color = "#a6d96a";
                }else if (regencyCount.length < 50) {
                    color = "#ffffbf";
                }else if (regencyCount.length < 75) {
                    color = "#fdae61";
                }else{
                    color = "#d7191c";
                }
            }else{
                if (regencyCount.length < 5) {
                    color = "#1a9641";
                }else if (regencyCount.length < 10) {
                    color = "#a6d96a";
                }else if (regencyCount.length < 20) {
                    color = "#ffffbf";
                }else if (regencyCount.length < 30) {
                    color = "#fdae61";
                }else{
                    color = "#d7191c";
                }
            }
            dataRegency.push({"name": feature.properties.KABUPATEN, "value": regencyCount.length, "color": color});

            var customOptions =
            {
                'className' : 'custom'
            };
            layer.bindPopup('<div">' + 
                '<p class="my-1 p-1 uppercase font-bold text-black" style="background-color:' + color + '">' + feature.properties.KABUPATEN + '</p>' + 
                '<p class="px-1 leading-5"><span class="text-white">Jumlah Bencana:</span> <span class="font-bold" style="color:' + color + '">' + regencyCount.length + '</span> Kejadian</p>' +
                '<button class="modal-kabupaten-open text-white text-xs bg-yellow-600 px-2 py-1 ml-1 hover:bg-yellow-500" onclick="getDibiRegency(' + feature.properties.ID_KAB_CLE + ');">Informasi Detail</button>' + '</div>',customOptions);            
            layer.setStyle({
                color: '#fff',
                fillColor: color,
                strokeWeight: 1,
                fillOpacity: 0.6,
                opacity:0.8
            });
        });
        contentRegency = "";
        dataRegency.sort((a,b) => b.value - a.value);
        for (var i = 0; i < dataRegency.length; i++) {
            contentRegency += "<div class='hover:bg-opacity-20 hover:bg-white flex justify-between py-1 ml-1 px-2' style='border-bottom:1px solid rgba(0,0,0,.1);border-left:3px solid " + dataRegency[i].color + ";'><span class='my-auto text-white'>" + dataRegency[i].name + "</span><span class='my-auto font-bold' style='color:" + dataRegency[i].color + "'> " + dataRegency[i].value + " Bencana</span></div><hr style='color:rgba(255,255,255,.1);margin:1px 0'>";
        }
        document.getElementById("table-kabupaten").innerHTML = contentRegency;

        dataProvince = [];
        province.eachLayer(function(layer) {
            var feature = layer.toGeoJSON();
            let provinceCount = data.filter(function (val) {
                return val.province_name == feature.properties.PROVINSI;
            });
            if (document.getElementById("month").value == 'all') {
                if (provinceCount.length < 50) {
                    color = "#1a9641";
                }else if (provinceCount.length < 100) {
                    color = "#a6d96a";
                }else if (provinceCount.length < 250) {
                    color = "#ffffbf";
                }else if (provinceCount.length < 500) {
                    color = "#fdae61";
                }else{
                    color = "#d7191c";
                }
            }else{
                if (provinceCount.length < 25) {
                    color = "#1a9641";
                }else if (provinceCount.length < 50) {
                    color = "#a6d96a";
                }else if (provinceCount.length < 75) {
                    color = "#ffffbf";
                }else if (provinceCount.length < 100) {
                    color = "#fdae61";
                }else{
                    color = "#d7191c";
                }
            }
            dataProvince.push({"name": feature.properties.PROVINSI, "value": provinceCount.length, "color": color});
            var customOptions =
            {
                'className' : 'custom'
            };            
            layer.bindPopup('<div">' + 
                '<p class="my-1 p-1 uppercase font-bold text-black" style="background-color:' + color + '">' + feature.properties.PROVINSI + '</p>' + 
                '<p class="px-1 leading-5"><span class="text-white">Jumlah Bencana:</span> <span class="font-bold" style="color:' + color + '">' + provinceCount.length + '</span> Kejadian</p>' +
                '<button class="modal-province-open text-white text-xs bg-yellow-600 px-2 py-1 ml-1 hover:bg-yellow-500" onclick="getDibiProvince(' + feature.properties.ID_PROV + ');">Informasi Detail</button>' +
                '</div>',customOptions);               
            layer.setStyle({
                color: '#fff',
                fillColor: color,
                strokeWeight: 1,
                fillOpacity: 0.6,
                opacity:0.8
            });
        });
        contentProvince = "";
        dataProvince.sort((a,b) => b.value - a.value);
        for (var i = 0; i < dataProvince.length; i++) {
            contentProvince += "<div class='hover:bg-opacity-20 hover:bg-white flex justify-between py-1 ml-1 px-2' style='border-bottom:1px solid rgba(0,0,0,.1);border-left:3px solid " + dataProvince[i].color + ";'><span class='my-auto text-white'>" + dataProvince[i].name + "</span><span class='my-auto font-bold' style='color:" + dataProvince[i].color + "'> " + dataProvince[i].value + " Bencana</span></div><hr style='color:rgba(255,255,255,.1);margin:1px 0'>";
        }
        document.getElementById("table-provinsi").innerHTML = contentProvince;        

        setTimeout(() => { document.getElementById("loader").classList.remove("show-animation");  }, 250);
    })
    .catch((error) => {
      console.error('Error:', error);
      setTimeout(() => { document.getElementById("loader").classList.remove("show-animation");  }, 250);
    });
};


window.getDibiRegency = function (id) {
    document.getElementById("loader").classList.add("show-animation");
    let data = { 
        id: id 
    };
    fetch('getDibiRegency?' + new URLSearchParams(data), {
        method: 'get',
        headers: {
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        toggleModalKabupaten();

        function groupBy(list, keyGetter) {
            const map = new Map();
            list.forEach((item) => {
                 const key = keyGetter(item);
                 const collection = map.get(key);
                 if (!collection) {
                     map.set(key, [item]);
                 } else {
                     collection.push(item);
                 }
            });
            return map;
        }
        let dataBar = [];
        let dataColumn = [];
        let dataColumnX = [];
        dibi = groupBy(data.dibi, dibi => dibi.hazard);
        for (var i = moment(new Date).format("Y")-4; i <= moment(new Date).format("Y"); i++) {
            dataColumnX.push(i);
        }
     
        Array.from( dibi.keys() ).forEach(value => {
            dataTmp = [];
            for (var i = moment(new Date).format("Y")-4; i <= moment(new Date).format("Y"); i++) {
                tmp = data.dibi.filter(function (el) {
                  return moment(el.date).format("Y") == i
                });
                count = tmp.filter(function (dibi) {
                  return dibi.hazard == value
                }).length;
                dataTmp.push(count);
            }
            dataColumn.push({name: value,data: dataTmp})
        });

        for (var i = moment(new Date).format("Y"); i > moment(new Date).format("Y")-5; i--) {
            tmp = data.dibi.filter(function (el) {
              return moment(el.date).format("Y") == i
            });
            dataTmp = [];
            Array.from( dibi.keys() ).forEach(value => {
                count = tmp.filter(function (dibi) {
                  return dibi.hazard == value
                }).length;
                dataTmp.push(count);
            });
            dataBar.push({name: i,data: dataTmp})
        }
        document.getElementById('modal-regency-title').innerHTML = "Data Informasi Bencana " + data.location.name;

        Highcharts.chart('chart-regency-1', {
            chart: {
                type: 'bar'
            },
            exporting:{
                enabled: false
            },
            credits:{
                enabled: false
            },
            title: {
                text: 'Bencana Berdasarkan Jenis'
            },
            xAxis: {
                categories: Array.from( dibi.keys() ),
                title: {
                    text: null
                },
                max:(Array.from( dibi.keys() ).length > 3 ? 3 : Array.from( dibi.keys() ).length),
                scalable: true,
                scrollbar: {
                    margin:30,
                    enabled:true,
                    barBackgroundColor: '#999',
                    barBorderRadius: 0,
                    barBorderWidth: 0,
                    buttonBackgroundColor: '#fff',
                    buttonBorderWidth: 0,
                    buttonBorderRadius: 0,
                    trackBackgroundColor: '#f1f1f1',
                    trackBorderWidth: 0,
                    trackBorderRadius: 0,
                    trackBorderColor: '#f1f1f1'
                },
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Kejadian Bencana',
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0;padding-left:2px;"><b>{point.y:.0f} Kejadian</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                series:{
                    // stacking: 'normal',
                    borderWidth: 0,
                    borderHeight: 0,
                     dataLabels: {
                        enabled: true,
                        padding:5,
                        style:{
                            fontSize: '8px',
                            color:'#111',
                            textOutline:'none'
                        },
                        useHTML: true,
                        formatter: function () {
                            return (this.y > 0 ? this.y.toLocaleString('id') : "");
                        }
                    }


                },
                bar: {borderWidth:0} 
            },
            legend: {
                layout: 'horizontal',
                align: 'center',
                verticalAlign: 'bottom',
                floating: false,
                borderWidth: 0,
                backgroundColor:
                Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
                shadow: false
            },
            series: dataBar
        });

        Highcharts.chart('chart-regency-2', {
            chart: {
                type: 'column'
            },
            exporting:{
                enabled:false
            },
            credits:{
                enabled:false
            },
            title: {
                text: 'Bencana Berdasarkan Waktu'
            },
            xAxis: {
                categories: dataColumnX,
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Kejadian Bencana'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0;padding-left:2px;"><b>{point.y:.0f} Kejadian</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            legend: {
                layout: 'vertical',
                align: 'left',
                verticalAlign: 'top',
                floating: true,
                x: 50,
                y: 40,                
                borderWidth: 0,
                backgroundColor: 'transparent',
                shadow: false
            },            
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: dataColumn
        });


        setTimeout(() => { document.getElementById("loader").classList.remove("show-animation");  }, 250);
    })
    .catch((error) => {
      console.error('Error:', error);
      setTimeout(() => { document.getElementById("loader").classList.remove("show-animation");  }, 250);
    });
};

window.getDibiProvince = function (province) {
    document.getElementById("loader").classList.add("show-animation");
    let data = { 
        province: province 
    };
    fetch('getDibiProvince?' + new URLSearchParams(data), {
        method: 'get',
        headers: {
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        toggleModalProvinsi();

        function groupBy(list, keyGetter) {
            const map = new Map();
            list.forEach((item) => {
                 const key = keyGetter(item);
                 const collection = map.get(key);
                 if (!collection) {
                     map.set(key, [item]);
                 } else {
                     collection.push(item);
                 }
            });
            return map;
        }
        let dataBar = [];
        let dataColumn = [];
        let dataColumnX = [];
        dibi = groupBy(data.dibi, dibi => dibi.hazard);
        for (var i = moment(new Date).format("Y")-4; i <= moment(new Date).format("Y"); i++) {
            dataColumnX.push(i);
        }
     
        Array.from( dibi.keys() ).forEach(value => {
            dataTmp = [];
            for (var i = moment(new Date).format("Y")-4; i <= moment(new Date).format("Y"); i++) {
                tmp = data.dibi.filter(function (el) {
                  return moment(el.date).format("Y") == i
                });
                count = tmp.filter(function (dibi) {
                  return dibi.hazard == value
                }).length;
                dataTmp.push(count);
            }
            dataColumn.push({name: value,data: dataTmp})
        });

        for (var i = moment(new Date).format("Y"); i > moment(new Date).format("Y")-5; i--) {
            tmp = data.dibi.filter(function (el) {
              return moment(el.date).format("Y") == i
            });
            dataTmp = [];
            Array.from( dibi.keys() ).forEach(value => {
                count = tmp.filter(function (dibi) {
                  return dibi.hazard == value
                }).length;
                dataTmp.push(count);
            });
            dataBar.push({name: i,data: dataTmp})
        }
        document.getElementById('modal-province-title').innerHTML = "Data Informasi Bencana " + data.location.name;

        Highcharts.chart('chart-province-1', {
            chart: {
                type: 'bar'
            },
            exporting:{
                enabled: false
            },
            credits:{
                enabled: false
            },
            title: {
                text: 'Bencana Berdasarkan Jenis'
            },
            xAxis: {
                categories: Array.from( dibi.keys() ),
                title: {
                    text: null
                },
                max:(Array.from( dibi.keys() ).length > 3 ? 3 : Array.from( dibi.keys() ).length),
                scalable: true,
                scrollbar: {
                    margin:30,
                    enabled:true,
                    barBackgroundColor: '#999',
                    barBorderRadius: 0,
                    barBorderWidth: 0,
                    buttonBackgroundColor: '#fff',
                    buttonBorderWidth: 0,
                    buttonBorderRadius: 0,
                    trackBackgroundColor: '#f1f1f1',
                    trackBorderWidth: 0,
                    trackBorderRadius: 0,
                    trackBorderColor: '#f1f1f1'
                },
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Kejadian Bencana',
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0;padding-left:2px;"><b>{point.y:.0f} Kejadian</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                series:{
                    // stacking: 'normal',
                    borderWidth: 0,
                    borderHeight: 0,
                     dataLabels: {
                        enabled: true,
                        padding:5,
                        style:{
                            fontSize: '8px',
                            color:'#111',
                            textOutline:'none'
                        },
                        useHTML: true,
                        formatter: function () {
                            return (this.y > 0 ? this.y.toLocaleString('id') : "");
                        }
                    }


                },
                bar: {borderWidth:0} 
            },
            legend: {
                layout: 'horizontal',
                align: 'center',
                verticalAlign: 'bottom',
                floating: false,
                borderWidth: 0,
                backgroundColor:
                Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
                shadow: false
            },
            series: dataBar
        });

        Highcharts.chart('chart-province-2', {
            chart: {
                type: 'column'
            },
            exporting:{
                enabled:false
            },
            credits:{
                enabled:false
            },
            title: {
                text: 'Bencana Berdasarkan Waktu'
            },
            xAxis: {
                categories: dataColumnX,
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Kejadian Bencana'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0;padding-left:2px;"><b>{point.y:.0f} Kejadian</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            legend: {
                layout: 'vertical',
                align: 'left',
                verticalAlign: 'top',
                floating: true,
                x: 50,
                y: 40,                
                borderWidth: 0,
                backgroundColor: 'transparent',
                shadow: false
            },            
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: dataColumn
        });


        setTimeout(() => { document.getElementById("loader").classList.remove("show-animation");  }, 250);
    })
    .catch((error) => {
      console.error('Error:', error);
      setTimeout(() => { document.getElementById("loader").classList.remove("show-animation");  }, 250);
    });
};

window.changeHotspotLevel = function (layer) {
    document.getElementById("loader").classList.add("show-animation");
    if(hazardMarkerContainer){
        Object.keys(hazardMarkerContainer).forEach(function(key) {
            hazardMarkerContainer[key].clearLayers();
        });
    }
    let level = document.getElementsByName('variable-level');
    for (var i = 0; i < level.length; i++) {
        if (level[i].checked) {
            hazardMarkerContainer[level[i].value] = L.layerGroup(hazardMarkers[level[i].value]).setZIndex(999).addTo(map);

        }
    }
    setTimeout(() => { document.getElementById("loader").classList.remove("show-animation");  }, 250);
};

window.getHotspot = function () {
    if(hazardMarkerContainer){
        Object.keys(hazardMarkerContainer).forEach(function(key) {
            hazardMarkerContainer[key].clearLayers();
        });
        hazardMarkers = [];
    }
    hazardMarkerContainer = {};
    document.getElementById("loader").classList.add("show-animation");
    document.getElementById("title-date").innerHTML = moment(document.getElementById("date").value).format("DD MMMM YYYY");
    let categories = ["","Rendah","Sedang","Tinggi"];
    let data = { 
        date: document.getElementById("date").value,
        source: document.getElementById("data-source").value, 
    };
    let countHazard = document.getElementsByClassName('count-hazard');
    for (var i = 0; i < countHazard.length; i++) {
        countHazard[i].innerHTML = "-";
    }
    // let tableHazard = document.getElementsByClassName('table-hazard');
    // for (var i = 0; i < tableHazard.length; i++) {
    //     tableHazard[i].innerHTML = "<tr><td  class='p-1 border-b border-white border-opacity-20 text-center' colspan='3'>-</td></tr>";
    // }
    fetch('getHotspot?' + new URLSearchParams(data), {
        method: 'get',
        headers: {
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {

        // for (var i = 0; i < tableHazard.length; i++) {
        //     tableHazard[i].innerHTML = "";
        //     count = 1;
        //     for (const value in data[hazards[i]]) {
        //         tableHazard[i].innerHTML += "<tr><td class='p-1 border-b border-white border-opacity-20 text-center'>" + count + "</td><td class='p-1 border-b border-white border-opacity-20'>" + data[hazards[i]][value][0]["KABUPATEN"] + "</td><td class='p-1 border-b border-white border-opacity-20 text-center'>" + categories[Math.max.apply(Math, data[hazards[i]][value].map(function(o) { return o.category; }))] + "</td></tr>";
        //         count++;
        //     }
        // }
        var dataIndonesia = data.filter(function (el) {
          return el.location == "Indonesia"
        });
        for (var i = 0; i < countHazard.length; i++) {
            if (i == 0) {
                count = dataIndonesia.length;
            }else{
                count = dataIndonesia.filter(function (el) {return el.level == (i)}).length;
            }
            countHazard[i].innerHTML = count;
        }
        let provinces = dataIndonesia.reduce(function (r, a) {
            r[a.province] = r[a.province] || [];
            r[a.province].push(a);
            return r;
        }, Object.create(null));

        provinceSort = [];
        for (const province in provinces) {
            provinceSort.push([province,provinces[province].length]);
        }
        provinceSort.sort(function(a, b) {
            return b[1] - a[1];
        });
        striped = false;
        document.getElementsByClassName('table-hotspot')[1].innerHTML = "";
        for (var i = 0; i < provinceSort.length; i++) {
            document.getElementsByClassName('table-hotspot')[1].innerHTML += "<tr class='" + (striped ? 'bg-blue-500-light bg-opacity-20' : '') + "'><td class='p-1 border-b border-white border-opacity-20'>" + provinceSort[i][0] + "</td><td class='p-1 border-b border-white border-opacity-20 text-center'>" + provinceSort[i][1] + "</td></tr>";
            striped = !striped;
        }

        let regencies = dataIndonesia.reduce(function (r, a) {
            r[a.regency] = r[a.regency] || [];
            r[a.regency].push(a);
            return r;
        }, Object.create(null));

        document.getElementsByClassName('table-hotspot')[0].innerHTML = "";
        count = 1;

        regencySort = [];
        for (const regency in regencies) {
            regencySort.push([regency,regencies[regency].length]);
        }
        regencySort.sort(function(a, b) {
            return b[1] - a[1];
        });
        striped = false;
        for (var i = 0; i < regencySort.length; i++) {
            document.getElementsByClassName('table-hotspot')[0].innerHTML += "<tr class='" + (striped ? 'bg-blue-500-light bg-opacity-20' : '') + "'><td class='p-1 border-b border-white border-opacity-20'>" + regencySort[i][0] + "</td><td class='p-1 border-b border-white border-opacity-20 text-center'>" + regencySort[i][1] + "</td></tr>";
            striped = !striped;
        }
        hazardMarkers = [];
            for (var j = 1; j < categories.length; j++) {
                hazardMarkers[categories[j]] = [];
            }
            for (var i = 0; i < data.length; i++) {
                let color;
                if (data[i]["level"] == 1) {
                    level = 1;
                    color = "rgba(124,179,66 ,1)";
                    colorAnimation = "green";
                }else if (data[i]["level"] == 2) {
                    level = 2;
                    color = "rgba(251,192,45 ,1)";
                    colorAnimation = "yellow";
                }else if (data[i]["level"] == 3) {
                    level = 3;
                    color = "rgba(198,40,40 ,1)";
                    colorAnimation = "red";
                }else{
                    level = 3;
                    color = "rgba(198,40,40 ,1)";
                    colorAnimation = "red";
                }
                let hazardMarker = L.circleMarker([data[i]['lat'], data[i]['lon']], {
                    radius: 4,
                    fillColor: color,
                    color: "#000",
                    weight: 0.25,
                    opacity: 1,
                    fillOpacity: 1
                });
                customOptions = {
                    'className' : 'custom'
                }; 
                hazardMarker.bindPopup('<div">' + 
                    '<p class="my-1 p-1 uppercase font-bold text-black" style="background-color:' + color + '">Status: ' + categories[level] + '</p>' +
                    '<p class="my-1 p-1 d-block uppercase font-bold text-white bg-blue-500" style="margin-top:5px !important;">Lokasi</p>' + 
                    (data[i]["province"] ? '<p class="px-1 leading-5"><span class="text-blue-500">Provinsi:</span> ' + data[i]["province"] + '</p>' : "") +
                    (data[i]["regency"] ? '<p class="px-1 leading-5"><span class="text-blue-500">Kabupaten/Kota:</span> ' + data[i]["regency"] + '</p>' : "") +
                    (data[i]["district"] ? '<p class="px-1 leading-5"><span class="text-blue-500">Kecamatan:</span> ' + data[i]["district"] + '</p>' : "") +
                    (data[i]["village"] ? '<p class="px-1 leading-5"><span class="text-blue-500">Desa/Kelurahan:</span> ' + data[i]["village"] + '</p>' : "") +
                    '<p class="px-1 leading-5"><span class="text-blue-500">Koordinat:</span> ' + data[i]["lat"].toFixed(2) + (data[i]["lat"] > 0 ? " LU" : " LS") + ', '+ data[i]["lon"].toFixed(2) + ' BT</p>' +
                    '<p class="my-1 p-1 d-block uppercase font-bold text-white bg-yellow-600" style="margin-top:5px !important;">Informasi Lahan</p>' + 
                    (data[i]["company"] ? '<p class="px-1 leading-5"><span class="text-yellow-500">Perusahaan:</span> ' + data[i]["company"] + '</p>' : "") +
                    (data[i]["group"] ? '<p class="px-1 leading-5"><span class="text-yellow-500">Grup:</span> ' + data[i]["group"] + '</p>' : "") +
                    (data[i]["konsesi"] ? '<p class="px-1 leading-5"><span class="text-yellow-500">Konsesi:</span> ' + data[i]["konsesi"] + '</p>' : "") +
                    (data[i]["landcover"] ? '<p class="px-1 leading-5"><span class="text-yellow-500">Tutupan Lahan:</span> ' + data[i]["landcover"] + '</p>' : "") + 
                    '</div>',customOptions);                  
                hazardMarkers[categories[level]].push(hazardMarker);
                
            }
        let level = document.getElementsByName('variable-level');
        for (var i = 0; i < level.length; i++) {
            if (level[i].checked) {
                hazardMarkerContainer[level[i].value] = L.layerGroup(hazardMarkers[level[i].value]).setZIndex(999).addTo(map);

            }
        }

        setTimeout(() => { document.getElementById("loader").classList.remove("show-animation");  }, 250);
    })
    .catch((error) => {
      console.error('Error:', error);
      setTimeout(() => { document.getElementById("loader").classList.remove("show-animation");  }, 250);
    });    
    getFDRS();
};

window.getFDRS = function () {
    document.getElementById("loader").classList.add("show-animation");
        var colorRGB        = ['#1a9641', '#a6d96a', '#ffffbf', '#fdae61', '#d7191c'];
        var colorBreak      = [0,1,2,3,4];

        if(layerData){
            layerData.remove();
            layerData = null;
        }
        if (document.getElementById('check-fdrs').checked) {
            tiffData = null;
            d3.request(storagePath + '/storage/fdrs/fdrs_' + moment(document.getElementById("date").value).format("YYYYMMDD") + '.tif').responseType('arraybuffer').get(
                function(tiffData) {
                    let geo = L.ScalarField.fromGeoTIFF(tiffData.response);
                    layerData = L.canvasLayer.scalarField(geo, {
                        color: chroma.scale(colorRGB).domain(colorBreak),
                        opacity: 0.8,
                        interpolate: true
                    }).addTo(map);
                    layerData.on('click', function(e) {
                        if (e.value !== null) {
                            let v = e.value.toFixed(2);
                            let html = (`<span class="popupText"> ${v}</span>`);
                            let popup = L.popup()
                                .setLatLng(e.latlng)
                                .setContent(html)
                                .openOn(map);
                        }
                    });                    
                }
            );
        }
        setTimeout(() => { document.getElementById("loader").classList.remove("show-animation");  }, 250);  
};


window.getWeather = function () {
    document.getElementById("loader").classList.add("show-animation");
    var colorRGB        = {
        rain            : ['rgba(94,53,177 ,0)', 'rgba(94,53,177 ,1)', 'rgba(30,136,229 ,1)', 'rgba(0,172,193 ,1)', 'rgba(67,160,71 ,1)', 'rgba(192,202,51 ,1)', 'rgba(255,179,0 ,1)', 'rgba(244,81,30 ,1)', 'rgba(229,57,53 ,1)', 'rgba(216,27,96 ,1)'],
        rainAcc            : ['rgba(67,160,71 ,0)', 'rgba(67,160,71 ,1)', 'rgba(192,202,51 ,1)', 'rgba(255,179,0 ,1)', 'rgba(244,81,30 ,1)', 'rgba(229,57,53 ,1)', 'rgba(216,27,96 ,1)','rgba(142,36,170 ,1)'],
        wspd            : ['rgba(94,53,177 ,1)', 'rgba(30,136,229 ,1)', 'rgba(30,136,229 ,1)', 'rgba(0,172,193 ,1)', 'rgba(67,160,71 ,1)', 'rgba(192,202,51 ,1)', 'rgba(255,179,0 ,1)', 'rgba(244,81,30 ,1)', 'rgba(229,57,53 ,1)', 'rgba(216,27,96 ,1)'],
        temp            : ['rgba(94,53,177 ,0)', 'rgba(94,53,177 ,1)', 'rgba(30,136,229 ,1)', 'rgba(0,172,193 ,1)', 'rgba(67,160,71 ,1)', 'rgba(192,202,51 ,1)', 'rgba(255,179,0 ,1)', 'rgba(244,81,30 ,1)', 'rgba(229,57,53 ,1)', 'rgba(216,27,96 ,1)'],
        cloud           : ['rgba(94,53,177 ,0)','rgb(130, 0, 220)', 'rgb(34, 57, 254)', 'rgb(0, 202, 176)', 'rgb(158, 230, 49)', 'rgb(223, 221, 50)'],
        rh              : ['rgba(216,27,96 ,1)','rgba(229,57,53 ,1)','rgba(244,81,30 ,1)', 'rgba(255,179,0 ,1)', 'rgba(192,202,51 ,1)','rgba(67,160,71 ,1)', 'rgba(0,172,193 ,1)', 'rgba(30,136,229 ,1)','rgba(94,53,177 ,1)', 'rgba(94,53,177 ,1)'],
        wave            : ['rgba(94,53,177 ,1)', 'rgba(30,136,229 ,1)', 'rgba(30,136,229 ,1)', 'rgba(0,172,193 ,1)', 'rgba(67,160,71 ,1)', 'rgba(192,202,51 ,1)', 'rgba(255,179,0 ,1)', 'rgba(244,81,30 ,1)', 'rgba(229,57,53 ,1)', 'rgba(216,27,96 ,1)'],
        windWave        : ['rgba(94,53,177 ,0)', 'rgba(30,136,229 ,1)', 'rgba(30,136,229 ,1)', 'rgba(0,172,193 ,1)', 'rgba(67,160,71 ,1)', 'rgba(192,202,51 ,1)', 'rgba(255,179,0 ,1)', 'rgba(244,81,30 ,1)', 'rgba(229,57,53 ,1)', 'rgba(216,27,96 ,1)'],
        swell           : ['rgba(94,53,177 ,0)', 'rgba(30,136,229 ,1)', 'rgba(30,136,229 ,1)', 'rgba(0,172,193 ,1)', 'rgba(67,160,71 ,1)', 'rgba(192,202,51 ,1)', 'rgba(255,179,0 ,1)', 'rgba(244,81,30 ,1)', 'rgba(229,57,53 ,1)', 'rgba(216,27,96 ,1)'],
        current         : ['rgba(94,53,177 ,1)', 'rgba(30,136,229 ,1)', 'rgba(30,136,229 ,1)', 'rgba(0,172,193 ,1)', 'rgba(67,160,71 ,1)', 'rgba(192,202,51 ,1)', 'rgba(255,179,0 ,1)', 'rgba(244,81,30 ,1)', 'rgba(229,57,53 ,1)', 'rgba(216,27,96 ,1)'],
        sst             : ['rgba(94,53,177 ,1)', 'rgba(94,53,177 ,1)', 'rgba(30,136,229 ,1)', 'rgba(0,172,193 ,1)', 'rgba(67,160,71 ,1)', 'rgba(192,202,51 ,1)', 'rgba(255,179,0 ,1)', 'rgba(244,81,30 ,1)', 'rgba(229,57,53 ,1)', 'rgba(216,27,96 ,1)'],
        salinity        : ['rgba(94,53,177 ,1)', 'rgba(94,53,177 ,1)', 'rgba(30,136,229 ,1)', 'rgba(0,172,193 ,1)', 'rgba(67,160,71 ,1)', 'rgba(192,202,51 ,1)', 'rgba(255,179,0 ,1)', 'rgba(244,81,30 ,1)', 'rgba(229,57,53 ,1)', 'rgba(216,27,96 ,1)'],
        radiation       : ['rgba(94,53,177 ,0)', 'rgba(94,53,177 ,1)', 'rgba(30,136,229 ,1)', 'rgba(30,136,229 ,1)', 'rgba(0,172,193 ,1)', 'rgba(67,160,71 ,1)', 'rgba(192,202,51 ,1)', 'rgba(255,179,0 ,1)', 'rgba(244,81,30 ,1)', 'rgba(229,57,53 ,1)', 'rgba(216,27,96 ,1)']
    }
    var colorBreak      = {
        rain            : [0,5,10,15,20,25,30,35,40],
        rainAcc         : [0,25,50,75,100,125,150],
        wspd            : [0,1,2,5,6,8,10,12,14],
        temp            : [-50+273.15,18+273.15,20+273.15,22+273.15,24+273.15,26+273.15,28+273.15,30+273.15,32+273.15,34+273.15],
        cloud           : [0,20,40,60,80,100],
        rh              : [55,60,65,75,80,85,90,95,100],
        wave            : [0.0,0.5,1.0,1.5,2.0,2.5,3.0,3.5,4.0],
        windWave        : [0.0,0.5,1.0,1.5,2.0,2.5,3.0,3.5,4.0],
        swell           : [0.0,0.5,1.0,1.5,2.0,2.5,3.0,3.5,4.0],
        current         : [0,0.25,0.5,0.75,1,1.25,1.5,1.75,2],
        sst             : [18,20,22,24,26,28,30,32,34],
        salinity        : [32,32.5,33,33.5,34,34.5,35,35.5,36],
        radiation       : [0,200,300,400,500,600,700,800,900,1000]
    }
    var colorLegend         = {
        rain            : '<i style="width: calc(100%/10);">0</i><i style="width: calc(100%/10);">5</i><i style="width: calc(100%/10);">10</i><i style="width: calc(100%/10);">15</i><i style="width: calc(100%/10);">20</i><i style="width: calc(100%/10);">25</i><i style="width: calc(100%/10);">30</i><i style="width: calc(100%/10);">35</i><i style="width: calc(100%/10);">40</i><i style="width: calc((100%/10));">mm</i>',
        wspd            : '<i style="width: calc(100%/10);">0</i><i style="width: calc(100%/10);">1</i><i style="width: calc(100%/10);">2</i><i style="width: calc(100%/10);">5</i><i style="width: calc(100%/10);">6</i><i style="width: calc(100%/10);">8</i><i style="width: calc(100%/10);">10</i><i style="width: calc(100%/10);">12</i><i style="width: calc(100%/10);">14</i><i style="width: calc((100%/10));">m/s</i>',
        temp            : '<i style="width: calc(100%/10);">18</i><i style="width: calc(100%/10);">20</i><i style="width: calc(100%/10);">22</i><i style="width: calc(100%/10);">24</i><i style="width: calc(100%/10);">26</i><i style="width: calc(100%/10);">28</i><i style="width: calc(100%/10);">30</i><i style="width: calc(100%/10);">32</i><i style="width: calc(100%/10);">34</i><i style="width: calc((100%/10));"><sup>o</sup>C</i>',
        rh              : '<i style="width: calc(100%/11);">55</i><i style="width: calc(100%/11);">60</i><i style="width: calc(100%/11);">65</i><i style="width: calc(100%/11);">70</i><i style="width: calc(100%/11);">75</i><i style="width: calc(100%/11);">80</i><i style="width: calc(100%/11);">85</i><i style="width: calc(100%/11);">90</i><i style="width: calc(100%/11);">95</i><i style="width: calc(100%/11);">100</i><i style="width: calc((100%/11));">%</i>'
    }
    var layerInfo         = {
        rain            : ['Curah Hujan', 'mm'],
        wspd            : ['Kecepatan Angin', 'm/s'],
        temp            : ['Temperatur Udara', '<sup>o</sup>C'],
        rh              : ['Kelembapan',"%"]
    }

        if(layerData){
            layerData.remove();
            layerData = null;
        }
        tiffData = null;
        let menuLayer = document.getElementsByName('menu-layer');
        for (var i = 0; i < menuLayer.length; i++) {
            if (menuLayer[i].checked) {
                layer = menuLayer[i].value;
                break;

            }
        }
        document.getElementById('map-date').innerHTML = moment(document.getElementById("date").value + " " + document.getElementById("time").value + ":00").format("DD MMMM YYYY HH:00");
        date = moment(document.getElementById("date").value + " " + document.getElementById("time").value + ":00").utc().format("YYYYMMDDHH"); 
        d3.request(storagePath + '/storage/weather/' + layer + '/' + layer + '-' + date + '.tif').responseType('arraybuffer').get(
            function(tiffData) {
                let geo = L.ScalarField.fromGeoTIFF(tiffData.response);
                layerData = L.canvasLayer.scalarField(geo, {
                    color: chroma.scale(colorRGB[layer]).domain(colorBreak[layer]),
                    opacity: 0.6,
                    interpolate: true
                }).addTo(map);
                layerData.on('click', function(e) {
                    if (e.value !== null && action != true) {
                        let v = e.value.toFixed(2);
                        let html = (`<span class="popupText" style="margin-right:20px;">${layerInfo[layer][0]}: ${(layer == 'temp' ? (v-273.15).toFixed(1) : v)} ${layerInfo[layer][1]}</span>`);
                        let popup = L.popup()
                            .setLatLng(e.latlng)
                            .setContent(html)
                            .openOn(map);
                    }
                });                    
            }
        );

        var grad = chroma.scale(colorRGB[layer]).domain(colorBreak[layer]);
            var colorGradient = {
                rain        : 'linear-gradient(to right,' + grad(0) + ', ' + grad(5) + ', ' + grad(10) + ', ' + grad(15) + ', ' + grad(20) + ', ' + grad(25) + ', ' + grad(30) + ', ' + grad(35) + ', ' + grad(40) + ', ' + grad(40) + ', ' + grad(40) + ')',
                wspd        : 'linear-gradient(to right,' + grad(0) + ', ' + grad(1) + ', ' + grad(2) + ', ' + grad(5) + ', ' + grad(6) + ', ' + grad(8) + ', ' + grad(10) + ', ' + grad(12) + ', ' + grad(14) + ', ' + grad(14) + ')',
                temp        : 'linear-gradient(to right,' + grad(18+273.15) + ', ' + grad(20+273.15) + ', ' + grad(22+273.15) + ', ' + grad(24+273.15) + ', ' + grad(26+273.15) + ', ' + grad(28+273.15) + ', ' + grad(30+273.15) + ', ' + grad(32+273.15) + ', ' + grad(34+273.15) + ', ' + grad(34+273.15) + ')',
                rh          : 'linear-gradient(to right,' + grad(55) + ', ' + grad(60) + ', ' + grad(65) + ', ' + grad(70) + ', ' + grad(75) + ', ' + grad(80) + ', ' + grad(85) + ', ' + grad(90) + ', ' + grad(95) + ', ' + grad(100) + ', ' + grad(100) + ')'
      
            }
        var bg                      = document.getElementById('weather-legend-container');
        bg.style.backgroundImage    = colorGradient[layer];
        bg.innerHTML                = colorLegend[layer];

        if (velocityLayer) {
                velocityLayer.remove();
        }

        let data = { 
            date: date
        };
        fetch('getWindmap?' + new URLSearchParams(data), {
            method: 'get',
            headers: {
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
                velocityLayer = L.velocityLayer({
                    displayValues: false,
                    data: data[0],
                    maxVelocity: 10,
                    lineWidth:1,
                    velocityScale: 0.005,
                    colorScale: ['#fff']  
                }).addTo(map);

        })
        .catch((error) => {
          console.error('Error:', error);
          setTimeout(() => { document.getElementById("loader").classList.remove("show-animation");  }, 250);
        }); 

        setTimeout(() => { document.getElementById("loader").classList.remove("show-animation");  }, 250);
        setTimeout(() => { map.fitBounds(map.getBounds());  }, 1000);  
};

window.getKRB = function () {
    document.getElementById("loader").classList.add("show-animation");
        if(layerKRB){
            layerKRB.remove();
            layerKRB = null;
        }
        if (document.getElementById('check-krb').checked) {
            layerKRB = L.esri.dynamicMapLayer({
                url: "https://sipkn.kemenkeu.go.id/arcgis/rest/services/Kawasan_Rawan_Bencana_Gunung_Api/MapServer",
                opacity: 0.5
            }).addTo(map);
        }
        setTimeout(() => { document.getElementById("loader").classList.remove("show-animation");  }, 250);  
};

window.getInariskHazard = function (hazard) {
    document.getElementById("loader").classList.add("show-animation");
        if(layerData){
            layerData.remove();
            layerData = null;
        }
        let inariskType = document.querySelector('input[name="layer-inarisk-type"]:checked').value;
        if (inariskType != "no_layer") {
            layerData = L.esri.imageMapLayer({
                url: "https://inarisk1.bnpb.go.id:6443/arcgis/rest/services/inaRISK/layer_" + inariskType + "_" + hazard + "/ImageServer",
                opacity: 0.5
            }).addTo(map);
        }
        setTimeout(() => { document.getElementById("loader").classList.remove("show-animation");  }, 250);  
};

window.getInariskType = function (type) {
    document.getElementById("loader").classList.add("show-animation");
        if(layerData){
            layerData.remove();
            layerData = null;
        }
        let hazard = document.querySelector('input[name="layer-inarisk-hazard"]:checked').value;
        if (type != "no_layer") {
            layerData = L.esri.imageMapLayer({
                url: "https://inarisk1.bnpb.go.id:6443/arcgis/rest/services/inaRISK/layer_" + type + "_" + hazard + "/ImageServer",
                opacity: 0.5
            }).addTo(map);
        }
        setTimeout(() => { document.getElementById("loader").classList.remove("show-animation");  }, 250);  
};

window.getVolcano = function () {
    document.getElementById("loader").classList.add("show-animation");
        if (volcanoLayer) {
            map.removeLayer(volcanoLayer);
        }
        var volcanoes = [];
        if (volcanoContainer) {
            volcanoContainer.remove();
        }   
        categories = ["","Level I (Normal)","Level II (Waspada)","Level III (Siaga)","Level IV (Awas)"];

            for (let i = 0; i < data.length; i++) {
                let color;
                let colorAnimation;
                if (parseInt(data[i].ga_status) == 1) {
                    level = 2;
                    color = "#37b896";
                    colorAnimation = "green";
                    image = (data[i].has_vona ? "erupt1.gif" : "");
                }else if (parseInt(data[i].ga_status) == 2) {
                    level = 3;
                    color = "#ffc107";
                    colorAnimation = "yellow";
                    image = (data[i].has_vona ? "erupt2.gif" : "");
                }else if (parseInt(data[i].ga_status) ==  3) {
                    level = 4;
                    color = "#dda15a";
                    colorAnimation = "orange";
                    image = (data[i].has_vona ? "erupt3.gif" : "");
                }else if (parseInt(data[i].ga_status) == 4) {
                    level = 5;
                    color = "#e02f22";
                    colorAnimation = "red";
                    image = (data[i].has_vona ? "erupt4.gif" : "");
                }else{
                    level = 1;
                    color = "#4bb2e8";
                    colorAnimation = "light-blue";
                }

                let randomPulse = Math.floor((Math.random() * 5) + 1);
                let myIcon_pulse = L.divIcon({
                    className: 'css-icon',
                    html: (data[i].has_vona ?  '<div class="marker-border marker-level' + level + ' flex justify-center pulse-animation-level' + level + '-' + randomPulse + '"><span class="align-self-center"></span><img src="' +  storagePath + '/img/mountain-' + level + '.png"></div>' : '<div class="marker-level' + level + ' flex justify-center"><span class="align-center"></span></div>'),
                    iconSize: (data[i].has_vona ? [50,50] : [12.5,12.5]),
                    iconAnchor: (data[i].has_vona ? [15, 15] : [9, 5])
                });

                let point  = L.marker([data[i].ga_lat_gapi,data[i].ga_lon_gapi], {
                    icon: myIcon_pulse
                });
                var customOptions =
                {
                    'className' : 'custom'
                };
                point.bindPopup('<div>' +
                    '<p class="p-1 uppercase font-bold bg-animation-' + colorAnimation + '">Status: ' + categories[data[i].ga_status] + '</p>' + 
                    '<p class="p-1"><span class="text-blue-light font-bold">Nama:</span> G. ' + data[i].ga_nama_gapi + '</p>' +
                    '<p class="p-1 bg-white bg-opacity-20"><span class="text-blue-light font-bold">Provinsi:</span> ' + data[i].ga_prov_gapi + '</p>'+
                    '<p class="p-1"><span class="text-blue-light font-bold">Kabupaten/Kota:</span> ' + data[i].ga_kab_gapi + '</p>'+
                    (data[i].activity ? ('<p class="p-1 text-center font-bold bg-blue-500 uppercase">Aktivitas Terakhir</p>'+
                    '<p class="p-1">' + data[i].activity.date + ' ' + data[i].activity.time + '</p>' +
                    '<p class="p-1">' + data[i].activity.article + '</p>') : "") +
                    ((data[i].eruption && data[i].has_vona) ? ('<p class="p-1 text-center uppercase font-bold bg-animation-' + colorAnimation + '">Erupsi Terakhir</p>'+
                    '<p class="p-1">' + data[i].eruption.date + ' ' + data[i].eruption.time + '</p>' +
                    '<p class="p-1">' + data[i].eruption.article + '</p>') : "") +

                    // + '<p class="bg-blue-light font-bold"><span>Letusan Terakhir:</span></p>'+
                    // + '<p>' + data[i].activity.date + '</p>' +
                    // + '<p>' + data[i].activity.time + '</p>' +
                    // + '<p>' + data[i].activity.article + '</p>' +
                '</div>',customOptions);

                volcanoes.push(point);
            }
            volcanoContainer = L.featureGroup(volcanoes).addTo(map);

        setTimeout(() => { document.getElementById("loader").classList.remove("show-animation");  }, 250);
        
    
        // omnivore.geojson(data, null, earthquakeLayer).addTo(map);  
};

window.getRadar = function () {
    document.getElementById("loader").classList.add("show-animation");
        if(layerData){
            layerData.remove();
            layerData = null;
        }
        tiffData = null;
        document.getElementById('map-date').innerHTML = moment(document.getElementById("date").value + " " + document.getElementById("time").value + ":00").format("DD MMMM YYYY HH:00");
        date = moment(document.getElementById("date").value + " " + document.getElementById("time").value + ":00").utc(); 
        layerData = L.tileLayer( 'https://tilecache.rainviewer.com/v2/radar/' + date.unix() + '/256/{z}/{x}/{y}/2/1_0.png', {
            tms         :false,
            maxZoom     : 10,
            minZoom     : 5,
            opacity     :.85,
        }).addTo(map);
        layerData.bringToFront();

        setTimeout(() => { document.getElementById("loader").classList.remove("show-animation");  }, 250);  
};

window.getSatellite = function () {
    document.getElementById("loader").classList.add("show-animation");
        if(layerData){
            layerData.remove();
            layerData = null;
        }
        tiffData = null;
        document.getElementById('map-date').innerHTML = moment(document.getElementById("date").value + " " + document.getElementById("time").value + ":00").format("DD MMMM YYYY HH:00");
        date = moment(document.getElementById("date").value + " " + document.getElementById("time").value + ":00").utc(); 
        layerData = L.tileLayer( 'http://167.205.106.70/web/himawari/tbb/' + date.format('YYYYMMDDHHmm') + '/{z}/{x}/{y}.png', {
            tms         :true,
            maxZoom     : 6,
            minZoom     : 5,
            opacity     :.85,
        }).addTo(map);
        layerData.bringToFront();

        setTimeout(() => { document.getElementById("loader").classList.remove("show-animation");  }, 250);  
};

window.sortTable = function (n,container,type,sort) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById(container);
  switching = true;
  // Set the sorting direction to ascending:
  dir = sort;
  /* Make a loop that will continue until
  no switching has been done: */
  while (switching) {
    // Start by saying: no switching is done:
    switching = false;
    rows = table.rows;
    /* Loop through all table rows (except the
    first, which contains table headers): */
    for (i = 1; i < (rows.length - 1); i++) {
      // Start by saying there should be no switching:
      shouldSwitch = false;
      /* Get the two elements you want to compare,
      one from current row and one from the next: */
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      /* Check if the two rows should switch place,
      based on the direction, asc or desc: */
    if (type == "text") {  
      if (dir == "asc") {
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          // If so, mark as a switch and break the loop:
          shouldSwitch = true;
          break;
        }
      } else if (dir == "desc") {
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
          // If so, mark as a switch and break the loop:
          shouldSwitch = true;
          break;
        }
      }
    }else{
      if (dir == "asc") {
        if (parseFloat(x.innerHTML) > parseFloat(y.innerHTML)) {
          // If so, mark as a switch and break the loop:
          shouldSwitch = true;
          break;
        }
      } else if (dir == "desc") {
        if (parseFloat(x.innerHTML) < parseFloat(y.innerHTML)) {
          // If so, mark as a switch and break the loop:
          shouldSwitch = true;
          break;
        }
      }        
    }
    }
    if (shouldSwitch) {
      /* If a switch has been marked, make the switch
      and mark that a switch has been done: */
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      // Each time a switch is done, increase this count by 1:
      switchcount ++;
    } else {
      /* If no switching has been done AND the direction is "asc",
      set the direction to "desc" and run the while loop again. */
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
};


window.leavingAnimation = function () {
      setTimeout(() => { document.getElementById("loader").classList.add("show-animation");  }, 100);
};

window.autocomplete = function(inp, arr) {
  /*the autocomplete function takes two arguments,
  the text field element and an array of possible autocompleted values:*/
  var currentFocus;
  /*execute a function when someone writes in the text field:*/
  inp.addEventListener("input", function(e) {
      var a, b, i, val = this.value;
      /*close any already open lists of autocompleted values*/
      closeAllLists();
      if (!val) { return false;}
      currentFocus = -1;
      /*create a DIV element that will contain the items (values):*/
      a = document.createElement("DIV");

      a.setAttribute("id", this.id + "autocomplete-list");
      a.setAttribute("class", "autocomplete-items");
      /*append the DIV element as a child of the autocomplete container:*/
      this.parentNode.appendChild(a);
      /*for each item in the array...*/
      for (i = 0; i < arr.length; i++) {
        /*check if the item starts with the same letters as the text field value:*/
        if (arr[i].name.toUpperCase().includes(val.toUpperCase())) {
          /*create a DIV element for each matching element:*/
          b = document.createElement("DIV");
          /*make the matching letters bold:*/
          b.innerHTML = arr[i].name;
          // b.innerHTML += arr[i].name.substr(val.length);
          /*insert a input field that will hold the current array item's value:*/
          let tmp = arr[i].regency_id;
          b.innerHTML += "<input name='location' type='hidden' value='" + arr[i].name + "'>";
          /*execute a function when someone clicks on the item value (DIV element):*/
              b.addEventListener("click", function(e) {
                getCountry(tmp);
              /*insert the value for the autocomplete text field:*/
              inp.value = this.getElementsByTagName("input")[0].value;
              /*close the list of autocompleted values,
              (or any other open lists of autocompleted values:*/
              closeAllLists();
          });
          a.appendChild(b);
        }
      }
  });
  /*execute a function presses a key on the keyboard:*/
  inp.addEventListener("keydown", function(e) {
      var x = document.getElementById(this.id + "autocomplete-list");
      if (x) x = x.getElementsByTagName("div");
      if (e.keyCode == 40) {
        /*If the arrow DOWN key is pressed,
        increase the currentFocus variable:*/
        currentFocus++;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 38) { //up
        /*If the arrow UP key is pressed,
        decrease the currentFocus variable:*/
        currentFocus--;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 13) {
        /*If the ENTER key is pressed, prevent the form from being submitted,*/
        e.preventDefault();
        if (currentFocus > -1) {
          /*and simulate a click on the "active" item:*/
          if (x) x[currentFocus].click();
        }
      }
  });
  function addActive(x) {
    /*a function to classify an item as "active":*/
    if (!x) return false;
    /*start by removing the "active" class on all items:*/
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (x.length - 1);
    /*add class "autocomplete-active":*/
    x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {
    /*a function to remove the "active" class from all autocomplete items:*/
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {
    /*close all autocomplete lists in the document,
    except the one passed as an argument:*/
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
      x[i].parentNode.removeChild(x[i]);
    }
  }
}
/*execute a function when someone clicks in the document:*/
document.addEventListener("click", function (e) {
    closeAllLists(e.target);
});
}