if ( ! Detector.webgl ) Detector.addGetWebGLMessage();

var container, stats;

var camera, cameraTarget, scene, renderer;

animate();

function init_render(name) {
    console.log(name);

    container = document.createElement( 'div' );
    container.setAttribute('id', '3D');
    document.body.appendChild( container );

    camera = new THREE.PerspectiveCamera( 20, window.innerWidth / window.innerHeight, 1, 15 );
    camera.position.set( 3, 0, 3 );

    cameraTarget = new THREE.Vector3( 0, 0, 0 );

    scene = new THREE.Scene();
    scene.fog = new THREE.Fog( 0x73777A, 2, 15 );


    // ASCII file

    var loader = new THREE.STLLoader();
    loader.load( './image/stl/'+ name +'.stl', function ( geometry ) {

        var material = new THREE.MeshPhongMaterial( { color: 0xDED5C9, specular: 0xDED5C9, shininess: 200 } );
        var mesh = new THREE.Mesh( geometry, material );

        mesh.position.set( 0, 0, 0 );
        mesh.scale.set( 1.5, 1.5, 1.5 );

        mesh.castShadow = true;
        mesh.receiveShadow = true;

        scene.add( mesh );

    } );


    // Lights

    scene.add( new THREE.HemisphereLight( 0x443333, 0x111122 ) );

    addShadowedLight( 0.5, 1, -1, 0xffff99, 1 );

    // renderer

    renderer = new THREE.WebGLRenderer( { antialias: true } );
    renderer.setClearColor( scene.fog.color );
    renderer.setPixelRatio( window.devicePixelRatio );
    renderer.setSize( window.innerWidth/2, window.innerHeight/2 );

    renderer.gammaInput = true;
    renderer.gammaOutput = true;

    renderer.shadowMap.enabled = true;
    renderer.shadowMap.cullFace = THREE.CullFaceBack;

    container.appendChild( renderer.domElement );
}

function addShadowedLight( x, y, z, color, intensity ) {

    var directionalLight = new THREE.DirectionalLight( color, intensity );
    directionalLight.position.set( x, y, z );
    scene.add( directionalLight );

    directionalLight.castShadow = true;
    // directionalLight.shadowCameraVisible = true;

    var d = 1;
    directionalLight.shadowCameraLeft = -d;
    directionalLight.shadowCameraRight = d;
    directionalLight.shadowCameraTop = d;
    directionalLight.shadowCameraBottom = -d;

    directionalLight.shadowCameraNear = 1;
    directionalLight.shadowCameraFar = 4;

    directionalLight.shadowMapWidth = 1024;
    directionalLight.shadowMapHeight = 1024;

    directionalLight.shadowBias = -0.005;

}

function animate() {

    requestAnimationFrame( animate );

    render();

}

function render() {

    var timer = Date.now() * 0.0005;

    camera.position.x = Math.cos( timer ) * 3;
    camera.position.z = Math.sin( timer ) * 3;

    camera.lookAt( cameraTarget );

    renderer.render( scene, camera );

}
