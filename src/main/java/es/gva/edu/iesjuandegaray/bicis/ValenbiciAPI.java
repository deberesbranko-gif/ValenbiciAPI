package es.gva.edu.iesjuandegaray.bicis;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.CloseableHttpClient;
import org.apache.http.impl.client.HttpClients;
import org.apache.http.util.EntityUtils;
import org.json.JSONArray;
import org.json.JSONObject;

public class ValenbiciAPI {

    private static final String API_URL = "https://valencia.opendatasoft.com/api/explore/v2.1/catalog/datasets/valenbisi-disponibilitat-valenbisi-dsiponibilidad/records?limit=20";

    public static void main(String[] args) {
        if (API_URL.isEmpty()) {
            System.err.println("La URL de la API no está especificada.");
            return;
        }

        System.out.println("Conectando con la API de Valenbisi...");

        try (CloseableHttpClient httpClient = HttpClients.createDefault()) {
            HttpGet request = new HttpGet(API_URL);
            HttpResponse response = httpClient.execute(request);
            HttpEntity entity = response.getEntity();

            if (entity != null) {
                String result = EntityUtils.toString(entity);
                
                JSONObject jsonObject = new JSONObject(result);
                JSONArray resultsArray = jsonObject.getJSONArray("results");

                System.out.println("\n--- DATOS DE LAS ESTACIONES (VALENBISI) ---");
                for (int i = 0; i < resultsArray.length(); i++) {
                    JSONObject station = resultsArray.getJSONObject(i);
                    
                    // Extraemos los campos requeridos por la práctica
                    String name = station.optString("name", "Desconocida");
                    int number = station.optInt("number", 0);
                    int availableBikes = station.optInt("available", 0);
                    int freeSlots = station.optInt("free", 0);
                    int totalSlots = station.optInt("total", 0);

                    // Imprimimos los datos limpios por la consola de Eclipse
                    System.out.println("Estación: " + number + " - " + name);
                    System.out.println("  -> Bicicletas Disponibles: " + availableBikes);
                    System.out.println("  -> Espacios Disponibles (Anclajes Libres): " + freeSlots);
                    System.out.println("  -> Capacidad Total: " + totalSlots);
                    System.out.println("----------------------------------------");
                }
            }
        } catch (Exception e) {
            System.err.println("Error al procesar los datos de la API: " + e.getMessage());
            e.printStackTrace();
        }
    }
}