import { OrbitControls } from "https://threejsfundamentals.org/threejs/resources/threejs/r122/examples/jsm/controls/OrbitControls.js";

// CRIAÇÃO DA CENA E CAMERA
const scene = new THREE.Scene();
const camera = new THREE.PerspectiveCamera(
  50,
  window.innerWidth / window.innerHeight,
  0.1,
  600
);

const renderer = new THREE.WebGLRenderer();
renderer.shadowMap.enabled = true;
renderer.setSize(window.innerWidth, window.innerHeight);

document.body.appendChild(renderer.domElement);

const controlador = new OrbitControls(camera, renderer.domElement);
const loader = new THREE.TextureLoader();

camera.position.x = 40;
camera.position.y = 50;
camera.position.z = 200;

//LUZ DO SOL
var angulo = 0;
var raio = 290;
var light = new THREE.PointLight(new THREE.Color(0xf1f2d8), 1);
light.castShadow = true;
light.radious = 4;
light.shadow.mapSize.width = 2000;
light.shadow.mapSize.height = 2000;
light.intensity = 1.5;
light.position.set(circleX(raio, angulo), circleY(raio, angulo), 0);
scene.add(light);

var ajudante = new THREE.PointLightHelper(light);
scene.add(ajudante);

var lightAmbient = new THREE.AmbientLight(new THREE.Color(0xffffff), 1);
scene.add(lightAmbient);
lightAmbient.intensity = 0.9;

// RASTERIZACAO DE CINCUNFERENCIAS
function circleX(radius, angle) {
  return radius * Math.sin((Math.PI * 2 * angle) / 360);
}
function circleY(radius, angle) {
  return radius * Math.cos((Math.PI * 2 * angle) / 360);
}

// MOVIMENTACAO DA LUZ
function moveLuz(radius, angle) {
  light.position.set(circleX(radius, angle), circleY(radius, angle), 0);
  esfera.position.set(circleX(radius, angle), circleY(radius, angle), 0);

  sky.rotation.z = Math.PI - (Math.PI * angle) / 180;
}

//ESFERA DO SOL
var geometria = new THREE.SphereGeometry(10, 55, 25);
var material = new THREE.MeshBasicMaterial({ color: 0xf4ff78 });
var esfera = new THREE.Mesh(geometria, material);
esfera.castShadow = false;
esfera.receiveShadow = false;
esfera.position.set(circleX(raio, angulo), circleY(raio, angulo), 0);
scene.add(esfera);

// SKY DOME
var skyGeo = new THREE.SphereGeometry(300, 50, 50, 180);
var texture = loader.load(
  "https://raw.githubusercontent.com/filipe-jsales/cg-trab-02/master/sky_daynight.jpg"
);
texture.repeat.set(1, 1);
var material = new THREE.MeshBasicMaterial({
  map: texture,
  shading: THREE.FlatShading,
});
var sky = new THREE.Mesh(skyGeo, material);
sky.material.side = THREE.BackSide;
sky.opacity = 0;
sky.castShadow = false;
sky.receiveShadow = false;
sky.rotation.z = 135;
scene.add(sky);

// CARREGAMENTO E INSERÇÃO DE UM OBJETO 3D QUALQUER
var gltfloader = new THREE.GLTFLoader();
gltfloader.crossOrigin = true;

function criarObjeto(gltf_url, x, y, z, sx, sy, sz, castSh, receSh) {
  gltfloader.load(gltf_url, function (criar) {
    var objeto = criar.scene;
    objeto.position.set(x, y, z);
    objeto.scale.set(sz, sy, sz);
    objeto.traverse(function (node) {
      if (node instanceof THREE.Mesh) {
        node.castShadow = castSh;
        node.receiveShadow = receSh;
      }
    });
    scene.add(objeto);
  });
}

