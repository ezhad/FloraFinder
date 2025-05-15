<?php

use App\Http\Integrations\IdentifyPlantRequest;
use App\Http\Integrations\PlantNetConnector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Api\PlantProxyController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//test api identify
Route::get('identify-plant', function () {
    $apiKey = env('PLANTNET_API_KEY');
    $connector = new PlantNetConnector();
    // $imageUrl = 'https://www.fauna-flora.org/wp-content/uploads/2023/06/our-green-planet-resplendent-rafflesia-reigns-supreme-scaled.jpg';

    $imageUrl = 'https://resources.finalsite.net/images/f_auto,q_auto,t_image_size_2/v1739518827/iskledumy/iiihl5epf5qwcttumzpv/Hibiscus_Flower.jpg';

    $result = [
        'status' => 'Processing',
        'data' => null
    ];

    try {
        // Create the request
        $request = new IdentifyPlantRequest(
            'all',     // project
            $imageUrl, // image URL
            $apiKey,   // API key
            'flower'   // organ type
        );

        // Send the request
        $response = $connector->send($request);

        // Check if the response is successful
        if ($response->successful()) {
            $result = [
                'status' => 'Success',
                'statusCode' => $response->status(),
                'data' => $response->json()
            ];
        } else {
            // If the response is not successful, prepare error data
            $result = [
                'status' => 'API returned an error',
                'statusCode' => $response->status(),
                'error' => $response->body()
            ];
        }
    } catch (\Exception $e) {
        $result = [
            'status' => 'Error',
            'message' => $e->getMessage()
        ];
    }

    dd($result);

    return response()->json($result);

    //pass to Detect.vue
    return Inertia::render('Detect', [
        'response' => $result,
    ]);
})->name('api-identify-plant');

// AR Plant Recognition API Routes
Route::prefix('api/ar')->group(function () {
    Route::get('/plants', [App\Http\Controllers\ARPlantController::class, 'getAllPlants']);
    Route::get('/plants/{id}', [App\Http\Controllers\ARPlantController::class, 'getPlant']);
    Route::post('/identify', [App\Http\Controllers\ARPlantController::class, 'identifyPlant']);
    Route::post('/educational-data', [App\Http\Controllers\ARPlantController::class, 'storeEducationalData']);
    Route::post('/garden-design', [App\Http\Controllers\ARPlantController::class, 'saveGardenDesign']);
    Route::post('/upload-marker', [App\Http\Controllers\ARPlantController::class, 'uploadMarker']);
});

Route::get('/proxy/iucn', [PlantProxyController::class, 'iucn']);
Route::get('/proxy/trefle', [PlantProxyController::class, 'trefle']);
