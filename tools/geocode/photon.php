<?php
// tools/geocode/photon.php
// Simple Photon geocoder helper using GuzzleHttp
// Usage: set PHOTON_BASE_URL (e.g. http://192.168.0.20:2322) and call geocodeWithPhoton($address)

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

if (!function_exists('geocodeWithPhoton')) {
    /**
     * Geocode an address using a Photon instance.
     *
     * @param string $address
     * @param string|null $baseUrl
     * @param int $limit
     * @return array|null ['latitude' => float, 'longitude' => float, 'raw' => array]
     * @throws GuzzleException
     */
    function geocodeWithPhoton(string $address, ?string $baseUrl = null, int $limit = 1): ?array
    {
        $baseUrl = rtrim($baseUrl ?? getenv('PHOTON_BASE_URL') ?: 'http://192.168.0.20:2322', '/');
        if ($address === '') return null;

        $client = new Client(['base_uri' => $baseUrl]);
        $response = $client->request('GET', '/api/', [
            'query' => ['q' => $address, 'limit' => $limit],
            'headers' => ['Accept' => 'application/json']
        ]);

        $status = $response->getStatusCode();
        if ($status < 200 || $status >= 300) {
            throw new RuntimeException("Photon request failed: HTTP $status");
        }

        $body = (string)$response->getBody();
        $data = json_decode($body, true);
        if (empty($data['features'])) return null;

        $coords = $data['features'][0]['geometry']['coordinates']; // [lon, lat]
        return [
            'latitude' => (float)$coords[1],
            'longitude' => (float)$coords[0],
            'raw' => $data['features'][0]
        ];
    }
}