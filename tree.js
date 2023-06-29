// Importing the OrbitControls module from the specified URL
import { OrbitControls } from "https://threejsfundamentals.org/threejs/resources/threejs/r122/examples/jsm/controls/OrbitControls.js";

// Creating the scene and camera
const scene = new THREE.Scene();
const camera = new THREE.PerspectiveCamera(50, window.innerWidth / window.innerHeight, 0.1, 600);

// Creating the renderer and configuring shadow mapping
const renderer = new THREE.WebGLRenderer();
renderer.shadowMap.enabled = true;
renderer.setSize(window.innerWidth, window.innerHeight);

// Adding the renderer's DOM element to the document body
document.body.appendChild(renderer.domElement);

// Creating an instance of OrbitControls and passing the camera and renderer DOM element to it
const controlador = new OrbitControls(camera, renderer.domElement);

// Creating a texture loader
const loader = new THREE.TextureLoader();

// Configuring the initial camera position
camera.position.x = 40;
camera.position.y = 50;
camera.position.z = 200;

// Creating a sunlight (point light)
var angle = 0;
var radius = 290;
var light = new THREE.PointLight(new THREE.Color(0xf1f2d8), 1);
light.castShadow = true;
light.radious = 4;
light.shadow.mapSize.width = 2000;
light.shadow.mapSize.height = 2000;
light.intensity = 1.5;
light.position.set(circleX(radius, angle), circleY(radius, angle), 0);
scene.add(light);

// Creating a helper for the light
var aux = new THREE.PointLightHelper(light);
scene.add(aux);

// Creating an ambient light
var lightAmbient = new THREE.AmbientLight(new THREE.Color(0xFFFFFF), 1);
scene.add(lightAmbient);
lightAmbient.intensity = 0.9;

// Function to calculate the X coordinate on a circle given the radius and angle in degrees
function circleX(radius, angle) {
    return radius * Math.sin(Math.PI * 2 * angle / 360);
}

// Function to calculate the Y coordinate on a circle given the radius and angle in degrees
function circleY(radius, angle) {
    return radius * Math.cos(Math.PI * 2 * angle / 360);
}

// Function to move the light and sphere along the circular path
function moveLight(radius, angle) {
    light.position.set(circleX(radius, angle), circleY(radius, angle), 0);
    sphere.position.set(circleX(radius, angle), circleY(radius, angle), 0);
    sky.rotation.z = Math.PI - (Math.PI * angle) / 180;
}

// Creating a sun sphere
var geometry = new THREE.SphereGeometry(10, 55, 25);
var material = new THREE.MeshBasicMaterial({ color: 0xf4ff78 });
var sphere = new THREE.Mesh(geometry, material);
sphere.castShadow = false;
sphere.receiveShadow = false;
sphere.position.set(circleX(radius, angle), circleY(radius, angle), 0);
scene.add(sphere);




// Sky dome
var skyGeo = new THREE.SphereGeometry(300, 50, 50, 180);
var texture = loader.load(
    "https://raw.githubusercontent.com/filipe-jsales/cg-trab-02/master/sky_daynight.jpg"
);
    texture.repeat.set(1, 1);
    var material = new THREE.MeshBasicMaterial({ 
    map: texture,
    shading: THREE.FlatShading
});
var sky = new THREE.Mesh(skyGeo, material);
sky.material.side = THREE.BackSide;
sky.opacity = 0;
sky.castShadow = false;
sky.receiveShadow = false;
sky.rotation.z = 135;
scene.add(sky);

// Load and insertion of any 3d object in the scene
var gltfloader = new THREE.GLTFLoader();
gltfloader.crossOrigin = true;

function createObject(gltf_url, x, y, z, sx, sy, sz, castSh, receSh) {
    gltfloader.load(gltf_url, function (criar) {
        var object = criar.scene;
        object.position.set(x, y, z);
        object.scale.set(sz,sy,sz);
        object.traverse(function(node) {
            if (node instanceof THREE.Mesh) {
                node.castShadow = castSh;
                node.receiveShadow = receSh;
            }
        });
        scene.add(object);
    });
}

// Inserting trees belong the way
function Trees(xArv, zArv){
    var angles = [5, 10, 15, 20, 25, 30]
    var radiuses = [ 5, 10, 20, 30, 45, 60 ]
    var limitRadius = 60;

    for (let r = radiuses[1]; r <= limitRadius; r+=radiuses[Math.floor(Math.random() * radiuses.length)]) {
      for (let a = 0; a <= 360; a+=angles[Math.floor(Math.random() * angles.length)]) {
          if (Math.random() > 0.9) {
              var x = circleX(r, a) + xArv;
              var z = circleY(r, a) + zArv;
              createObject(
                  "https://raw.githubusercontent.com/filipe-jsales/cg-trab-02/master/laurel_tree_-_low_poly/scene.gltf",
                  x, -1, z,
                  .03, .03, .03,
                  true, true
              );
          }
      }
    }
}



Trees(0, 0);
Trees(0, 120);
Trees(-90, 200);
Trees(0, 230);
Trees(90, 210);

