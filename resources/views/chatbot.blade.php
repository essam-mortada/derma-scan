
@extends('layouts.app')
@extends('layouts.navbar')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Kalnia+Underline&display=swap" rel="stylesheet">
      <style>
          body {
              font-family: 'Kalnia Underline', sans-serif;
          }
      </style>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width,
								initial-scale=1.0" />
	<title>Chatbot </title>
	<script src="https://cdn.tailwindcss.com"></script>
	<script
	src="https://cdnjs.cloudflare.com/ajax/libs/showdown/2.1.0/showdown.min.js"></script>
	<style type="text/tailwindcss">
	@layer base {
		ul {
		list-style-type: circle;
		}
		ol {
		list-style-type: decimal;
		}
	}
	</style>
</head>
<body class="flex flex-col justify-between h-screen">
	<div >
	<p class="text-4xl font-bold text-center text-black-500">
		<br/>
		 Chatbot 
	</p>
	<div class="overflow-y-auto" style="max-height: 80vh">
		<div id="messageHolder" class="flex flex-col m-4"></div>
	</div>
	</div>
	<div class="flex flex-row m-4">
	<input
		type="text"
		class="flex-1 border rounded-md m-2 border-black-600 
			p-2 outline-none ring-2 ring-black 
			border-transparent"
		placeholder="Chat..."
		name="chat"
		id="chat"
	/>
	<button id="btn" class="m-2 bg-black p-2 rounded-md text-white">
		Send
	</button>
	</div>
	<script type="importmap">
	{
		"imports": {
		"@google/generative-ai": "https://esm.run/@google/generative-ai"
		}
	}
	</script>
	<script type="module" src="{{asset('js/chatbot.js')}}"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
  
</body>
</html>
@endsection