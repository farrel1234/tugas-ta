<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - Perpustakaan Digital</title>
    
    <!-- Memanggil Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased">
    
    <!-- Bagian ini akan otomatis berubah sesuai halaman yang dibuka -->
    <main>
        @yield('content')
    </main>

</body>
</html>