Trees(-50, -110);
Trees(0, -200);
Trees(-120, -200);
Trees(-180, -140);


function Restaurant(xArv, zArv) {
    var x = xArv;
    var z = zArv;
    
    createObject(
      "https://raw.githubusercontent.com/filipe-jsales/cg-trab-02/master/low_poly_ristorante/scene.gltf",
      x, -1, z,
      .03, .03, .03,
      true, true
    );
  }

Restaurant(0, -110);

// Inserting bushes along the way
function Bushes(xArv, zArv){
    var angles = [5, 10, 15, 20, 25, 30]
    var radiuses = [ 5, 10, 20, 30, 45, 60 ]
    var limitRadius = 60;

    for (let r = radiuses[1]; r <= limitRadius; r+=radiuses[Math.floor(Math.random() * radiuses.length)]) {
      for (let a = 0; a <= 360; a+=angles[Math.floor(Math.random() * angles.length)]) {
          if (Math.random() > 0.9) {
              var x = circleX(r, a) + xArv;
              var z = circleY(r, a) + zArv;
              createObject(
                  "https://raw.githubusercontent.com/filipe-jsales/cg-trab-02/master/bush/scene.gltf",
                  x, -1, z,
                  5, 5, 5,
                  true, true
              );
          }
      }
    }
}

Bushes(0, 0);
Bushes(0, 120);
Bushes(-90, 200);
Bushes(0, 230);
Bushes(90, 210);

Bushes(-50, -110);
Bushes(0, -200);
Bushes(-120, -200);
Bushes(-180, -140);


// Creating a simple lake using primitives
var lakeX = -200;
var lakeZ = 30;
var lakeRadius = 100;
var lakeGeometry = new THREE.SphereBufferGeometry(lakeRadius, 12, 6, 0, 2*Math.PI, 0, 0.5*Math.PI);
var lakeTopGeometry = new THREE.CircleGeometry(lakeRadius, 12, 6);

var lakeTexture = loader.load("https://raw.githubusercontent.com/filipe-jsales/cg-trab-02/master/water-texture.jpg");
	lakeTexture.wrapS = lakeTexture.wrapT = THREE.RepeatWrapping; 
	lakeTexture.repeat.set(30, 30);
	var lakeMaterial = new THREE.MeshPhongMaterial( { map: lakeTexture, side: THREE.BackSide } );
lakeMaterial.side = THREE.DoubleSide;

var lago_sphere = new THREE.Mesh(lakeGeometry, lakeMaterial);
lago_sphere.rotation.z = Math.PI;
lago_sphere.position.set(0, 0.5, 0);

var lakeTop = new THREE.Mesh(lakeTopGeometry, lakeMaterial);
lakeTop.rotation.x = Math.PI/2;
lakeTop.rotation.z = 50;
lakeTop.position.set(0, 0.3, 0);
lakeTop.receiveShadow = true;

var lake = new THREE.Group();
    lake.add(lago_sphere);
    lake.add(lakeTop);

lake.position.set(lakeX, 0, lakeZ);

scene.add(lake);


// Creating pyramid using primitives
function CreatePyramid(radius, altura, x, y){
    var texture = loader.load(
    "https://raw.githubusercontent.com/filipe-jsales/cg-trab-02/master/pyramid.jpg"
);
    var coneGeo = new THREE.ConeGeometry(radius, altura);
    var coneMaterial = new THREE.MeshStandardMaterial({map: texture, shading: THREE.FlatShading});

    var pyramid = new THREE.Mesh(coneGeo, coneMaterial);
    pyramid.position.set(x, altura/2, y);
    pyramid.castShadow = true;
    pyramid.receiveShadow = true;
    scene.add(pyramid);
}

CreatePyramid(100, 200, -20, 180);
CreatePyramid(120, 150, 170, 70);
CreatePyramid(110, 130, 140, -130);


CreatePyramid(110, 130, 140, -130);

