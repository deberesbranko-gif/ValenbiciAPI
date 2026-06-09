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
    </style>
</head>
<body>

<h1>Mapeo de Bicicletas en Valencia</h1>

<div id="map"></div>

<div style="margin-top: 15px;">
    <a href="index.php" class="btn-volver">Volver al Listado</a>
</div>

<script>
// 1. Inicializa el mapa centrado en Valencia
var map = L.map('map').setView([39.47, -0.37], 13); 

// 2. Añade la capa base de OpenStreetMap
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

/**
 * Función para determinar el color del marcador según las bicis disponibles 
 */
function getMarkerColor(available) { 
    if (available === 0) { 
        return '#e74c3c'; // Rojo plano moderno
    } else if (available > 0 && available < 10) { 
        return '#e67e22'; // Naranja
    } else if (available >= 10 && available < 20) { 
        return '#f1c40f'; // Amarillo
    } else { 
        return '#2ecc71'; // Verde
    } 
} 

// 3. Cargar el archivo data.json
fetch('data.json') 
    .then(response => { 
        if (!response.ok) { 
            throw new Error(`Error al cargar data.json: ${response.statusText}`); 
        } 
        return response.json(); 
    }) 
    .then(data => { 
        Object.values(data).forEach(station => {
            const { lat, lon, address, available, free, total } = station; 
            
            if (lat && lon) { 
                // CORRECCIÓN: Asignamos color tanto al borde como al relleno interno
                var colorEstacion = getMarkerColor(available);

                L.circleMarker([lat, lon], {
                    color: colorEstacion,       // Color del borde del círculo
                    fillColor: colorEstacion,   // ¡NUEVO! Color de relleno del círculo
                    fill: true,                 // ¡NUEVO! Fuerza el rellenado completo
                    radius: 8, 
                    fillOpacity: 0.7,
                    weight: 2                   // Grosor del contorno
                }) 
                .addTo(map) 
                .bindPopup(` 
                    <strong>${address}</strong><br> 
                    <b>Disponibles:</b> ${available}<br> 
                    <b>Libres:</b> ${free}<br> 
                    <b>Total:</b> ${total} 
                `); 
            } 
        }); 
    }) 
    .catch(error => { 
        console.error('Error cargando los datos:', error); 
    }); 
</script>

</body>
</html>