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
            $result = $this->processPlantIdentification(
                $request->file('image'),
                $request->input('organ')
            );

            return Inertia::render('Detect', ['plantData' => $result]);
        } catch (\Exception $e) {
            Log::error('Plant identification error: ' . $e->getMessage());

            return back()->with('data', [
                'success' => false,
                'message' => 'An error occurred during identification',
                'error' => $e->getMessage()
            ]);
        }
    }

    private function processPlantIdentification($imageFile, $organ)
    {
        // Save the uploaded image
        $path = $imageFile->store('temp-plant-images');
        $fullPath = Storage::path($path);

        try {
            // Get API response
            $response = $this->sendIdentificationRequest($fullPath, $organ);

            // Process response
            if ($response->successful()) {
                $result = [
                    'success' => true,
                    'data' => $response->json()
                ];
            } else {
                $result = [
                    'success' => false,
                    'message' => 'API returned an error: ' . $response->status(),
                    'error' => $response->json() ?? $response->body()
                ];
            }

            return $result;
        } finally {
            // Clean up the temporary file
            Storage::delete($path);
        }
    }

    private function sendIdentificationRequest($imagePath, $organ)
    {
        $apiKey = env('PLANTNET_API_KEY');
        $connector = new IntegrationsPlantNetConnector();

        $plantNetRequest = new IntegrationsIdentifyPlantRequest(
            'all',     // project
            $imagePath, // local image path
            $apiKey,   // API key
            $organ     // organ type
        );

        return $connector->send($plantNetRequest);
    }
}
