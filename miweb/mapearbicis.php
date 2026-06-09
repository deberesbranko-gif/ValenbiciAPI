<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Estaciones Valenbisi</title>
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            text-align: center;
            background-color: #f9f9f9;
        }
        h1 {
            color: #2c3e50;
            font-size: 24px;
            margin-top: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        #map {
            height: 600px;
            width: 90%;
            margin: 20px auto;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border: 1px solid #ddd;
        }
        .btn-volver {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #34495e;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 4px;
            transition: background-color 0.2s;
        }
        .btn-volver:hover {
            background-color: #2c3e50;
        }
        /* Estilos de botones de idioma */
        .lang-container {
            text-align: center;
            margin-top: 15px;
            margin-bottom: 5px;
        }
        .btn-lang {
            background-color: #ffffff;
            border: 2px solid #34495e;
            color: #34495e;
            padding: 8px 16px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 4px;
            margin: 0 5px;
            transition: all 0.2s;
        }
        .btn-lang:hover {
            background-color: #34495e;
            color: white;
        }
    </style>
</head>
<body>

<div class="lang-container">
    <button class="btn-lang" onclick="setLang('es')">Español</button>
    <button class="btn-lang" onclick="setLang('en')">English</button>
</div>

<h1 id="main-title">Mapeo de Bicicletas en Valencia</h1>

<div id="map"></div>

<div style="margin-top: 15px;">
    <a href="index.php" id="btn-back" class="btn-volver">Volver al Listado</a>
</div>

<script>
// 1. Inicializa el mapa centrado en Valencia
var map = L.map('map').setView([39.47, -0.37], 13); 

// Variables globales para persistir datos e idioma actual
var estacionesData = [];
var currentLang = 'es';
var markersGroup = L.layerGroup().addTo(map); // Grupo de capas para limpiar marcadores fácilmente

// 2. Añade la capa base de OpenStreetMap
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

/**
 * Función para determinar el color del marcador según las bicis disponibles 
 */
function getMarkerColor(available) { 
    if (available === 0) { 
        return '#e74c3c'; 
    } else if (available > 0 && available < 10) { 
        return '#e67e22'; 
    } else if (available >= 10 && available < 20) { 
        return '#f1c40f'; 
    } else { 
        return '#2ecc71'; 
    } 
} 

/**
 * Renderiza los marcadores en el mapa basándose en el idioma actual
 */
function renderMarkers() {
    // Limpiamos los marcadores previos para que no se dupliquen al cambiar idioma
    markersGroup.clearLayers();

    // Diccionario de los textos de los globos informativos (Popups)
    const labelData = {
        'es': { available: "Disponibles", free: "Libres", total: "Total" },
        'en': { available: "Available", free: "Free", total: "Total" }
    };

    estacionesData.forEach(station => {
        const { lat, lon, address, available, free, total } = station; 
        
        if (lat && lon) { 
            var colorEstacion = getMarkerColor(available);

            L.circleMarker([lat, lon], {
                color: colorEstacion,       
                fillColor: colorEstacion,   
                fill: true,                 
                radius: 8, 
                fillOpacity: 0.7,
                weight: 2                   
            }) 
            .bindPopup(` 
                <strong>${address}</strong><br> 
                <b>${labelData[currentLang].available}:</b> ${available}<br> 
                <b>${labelData[currentLang].free}:</b> ${free}<br> 
                <b>${labelData[currentLang].total}:</b> ${total} 
            `)
            .addTo(markersGroup); 
        } 
    });
}

/**
 * Cambia el idioma global de la UI y los Popups del Mapa
 */
function setLang(lang) {
    currentLang = lang;

    const uiTranslations = {
        'es': { title: "Mapeo de Bicicletas en Valencia", backBtn: "Volver al Listado" },
        'en': { title: "Valencia Bike Mapping", backBtn: "Back to List" }
    };

    // Traducir interfaz fija
    document.getElementById('main-title').innerText = uiTranslations[lang].title;
    document.getElementById('btn-back').innerText = uiTranslations[lang].backBtn;

    // Redibujar los marcadores de mapa en el nuevo idioma
    if (estacionesData.length > 0) {
        renderMarkers();
    }
}

// 3. Cargar el archivo data.json una sola vez al cargar la web
fetch('data.json') 
    .then(response => { 
        if (!response.ok) { 
            throw new Error(`Error al cargar data.json: ${response.statusText}`); 
        } 
        return response.json(); 
    }) 
    .then(data => { 
        // Convertimos el objeto en array y lo guardamos globalmente
        estacionesData = Object.values(data);
        // Hacemos el primer renderizado en español (por defecto)
        renderMarkers();
    }) 
    .catch(error => { 
        console.error('Error cargando los datos:', error); 
    }); 
</script>

</body>
</html>