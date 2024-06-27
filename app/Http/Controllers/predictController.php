<?php

namespace App\Http\Controllers;

use App\Models\diagnosis;
use App\Models\diagnosis_histories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        // Store the file in a public folder
        $path = $file->store('predict_pictures', 'public');

        // Get the full path of the file
        $filePath = storage_path('app/public/' . $path);

        // Send the file to the Flask API
        $response = Http::attach('file', file_get_contents($filePath), $file->getClientOriginalName())
            ->post('http://127.0.0.1:5000/predict');

        // Check if the Flask API request was successful
        if ($response->failed()) {
            return back()->withErrors(['error' => 'Failed to get a response from the prediction service']);
        }

        // Get the response from the Flask API
        $prediction = $response->json();

        // Get the public URL of the uploaded image
        $imageUrl = asset('storage/' . $path);

        // Return the prediction and image URL to a view
        return view('prediction_result', compact('prediction', 'imageUrl'));

    } catch (\Exception $e) {
        return back()->withErrors(['error' => $e->getMessage()]);
    }
}
public function saveDiagnosis(Request $request)
{
    $request->validate([
        'skin_image' => 'required|string',
        'diagnose' => 'required|string',
    ]);

    diagnosis_histories::create([
        'user_id' => Auth::id(),
        'skin_image' => $request->skin_image,
        'diagnose' => $request->diagnose,
    ]);

    return view('predict-form')->with('success', 'Diagnosis saved successfully!');
}

    }
