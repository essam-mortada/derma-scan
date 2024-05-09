<?php

namespace App\Http\Controllers;
use OpenAI;
use GuzzleHttp\Client;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GeminiAPI;
use Showdown\Converter;

class chatbotController extends Controller
{
   
    public function index(){
        return view('chatbot');
    }


    public function sendMessage(Request $request)
    {
        // Retrieve the message from the request
        $message = $request->input('message');

        // Retrieve the Gemini API key from the environment configuration
        $apiKey = 'AIzaSyAnT_wB6wa2WjYayeBEFeF1YGVOmtDVyCM';

        // Make a request to the Gemini API
        $response = Http::post('https://api.gemini.ai/v1/ask', [
            'api_key' => $apiKey,
            'query' => $message,
        ]);

        // Check if the request was successful
        if ($response->successful()) {
            // Extract the response data
            $responseData = $response->json();

            // Return the response
            return response()->json([
                'success' => true,
                'response' => $responseData,
            ]);
        } else {
            // Return an error response
            return response()->json([
                'success' => false,
                'message' => 'Failed to communicate with the Gemini API',
            ], $response->status());
        }
    }
}
