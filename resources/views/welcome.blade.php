<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>Tailwind Learning</title>
</head>
<body>
    <div class="min-h-screen bg-zinc-900 flex justify-center items-center">
        <div class="overflow-hidden bg-black rounded-3xl w-[900px]">
            <img src="{{ asset('images/nfs-heat.jpg') }}" alt="nfs-heat" class="w-full h-80 object-cover">
            <div class="flex justify-between p-6">
                <div>
                    <h2 class="text-white text-4xl font-bold">NFS HEAT</h2>
                    <p class="text-zinc-400">Race your way through Palm City.</p>
                </div>

                <button class="bg-pink-300 text-blue-900 rounded-2xl w-64 h-20 text-3x1 font-bold">
                    Play
                </button>
            </div>
        </div>
    </div>
</body>
</html>
