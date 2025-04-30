<?php

namespace App\Http\Controllers;

use App\Http\Integrations\IdentifyPlantRequest as IntegrationsIdentifyPlantRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Integrations\PlantNetConnector as IntegrationsPlantNetConnector;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PlantIdentifierController extends Controller
{
    public function index()
    {
        return Inertia::render('Detect');
    }

    public function identify(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:10240', // Max 10MB
            'organ' => 'required|string|in:flower,leaf,fruit,bark,habit,other',
        ]);

        try {
            // Save the uploaded image
            $path = $request->file('image')->store('temp-plant-images');
            $fullPath = Storage::path($path);

            // Get API key from environment
            $apiKey = env('PLANTNET_API_KEY');
            $connector = new IntegrationsPlantNetConnector();

            // Create the request
            $plantNetRequest = new IntegrationsIdentifyPlantRequest(
                'all',     // project
                $fullPath, // local image path
                $apiKey,   // API key
                $request->input('organ', 'flower') // organ type
            );

            // Send the request
            $response = $connector->send($plantNetRequest);


            // Clean up the temporary file
            Storage::delete($path);

            // Check if the response is successful
            if ($response->successful()) {
                $result = [
                    'success' => true,
                    'data' => $response->json()
                ];
            } else {
                // If the response is not successful, return an error message
                $result = [
                    'success' => false,
                    'message' => 'API returned an error: ' . $response->status(),
                    'error' => $response->json() ?? $response->body()
                ];
            }

            // dd($result);

            // Option 1: Use Inertia directly
            return Inertia::render('Detect', [
                'plantData' => $result
            ]);


            // Return with flash data for Inertia
            // return back()->with('plantData', $result);
        } catch (\Exception $e) {
            Log::error('Plant identification error: ' . $e->getMessage());

            return back()->with('data', [
                'success' => false,
                'message' => 'An error occurred during identification',
                'error' => $e->getMessage()
            ]);
        }
    }
}