// INSERINDO ARVORES AO LONGO DO CENÁRIO
function Arvores(xArv, zArv) {
  var angulos = [5, 10, 15, 20, 25, 30];
  var raios = [5, 10, 20, 30, 45, 60];
  var raioLimite = 60;

  for (
    let r = raios[1];
    r <= raioLimite;
    r += raios[Math.floor(Math.random() * raios.length)]
  ) {
    for (
      let a = 0;
      a <= 360;
      a += angulos[Math.floor(Math.random() * angulos.length)]
    ) {
      if (Math.random() > 0.9) {
        var x = circleX(r, a) + xArv;
        var z = circleY(r, a) + zArv;
        criarObjeto(
          "https://raw.githubusercontent.com/filipe-jsales/cg-trab-02/master/laurel_tree_-_low_poly/scene.gltf",
          x,
          -1,
          z,
          0.03,
          0.03,
          0.03,
          true,
          true
        );
      }
    }
  }
}

Arvores(0, 0);
Arvores(0, 120);
Arvores(-90, 200);
Arvores(0, 230);
Arvores(90, 210);

Arvores(-50, -110);
Arvores(0, -200);
Arvores(-120, -200);
Arvores(-180, -140);

function Restaurante(xArv, zArv) {
  var x = xArv;
  var z = zArv;

  criarObjeto(
    "https://raw.githubusercontent.com/filipe-jsales/cg-trab-02/master/low_poly_ristorante/scene.gltf",
    x,
    -1,
    z,
    0.03,
    0.03,
    0.03,
    true,
    true
  );
}

Restaurante(0, -110);

// INSERINDO ARBUSTOS AO LONGO DO CENÁRIO
function Arbustos(xArv, zArv) {
  var angulos = [5, 10, 15, 20, 25, 30];
  var raios = [5, 10, 20, 30, 45, 60];
  var raioLimite = 60;

  for (
    let r = raios[1];
    r <= raioLimite;
    r += raios[Math.floor(Math.random() * raios.length)]
  ) {
    for (
      let a = 0;
      a <= 360;
      a += angulos[Math.floor(Math.random() * angulos.length)]
    ) {
      if (Math.random() > 0.9) {
        var x = circleX(r, a) + xArv;
        var z = circleY(r, a) + zArv;
        criarObjeto(
          "https://raw.githubusercontent.com/filipe-jsales/cg-trab-02/master/bush/scene.gltf",
          x,
          -1,
          z,
          5,
          5,
          5,
          true,
          true
        );
      }
    }
  }
}

Arbustos(0, 0);
Arbustos(0, 120);
Arbustos(-90, 200);
Arbustos(0, 230);
Arbustos(90, 210);

Arbustos(-50, -110);
Arbustos(0, -200);
Arbustos(-120, -200);
Arbustos(-180, -140);

// CRIANDO UM LAGO COM PRIMIVAS
var lagoX = -200;
var lagoZ = 30;
var lagoraio = 100;
var lago_geometry = new THREE.SphereBufferGeometry(
  lagoraio,
  12,
  6,
  0,
  2 * Math.PI,
  0,
  0.5 * Math.PI
);
var lago_topo_geometry = new THREE.CircleGeometry(lagoraio, 12, 6);

var lagoTexture = loader.load(
  "https://raw.githubusercontent.com/filipe-jsales/cg-trab-02/master/water-texture.jpg"
);
lagoTexture.wrapS = lagoTexture.wrapT = THREE.RepeatWrapping;
lagoTexture.repeat.set(30, 30);
var lago_material = new THREE.MeshPhongMaterial({
  map: lagoTexture,
  side: THREE.BackSide,
});
lago_material.side = THREE.DoubleSide;

var lago_sphere = new THREE.Mesh(lago_geometry, lago_material);
lago_sphere.rotation.z = Math.PI;
lago_sphere.position.set(0, 0.5, 0);

var lago_topo = new THREE.Mesh(lago_topo_geometry, lago_material);
lago_topo.rotation.x = Math.PI / 2;
lago_topo.rotation.z = 50;
lago_topo.position.set(0, 0.3, 0);
lago_topo.receiveShadow = true;

