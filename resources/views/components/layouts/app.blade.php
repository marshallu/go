<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>GoMarshall</title>
	<link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />
	@vite('resources/css/app.css')
	@fluxAppearance
</head>
<body class="font-sans bg-zinc-50 antialiased">
	<div class="max-w-2xl px-6 mx-auto py-8">
		<flux:heading size="xl" :accent=true>GoMarshall123456789</flux:heading>
		<flux:subheading>Create shortened URLs and QR codes for Marshall University.</flux:subheading>
	</div>

	<div class="pb-32">
		{{ $slot }}
	</div>

	@persist('toast')
        <flux:toast />
    @endpersist

	@fluxScripts
</body>
</html>
