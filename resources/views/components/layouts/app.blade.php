<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>GoMarshall</title>
	@vite('resources/css/app.css')
	@fluxAppearance
</head>
<body class="font-sans bg-gray-50 antialiased">
	<div class="max-w-2xl px-6 mx-auto py-8">
		<flux:heading size="xl">GoMarshall</flux:heading>
		<flux:subheading>Create shortened URLs and QR codes for Marshall University.</flux:subheading>
	</div>

	<div class="pt-8 pb-32">
		{{ $slot }}
	</div>
	@fluxScripts
</body>
</html>
