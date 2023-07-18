@extends('layouts.master')
@section('content')
@section('head')
    @livewireStyles
@endsection
@include('layouts.loader')

<main id="main-container">
    <section id="home" class="menu-navigation-content min-h-screen w-100 overflow-x-hidden flex flex-col flex-grow">
        <div class="text-center my-auto" style="z-index:99999;">
            <img src="{{ asset('img/BNPB.png') }}" class="h-24 mx-auto my-2">
            <img src="{{ asset('img/logo-dark.png') }}" class="h-20 mx-auto my-2">
            <hr class="my-5 w-1/5 mx-auto">
            @if ($errors->has('email'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif            
            <form class="md:w-1/5 mx-auto border shadow border-zinc-900 bg-white px-8 pt-6 pb-8 mb-4" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-4">
                    <label class="uppercase block text-sm font-bold mb-2" for="username">
                        Username
                    </label>
                    <input class="text-center bg-transparent appearance-none rounded w-full py-2 px-3 border border-zinc-900 leading-tight focus:outline-none focus:shadow-outline" id="username" type="text" placeholder="Username" name="username">
                </div>
                <div class="mb-2">
                    <label class="uppercase block text-sm font-bold mb-2" for="password">
                        Password
                    </label>
                    <input class="text-center bg-transparent appearance-none rounded w-full py-2 px-3 border border-zinc-900 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="password" type="password" name="password" placeholder="******************">
                </div>
                <div class="flex">
                <button class="mx-auto bg-orange-500 text-white hover:bg-orange-700 font-bold py-2 px-4 focus:outline-none focus:shadow-outline" type="submit">
                    Sign In
                </button>
                </div>
            </form>
        </div>
    </section>    

</main>
@endsection

@section('js')
@livewireScripts
<script src='https://jeromeetienne.github.io/threex.terrain/examples/vendor/three.js/build/three-min.js'></script>
<script src='https://jeromeetienne.github.io/threex.terrain/examples/vendor/three.js/examples/js/SimplexNoise.js'></script>
<script src='https://jeromeetienne.github.io/threex.terrain/threex.terrain.js'></script>
<script type="text/javascript">
    var renderer    = new THREE.WebGLRenderer({
            antialias   : true
        });
    /* Fullscreen */
        renderer.setSize( window.innerWidth, window.innerHeight );
    /* Append to HTML */
        document.getElementById("home").appendChild( renderer.domElement );
        renderer.domElement.style.position = "fixed";
        renderer.domElement.style.opacity = 0.9;
        var onRenderFcts= [];
        var scene   = new THREE.Scene();
        var camera  = new THREE.PerspectiveCamera(25, window.innerWidth /    window.innerHeight, 0.01, 1000);
    /* Play around with camera positioning */
        camera.position.z = 15; 
      camera.position.y = 2;
    /* Fog provides depth to the landscape*/
      scene.fog = new THREE.Fog(0x000, 0, 45);
        ;(function(){
            var light   = new THREE.AmbientLight( 0x202020 )
            scene.add( light )
            var light   = new THREE.DirectionalLight('white', 5)
            light.position.set(0.5, 0.0, 2)
            scene.add( light )
            var light   = new THREE.DirectionalLight('white', 0.75*2)
            light.position.set(-0.5, -0.5, -2)
            scene.add( light )      
        })()
        var heightMap   = THREEx.Terrain.allocateHeightMap(256,256)
        THREEx.Terrain.simplexHeightMap(heightMap)  
        var geometry    = THREEx.Terrain.heightMapToPlaneGeometry(heightMap)
        THREEx.Terrain.heightMapToVertexColor(heightMap, geometry)
    /* Wireframe built-in color is white, no need to change that */
        var material    = new THREE.MeshBasicMaterial({
            wireframe: true
        });
        var mesh    = new THREE.Mesh( geometry, material );
        scene.add( mesh );
        mesh.lookAt(new THREE.Vector3(0,1,0));
    /* Play around with the scaling */
        mesh.scale.y    = 3.5;
        mesh.scale.x    = 3;
        mesh.scale.z    = 0.20;
        mesh.scale.multiplyScalar(10);
    /* Play around with the camera */
        onRenderFcts.push(function(delta, now){
            mesh.rotation.z += 0.05 * delta; 
        })
        onRenderFcts.push(function(){
            renderer.render( scene, camera );       
        })
        var lastTimeMsec= null
        requestAnimationFrame(function animate(nowMsec){
            requestAnimationFrame( animate );
            lastTimeMsec    = lastTimeMsec || nowMsec-1000/60
            var deltaMsec   = Math.min(200, nowMsec - lastTimeMsec)
            lastTimeMsec    = nowMsec
            onRenderFcts.forEach(function(onRenderFct){
                onRenderFct(deltaMsec/1000, nowMsec/1000)
            })
        })    
</script>
@endsection