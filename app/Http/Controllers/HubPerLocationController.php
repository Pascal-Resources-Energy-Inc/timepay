<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HubPerLocation;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HubPerLocationController extends Controller
{
    public function index(Request $request)
    {
        $region = $request->get('region', '');
        $territory = $request->get('territory', '');
        $area = $request->get('area', '');
        $hub_status = $request->get('hub_status', '');
        
        $header = 'Hub Per Location';
        
        $regions = HubPerLocation::select('region')
            ->distinct()
            ->whereNotNull('region')
            ->where('region', '!=', '')
            ->orderBy('region')
            ->get();
            
        $territories = collect();
        if ($region) {
            $territories = HubPerLocation::select('territory')
                ->distinct()
                ->where('region', $region)
                ->whereNotNull('territory')
                ->where('territory', '!=', '')
                ->orderBy('territory')
                ->get();
        }
            
        $areas = collect();
        if ($territory) {
            $areas = HubPerLocation::select('area')
                ->distinct()
                ->where('territory', $territory)
                ->whereNotNull('area')
                ->where('area', '!=', '')
                ->orderBy('area')
                ->get();
        }
            
        $hub_statuses = HubPerLocation::select('hub_status')
            ->distinct()
            ->whereNotNull('hub_status')
            ->where('hub_status', '!=', '')
            ->orderBy('hub_status')
            ->get();

        $query = HubPerLocation::select([
            'id', 'region', 'territory', 'area', 'hub_name', 'hub_code', 
            'retail_hub_address', 'hub_status', 'google_map_location_link',
            'lat', 'long'
        ]);
        
        if ($region) {
            $query->where('region', $region);
        }
        
        if ($territory) {
            $query->where('territory', $territory);
        }
        
        if ($area) {
            $query->where('area', $area);
        }
        
        if ($hub_status) {
            $query->where('hub_status', $hub_status);
        }
        
        $hubs = $query->orderBy('region')->orderBy('territory')->orderBy('area')->orderBy('hub_name')->get();

        return view('hubs.hub_per_location', compact(
            'header',
            'regions',
            'territories', 
            'areas', 
            'hub_statuses',
            'region',
            'territory',
            'area',
            'hub_status',
            'hubs'
        ));
    }
    
    
    private function extractLatLongFromGoogleMapsUrl($url)
    {
        if (empty($url)) {
            return ['lat' => null, 'lng' => null, 'accuracy' => 'no_url'];
        }

        $url = trim($url);
        
        if (preg_match('/(?:maps\.app\.goo\.gl|goo\.gl\/maps|g\.page)/', $url)) {
            $expandedUrl = $this->expandShortenedUrl($url);
            if ($expandedUrl) {
                $url = $expandedUrl;
            }
        }

        $lat = null;
        $lng = null;
        $accuracy = 'not_found';

        if (preg_match('/@(-?\d+\.?\d*),(-?\d+\.?\d*),(\d+\.?\d*[a-z]?)/', $url, $matches)) {
            $lat = (float) $matches[1];
            $lng = (float) $matches[2];
            $accuracy = 'standard_precise';
        }
        elseif (preg_match('/@(-?\d+\.?\d*),(-?\d+\.?\d*)/', $url, $matches)) {
            $lat = (float) $matches[1];
            $lng = (float) $matches[2];
            $accuracy = 'standard';
        }
        elseif (preg_match('/\/place\/[^\/]+\/@(-?\d+\.?\d*),(-?\d+\.?\d*),(\d+\.?\d*[a-z]?)/', $url, $matches)) {
            $lat = (float) $matches[1];
            $lng = (float) $matches[2];
            $accuracy = 'place_precise';
        }
        elseif (preg_match('/\/place\/[^\/]+\/@(-?\d+\.?\d*),(-?\d+\.?\d*)/', $url, $matches)) {
            $lat = (float) $matches[1];
            $lng = (float) $matches[2];
            $accuracy = 'place';
        }
        elseif (preg_match('/[?&]q=(-?\d+\.?\d*),(-?\d+\.?\d*)/', $url, $matches)) {
            $lat = (float) $matches[1];
            $lng = (float) $matches[2];
            $accuracy = 'query';
        }
        elseif (preg_match('/[?&]ll=(-?\d+\.?\d*),(-?\d+\.?\d*)/', $url, $matches)) {
            $lat = (float) $matches[1];
            $lng = (float) $matches[2];
            $accuracy = 'll_param';
        }
        elseif (preg_match('/[?&]center=(-?\d+\.?\d*),(-?\d+\.?\d*)/', $url, $matches)) {
            $lat = (float) $matches[1];
            $lng = (float) $matches[2];
            $accuracy = 'center_param';
        }
        elseif (preg_match('/[?&]destination=(-?\d+\.?\d*),(-?\d+\.?\d*)/', $url, $matches)) {
            $lat = (float) $matches[1];
            $lng = (float) $matches[2];
            $accuracy = 'destination';
        }
        elseif (preg_match('/\/search\/([^\/\?&]+)/', $url, $matches)) {
            $accuracy = 'place_name_only';
        }

        if ($lat !== null && $lng !== null) {
            if ($lat >= -90 && $lat <= 90 && $lng >= -180 && $lng <= 180) {

                if ($this->isWithinPhilippines($lat, $lng)) {
                    $accuracy .= '_validated_ph';
                } else {
                    $accuracy .= '_validated_global';
                }
            } else {
                $lat = null;
                $lng = null;
                $accuracy = 'invalid_coordinates';
            }
        }

        return [
            'lat' => $lat, 
            'lng' => $lng, 
            'accuracy' => $accuracy,
            'processed_url' => $url
        ];
    }

    private function isWithinPhilippines($lat, $lng)
    {
        return ($lat >= 4.0 && $lat <= 22.0 && $lng >= 116.0 && $lng <= 127.0);
    }


    private function expandShortenedUrl($url)
    {
        try {
            if (class_exists('Illuminate\Support\Facades\Http')) {
                $response = Http::timeout(15)
                    ->withOptions([
                        'allow_redirects' => [
                            'max' => 10,
                            'strict' => true,
                            'referer' => true,
                            'track_redirects' => true
                        ],
                        'verify' => false,
                    ])
                    ->head($url);
                
                if ($response->successful()) {
                    $finalUrl = $response->effectiveUri();
                    if ($finalUrl && $finalUrl !== $url) {
                        return $finalUrl;
                    }
                }
            }

            return $this->expandUrlWithCurl($url);

        } catch (\Exception $e) {
            Log::warning('Failed to expand shortened Google Maps URL', [
                'url' => $url,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    private function expandUrlWithCurl($url)
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => false,
            CURLOPT_NOBODY => true,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            CURLOPT_HTTPHEADER => [
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Accept-Language: en-US,en;q=0.5',
                'Accept-Encoding: gzip, deflate',
                'Connection: keep-alive',
            ],
        ]);
        
        curl_exec($ch);
        $finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            Log::warning('cURL error when expanding URL', [
                'url' => $url,
                'error' => $error
            ]);
            return null;
        }
        
        if ($httpCode >= 200 && $httpCode < 400 && $finalUrl && $finalUrl !== $url) {
            return $finalUrl;
        }
        
        return null;
    }

    private function getCoordinatesFromAddress($address, $apiKey = null)
    {
        if (!$apiKey) {
            $apiKey = config('services.google.maps_api_key');
        }
        
        if (!$apiKey) {
            return ['lat' => null, 'lng' => null, 'accuracy' => 'no_api_key'];
        }
        
        try {
            $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
                'address' => $address,
                'key' => $apiKey,
                'region' => 'ph',
            ]);
            
            $data = $response->json();
            
            if ($data['status'] === 'OK' && !empty($data['results'])) {
                $result = $data['results'][0];
                $location = $result['geometry']['location'];
                
                return [
                    'lat' => $location['lat'],
                    'lng' => $location['lng'],
                    'accuracy' => 'geocoding_api',
                    'formatted_address' => $result['formatted_address'],
                    'place_id' => $result['place_id'] ?? null
                ];
            }
            
        } catch (\Exception $e) {
            Log::error('Geocoding API error', [
                'address' => $address,
                'error' => $e->getMessage()
            ]);
        }
        
        return ['lat' => null, 'lng' => null, 'accuracy' => 'geocoding_failed'];
    }
    
    public function edit(Request $request, $id)
    {
        $request->validate([
            'region' => 'required|string|max:255',
            'territory' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'hub_name' => 'required|string|max:255',
            'hub_code' => 'required|string|max:50|unique:hub_per_location,hub_code,' . $id,
            'retail_hub_address' => 'required|string',
            'hub_status' => 'required|string|max:50',
            'google_map_location_link' => 'nullable|url',
        ]);

        $hub = HubPerLocation::findOrFail($id);

        $coordinates = $this->extractLatLongFromGoogleMapsUrl($request->google_map_location_link);
        
        if (!$coordinates['lat'] && !$coordinates['lng'] && $request->retail_hub_address) {
            $addressCoordinates = $this->getCoordinatesFromAddress($request->retail_hub_address);
            if ($addressCoordinates['lat'] && $addressCoordinates['lng']) {
                $coordinates = $addressCoordinates;
            }
        }

        $hub->region = $request->region;
        $hub->territory = $request->territory;
        $hub->area = $request->area;
        $hub->hub_name = $request->hub_name;
        $hub->hub_code = $request->hub_code;
        $hub->retail_hub_address = $request->retail_hub_address;
        $hub->hub_status = $request->hub_status;
        $hub->google_map_location_link = $request->google_map_location_link;
        $hub->lat = $coordinates['lat'];
        $hub->long = $coordinates['lng'];
        $hub->updated_at = now();

        $hub->save();

        $message = 'Hub updated successfully';
        if ($coordinates['lat'] && $coordinates['lng']) {
            $accuracyInfo = $this->getAccuracyMessage($coordinates['accuracy']);
            $message .= " with coordinates extracted ({$accuracyInfo})!";
        } else if ($request->google_map_location_link) {
            $message .= ', but coordinates could not be extracted from the Google Maps link.';
        } else {
            $message .= '!';
        }

        Alert::success($message)->persistent('Dismiss');
        return back();
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'region' => 'required|string|max:255',
            'territory' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'hub_name' => 'required|string|max:255',
            'hub_code' => 'required|string|max:50',
            'retail_hub_address' => 'required|string',
            'hub_status' => 'required|string|max:50',
            'google_map_location_link' => 'nullable|url',
        ]);

        $id = $request->input('id');

        $coordinates = $this->extractLatLongFromGoogleMapsUrl($request->google_map_location_link);
        
        if (!$coordinates['lat'] && !$coordinates['lng'] && $request->retail_hub_address) {
            $addressCoordinates = $this->getCoordinatesFromAddress($request->retail_hub_address);
            if ($addressCoordinates['lat'] && $addressCoordinates['lng']) {
                $coordinates = $addressCoordinates;
            }
        }

        if ($id) {
            $request->validate([
                'hub_code' => 'required|string|max:50|unique:hub_per_location,hub_code,' . $id,
            ]);

            $hub = HubPerLocation::findOrFail($id);
            $action = 'updated';
        } else {
            $request->validate([
                'hub_code' => 'required|string|max:50|unique:hub_per_location,hub_code',
            ]);

            $hub = new HubPerLocation;
            $action = 'created';
        }

        $hub->region = $request->region;
        $hub->territory = $request->territory;
        $hub->area = $request->area;
        $hub->hub_name = $request->hub_name;
        $hub->hub_code = $request->hub_code;
        $hub->retail_hub_address = $request->retail_hub_address;
        $hub->hub_status = $request->hub_status;
        $hub->google_map_location_link = $request->google_map_location_link;
        $hub->lat = $coordinates['lat'];
        $hub->long = $coordinates['lng'];

        $hub->save();

        $message = "Hub {$action} successfully";
        if ($coordinates['lat'] && $coordinates['lng']) {
            $accuracyInfo = $this->getAccuracyMessage($coordinates['accuracy']);
            $message .= " with coordinates extracted ({$accuracyInfo})!";
        } else if ($request->google_map_location_link) {
            $message .= ', but coordinates could not be extracted from the Google Maps link.';
        } else {
            $message .= '!';
        }

        Alert::success($message)->persistent('Dismiss');
        return back();
    }

    private function getAccuracyMessage($accuracy)
    {
        $messages = [
            'standard_precise' => 'High precision from Maps URL',
            'standard' => 'Good precision from Maps URL',
            'place_precise' => 'High precision from Place URL',
            'place' => 'Good precision from Place URL',
            'geocoding_api' => 'Maximum precision via Geocoding API',
            'query' => 'Moderate precision from query parameters',
            'll_param' => 'Good precision from coordinates',
            'center_param' => 'Moderate precision from center point',
            'destination' => 'Good precision from destination',
        ];
        
        foreach ($messages as $key => $message) {
            if (strpos($accuracy, $key) !== false) {
                if (strpos($accuracy, '_validated_ph') !== false) {
                    return $message . ' - Philippines validated';
                } elseif (strpos($accuracy, '_validated_global') !== false) {
                    return $message . ' - globally validated';
                }
                return $message;
            }
        }
        
        return 'coordinates extracted';
    }
    
    public function getTerritoriesByRegion(Request $request)
    {
        $region = $request->get('region');
        
        $territories = HubPerLocation::select('territory')
            ->distinct()
            ->where('region', $region)
            ->whereNotNull('territory')
            ->where('territory', '!=', '')
            ->orderBy('territory')
            ->get();
            
        return response()->json($territories);
    }
    
    public function getAreasByTerritory(Request $request)
    {
        $territory = $request->get('territory');
        
        $areas = HubPerLocation::select('area')
            ->distinct()
            ->where('territory', $territory)
            ->whereNotNull('area')
            ->where('area', '!=', '')
            ->orderBy('area')
            ->get();
            
        return response()->json($areas);
    }


    public function validateCoordinates(Request $request)
    {
        $url = $request->input('url');
        $address = $request->input('address');
        
        $result = [];
        
        if ($url) {
            $result['url_extraction'] = $this->extractLatLongFromGoogleMapsUrl($url);
        }
        
        if ($address) {
            $result['address_geocoding'] = $this->getCoordinatesFromAddress($address);
        }
        
        return response()->json($result);
    }
}