// Creating a house using primitives
function createHouse(casaX, casaZ, rotação){
    
    var cubeSize = 24;
    var cubeWidth = 5;
    var cubeLength = 7;

    var cubeX = 0;
    var cubeY = cubeSize/2;
    var cubeZ = 0;

    // Walls
    var geometry = new THREE.BoxGeometry(cubeWidth, cubeSize, cubeLength);
    var material = new THREE.MeshLambertMaterial({map: loader.load('https://raw.githubusercontent.com/filipe-jsales/cg-trab-02/master/yellowwood.jpg')});
    var cube = new THREE.Mesh(geometry, material);
    cube.castShadow = true;
    cube.receiveShadow = true;
    cube.position.set(cubeX, cubeY, cubeZ);

    // ceiling
    geometry = new THREE.CylinderGeometry(4, 4, cubeLength*1.01, 3);
    var material = new THREE.MeshLambertMaterial({map: loader.load('https://raw.githubusercontent.com/filipe-jsales/cg-trab-02/master/lightwood.jpg')});
    var ceiling = new THREE.Mesh(geometry, material);
    ceiling.castShadow = true;
    ceiling.receiveShadow = true;

    ceiling.position.set(cubeX, cubeY+13, cubeZ);
    ceiling.rotation.set(-Math.PI/2, 0, 0);
    ceiling.scale.set(1, 1, 0.5);

    // door
    geometry = new THREE.BoxGeometry(4, 4, 0.2);
    var material = new THREE.MeshLambertMaterial({map: loader.load('https://raw.githubusercontent.com/filipe-jsales/cg-trab-02/master/door-white-texture.jpg')});
    var door = new THREE.Mesh(geometry, material);
    door.castShadow = true;
    door.receiveShadow = true;

    door.position.set(cubeX, cubeY - (cubeSize*0.4), cubeZ+cubeLength/2);

    // window
    geometry = new THREE.BoxGeometry(0.1, cubeSize*0.5, 2.5);
    var materialWindow = new THREE.MeshLambertMaterial({map: loader.load('https://raw.githubusercontent.com/filipe-jsales/cg-trab-02/master/ywindow.jpg')});
    var window = new THREE.Mesh(geometry, materialWindow);
    window.castShadow = true;
    window.receiveShadow = true;

    window.position.set(cubeX+cubeWidth/2, cubeY, cubeZ);
    window.rotation.z = Math.PI * 2;


    // window2
    geometry = new THREE.BoxGeometry(0.1, cubeSize*0.5, 2.5);
    var window2 = new THREE.Mesh(geometry, materialWindow);
    window2.castShadow = true;
    window2.receiveShadow = true;

    window2.position.set(cubeX-cubeWidth/2, cubeY, cubeZ);
    window2.rotation.z = Math.PI * 2;

    var house = new THREE.Group();
    house.add(cube);
    house.add(window);
    house.add(door);
    house.add(ceiling);
    house.add(window2);

    house.scale.set(2, 2, 2);
    house.position.set(casaX, -0.8, casaZ);
    house.rotation.y= rotação;
    scene.add(house);
}
var xCasas = lake.position.x;
var zCasas = lake.position.z;
var raioCasa = lakeRadius+30;
for (let i = 30; i < 150; i+=20) {
    createHouse(circleX(raioCasa, i)+xCasas, circleY(raioCasa, i)+zCasas, (180 + i)*Math.PI/180);
}

// Creating a bridge using primitives
function bridge(ponteX, ponteZ){
    
    var geometry = new THREE.BoxGeometry( 3, 0.2, 5);
    var material = new THREE.MeshBasicMaterial( {map: loader.load('https://raw.githubusercontent.com/filipe-jsales/cg-trab-02/master/lightwood.jpg')});
    var wood = new THREE.Mesh( geometry, material );
    wood.position.set(0, 1, 0);
    scene.add( wood );

    var geometry = new THREE.CylinderGeometry( 0.3, 0.3, 4, 14);
    var material = new THREE.MeshBasicMaterial( {map: loader.load('https://media.istockphoto.com/photos/closeup-of-brown-tree-bark-texture-picture-id466135044?b=1&k=20&m=466135044&s=170667a&w=0&h=nz3u8epwayYVgpmOB1CBJ0Bx782_jCYrFf0RnUNcnD4=')});
    var stem = new THREE.Mesh( geometry, material );
    stem.position.set(1.5, 0, 0);
    scene.add( stem );

    var tronco2 = stem.clone();
    tronco2.position.set(-1.5, 0, 0);
    scene.add( tronco2 );

    var bridge = new THREE.Group();
    bridge.add(wood);
    bridge.add(stem);
    bridge.add(tronco2);

    bridge.position.set(ponteX, 0, ponteZ);
    bridge.rotation.y = Math.PI/2;
    
    return bridge;
}

var fullBridge = new THREE.Group();
for(let i=0; i<= 10; i++){
    fullBridge.add(bridge(-90-(i*5), 50));
}

fullBridge.rotation.y = -4*Math.PI/180;
scene.add(fullBridge);

// Floor
function createFloor() {
    var floorTexture = loader.load("https://raw.githubusercontent.com/filipe-jsales/cg-trab-02/master/sand1.jpg");
	floorTexture.wrapS = floorTexture.wrapT = THREE.RepeatWrapping; 
	floorTexture.repeat.set(50, 50);
	var floorMaterial = new THREE.MeshLambertMaterial( { map: floorTexture, side: THREE.BackSide } );
	var floorGeometry = new THREE.CircleGeometry(300, 50);
	var floor = new THREE.Mesh(floorGeometry, floorMaterial);
	floor.position.y = -0.5;
	floor.rotation.x = Math.PI / 2;
    floor.receiveShadow = true;
    floor.castShadow = true;
	scene.add(floor);
    
    var ground_geometry = new THREE.SphereBufferGeometry(300, 50, 6, 0, 2*Math.PI, 0, 0.5*Math.PI);
    var ground_material = new THREE.MeshBasicMaterial({color: 0x70483c});
    var ground = new THREE.Mesh(ground_geometry, ground_material);
    ground.rotation.z = Math.PI;
    scene.add(ground);
}

createFloor();

//Animation
function animacao(){
    requestAnimationFrame(animacao);
    
    angle += 0.1;
    moveLight(radius, angle);
    
    renderer.render(scene, camera);
}

animacao();