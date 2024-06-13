<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PredictController extends Controller
{
    public function predict(Request $request)
    {
        // Validate the request
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Check if the file is present in the request
        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'No file part in the request'], 400);
        }

        $file = $request->file('file');

        // Check if the file is valid
        if (!$file->isValid()) {
            return response()->json(['error' => 'Invalid file upload'], 400);
        }

        try {
            // Store the file temporarily
            $path = $file->store('temp');

            // Get the full path of the file
            $filePath = storage_path('app/' . $path);

            // Send the file to the Flask API
            $response = Http::attach('file', file_get_contents($filePath), $file->getClientOriginalName())
                ->post('http://127.0.0.1:5000/predict');

            // Delete the temporary file
            Storage::delete($path);

            // Check if the Flask API request was successful
            if ($response->failed()) {
                return response()->json(['error' => 'Failed to get a response from the prediction service'], 500);
            }

            // Return the response from the Flask API
            return response()->json($response->json(), $response->status());

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}