var lago = new THREE.Group();
lago.add(lago_sphere);
lago.add(lago_topo);

lago.position.set(lagoX, 0, lagoZ);

scene.add(lago);

// CRIANDO UMA PIRAMIDE COM PRIMIVAS
function CriarPiramide(raio, altura, x, y) {
  var texture = loader.load(
    "https://raw.githubusercontent.com/filipe-jsales/cg-trab-02/master/pyramid.jpg"
  );
  var coneGeo = new THREE.ConeGeometry(raio, altura);
  var coneMaterial = new THREE.MeshStandardMaterial({
    map: texture,
    shading: THREE.FlatShading,
  });

  var piramide = new THREE.Mesh(coneGeo, coneMaterial);
  piramide.position.set(x, altura / 2, y);
  piramide.castShadow = true;
  piramide.receiveShadow = true;
  scene.add(piramide);
}

CriarPiramide(100, 200, -20, 180);
CriarPiramide(120, 150, 170, 70);
CriarPiramide(110, 130, 140, -130);

CriarPiramide(110, 130, 140, -130);

// CRIANDO UMA CASA COM PRIMIVAS
function CriarCasa(casaX, casaZ, rotação) {
  var tamanhocubo = 24;
  var larguracubo = 5;
  var comprimentocubo = 7;

  var cubox = 0;
  var cuboy = tamanhocubo / 2;
  var cuboz = 0;

  // Paredes
  var geometria = new THREE.BoxGeometry(
    larguracubo,
    tamanhocubo,
    comprimentocubo
  );
  var material = new THREE.MeshLambertMaterial({
    map: loader.load(
      "https://raw.githubusercontent.com/filipe-jsales/cg-trab-02/master/yellowwood.jpg"
    ),
  });
  var cubo = new THREE.Mesh(geometria, material);
  cubo.castShadow = true;
  cubo.receiveShadow = true;
  cubo.position.set(cubox, cuboy, cuboz);

  // Teto
  geometria = new THREE.CylinderGeometry(4, 4, comprimentocubo * 1.01, 3);
  var material = new THREE.MeshLambertMaterial({
    map: loader.load(
      "https://raw.githubusercontent.com/filipe-jsales/cg-trab-02/master/lightwood.jpg"
    ),
  });
  var teto = new THREE.Mesh(geometria, material);
  teto.castShadow = true;
  teto.receiveShadow = true;

  teto.position.set(cubox, cuboy + 13, cuboz);
  teto.rotation.set(-Math.PI / 2, 0, 0);
  teto.scale.set(1, 1, 0.5);

  // Porta
  geometria = new THREE.BoxGeometry(4, 4, 0.2);
  var material = new THREE.MeshLambertMaterial({
    map: loader.load(
      "https://raw.githubusercontent.com/filipe-jsales/cg-trab-02/master/door-white-texture.jpg"
    ),
  });
  var porta = new THREE.Mesh(geometria, material);
  porta.castShadow = true;
  porta.receiveShadow = true;

  porta.position.set(
    cubox,
    cuboy - tamanhocubo * 0.4,
    cuboz + comprimentocubo / 2
  );

  // Janela
  geometria = new THREE.BoxGeometry(0.1, tamanhocubo * 0.5, 2.5);
  var materialjanela = new THREE.MeshLambertMaterial({
    map: loader.load(
      "https://raw.githubusercontent.com/filipe-jsales/cg-trab-02/master/ywindow.jpg"
    ),
  });
  var janela = new THREE.Mesh(geometria, materialjanela);
  janela.castShadow = true;
  janela.receiveShadow = true;

  janela.position.set(cubox + larguracubo / 2, cuboy, cuboz);
  janela.rotation.z = Math.PI * 2;

  // Janela2
  geometria = new THREE.BoxGeometry(0.1, tamanhocubo * 0.5, 2.5);
  var janela2 = new THREE.Mesh(geometria, materialjanela);
  janela2.castShadow = true;
  janela2.receiveShadow = true;

  janela2.position.set(cubox - larguracubo / 2, cuboy, cuboz);
  janela2.rotation.z = Math.PI * 2;

  var casa = new THREE.Group();
  casa.add(cubo);
  casa.add(janela);
  casa.add(porta);
  casa.add(teto);
  casa.add(janela2);

  casa.scale.set(2, 2, 2);
  casa.position.set(casaX, -0.8, casaZ);
  casa.rotation.y = rotação;
  scene.add(casa);
}
var xCasas = lago.position.x;
var zCasas = lago.position.z;
var raioCasa = lagoraio + 30;
for (let i = 30; i < 150; i += 20) {
  CriarCasa(
    circleX(raioCasa, i) + xCasas,
    circleY(raioCasa, i) + zCasas,
    ((180 + i) * Math.PI) / 180
  );
}

