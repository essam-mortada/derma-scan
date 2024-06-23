<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class predictController extends Controller
{
    public function showPredictView(){
        return view('predict');
    }
    public function showPredictCursoul(){
        return view('predict-cursoul');
    }
    public function showPredictForm(){
        return view('predict-form');
    }
    public function predict(Request $request)
    {
        // Validate the request
        $request->validate([
            'file' => 'required|image|mimes:jpeg,jpg|max:2048'
        ]);

        // Check if the file is present in the request
        if (!$request->hasFile('file')) {
            return back()->withErrors(['error' => 'No file part in the request']);
        }

        $file = $request->file('file');

        // Check if the file is valid
        if (!$file->isValid()) {
            return back()->withErrors(['error' => 'Invalid file upload']);
        }

        try {
            // Store the file in a specific folder
            $path = $file->store('temp');

            // Get the full path of the file
            $filePath = storage_path('app/' . $path);

            // Send the file to the Flask API
            $response = Http::attach('file', file_get_contents($filePath), $file->getClientOriginalName())
                ->post('http://127.0.0.1:5000/predict');

            // Delete the file after processing
            Storage::delete($path);

            // Check if the Flask API request was successful
            if ($response->failed()) {
                return back()->withErrors(['error' => 'Failed to get a response from the prediction service']);
            }

            // Get the response from the Flask API
            $prediction = $response->json();

            // Return the prediction to a view
            return view('prediction_result', compact('prediction'));

        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    }
