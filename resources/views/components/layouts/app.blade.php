<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>GoMarshall</title>
	@vite('resources/css/app.css')
</head>
<body class="font-sans bg-gray-50 antialiased">
	<div class="max-w-2xl px-6 mx-auto py-8">
		<a href="/"><h1 class="text-green font-bold text-3xl">GoMarshall</h1></a>
		<div class="mt-2 text-lg font-semibold text-gray-900">Create shortened URLs and QR codes for Marshall University.</div>
	</div>

	<div class="max-w-2xl px-6 mx-auto pt-8 pb-32">
		{{ $slot }}
	</div>
</body>
</html>