// CRIANDO UMA PONTES COM PRIMIVAS
function Ponte(ponteX, ponteZ) {
  var geometry = new THREE.BoxGeometry(3, 0.2, 5);
  var material = new THREE.MeshBasicMaterial({
    map: loader.load(
      "https://raw.githubusercontent.com/filipe-jsales/cg-trab-02/master/lightwood.jpg"
    ),
  });
  var tabua = new THREE.Mesh(geometry, material);
  tabua.position.set(0, 1, 0);
  scene.add(tabua);

  var geometry = new THREE.CylinderGeometry(0.3, 0.3, 4, 14);
  var material = new THREE.MeshBasicMaterial({
    map: loader.load(
      "https://media.istockphoto.com/photos/closeup-of-brown-tree-bark-texture-picture-id466135044?b=1&k=20&m=466135044&s=170667a&w=0&h=nz3u8epwayYVgpmOB1CBJ0Bx782_jCYrFf0RnUNcnD4="
    ),
  });
  var tronco = new THREE.Mesh(geometry, material);
  tronco.position.set(1.5, 0, 0);
  scene.add(tronco);

  var tronco2 = tronco.clone();
  tronco2.position.set(-1.5, 0, 0);
  scene.add(tronco2);

  var ponte = new THREE.Group();
  ponte.add(tabua);
  ponte.add(tronco);
  ponte.add(tronco2);

  //altura da ponte
  ponte.position.set(ponteX, 0, ponteZ);
  ponte.rotation.y = Math.PI / 2;

  return ponte;
}

var PonteCompleta = new THREE.Group();
for (let i = 0; i <= 10; i++) {
  PonteCompleta.add(Ponte(-90 - i * 5, 50));
}

PonteCompleta.rotation.y = (-4 * Math.PI) / 180;
scene.add(PonteCompleta);

// PISO
function CriarPiso() {
  var floorTexture = loader.load(
    "https://raw.githubusercontent.com/filipe-jsales/cg-trab-02/master/sand1.jpg"
  );
  floorTexture.wrapS = floorTexture.wrapT = THREE.RepeatWrapping;
  floorTexture.repeat.set(50, 50);
  var floorMaterial = new THREE.MeshLambertMaterial({
    map: floorTexture,
    side: THREE.BackSide,
  });
  var floorGeometry = new THREE.CircleGeometry(300, 50);
  var floor = new THREE.Mesh(floorGeometry, floorMaterial);
  floor.position.y = -0.5;
  floor.rotation.x = Math.PI / 2;
  floor.receiveShadow = true;
  floor.castShadow = true;
  scene.add(floor);

  var ground_geometry = new THREE.SphereBufferGeometry(
    300,
    50,
    6,
    0,
    2 * Math.PI,
    0,
    0.5 * Math.PI
  );
  var ground_material = new THREE.MeshBasicMaterial({ color: 0x70483c });
  var ground = new THREE.Mesh(ground_geometry, ground_material);
  ground.rotation.z = Math.PI;
  scene.add(ground);
}

CriarPiso();

//ANIMACAO
function animacao() {
  requestAnimationFrame(animacao);

  angulo += 0.1;
  moveLuz(raio, angulo);

  renderer.render(scene, camera);
}

animacao();
