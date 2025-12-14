<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug HDV</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Debug Hướng Dẫn Viên</h1>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Guides Table -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Bảng Guides</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-2 text-left">ID</th>
                                <th class="px-4 py-2 text-left">Name</th>
                                <th class="px-4 py-2 text-left">Full Name</th>
                                <th class="px-4 py-2 text-left">Email</th>
                                <th class="px-4 py-2 text-left">User ID</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $guides = \App\Models\Guide::all();
                            @endphp
                            @foreach($guides as $guide)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $guide->id }}</td>
                                <td class="px-4 py-2">{{ $guide->name ?? 'NULL' }}</td>
                                <td class="px-4 py-2">{{ $guide->full_name ?? 'NULL' }}</td>
                                <td class="px-4 py-2">{{ $guide->email ?? 'NULL' }}</td>
                                <td class="px-4 py-2">{{ $guide->user_id ?? 'NULL' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Users with Guide Role -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Users với Role 'guide'</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-2 text-left">ID</th>
                                <th class="px-4 py-2 text-left">Name</th>
                                <th class="px-4 py-2 text-left">Email</th>
                                <th class="px-4 py-2 text-left">Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $users = \App\Models\User::where('role', 'guide')->get();
                            @endphp
                            @foreach($users as $user)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $user->id }}</td>
                                <td class="px-4 py-2">{{ $user->name }}</td>
                                <td class="px-4 py-2">{{ $user->email }}</td>
                                <td class="px-4 py-2">{{ $user->role }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- API Test -->
        <div class="bg-white rounded-lg shadow p-6 mt-6">
            <h2 class="text-xl font-semibold mb-4">Test API</h2>
            <button onclick="testAPI()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Test /api/guides/available
            </button>
            <div id="api-result" class="mt-4 p-4 bg-gray-50 rounded hidden">
                <!-- API result will be shown here -->
            </div>
        </div>
    </div>

    <script>
        async function testAPI() {
            try {
                const response = await fetch('/api/guides/available');
                const data = await response.json();
                
                const resultDiv = document.getElementById('api-result');
                resultDiv.classList.remove('hidden');
                resultDiv.innerHTML = `
                    <h3 class="font-semibold mb-2">API Response:</h3>
                    <pre class="text-sm overflow-x-auto">${JSON.stringify(data, null, 2)}</pre>
                `;
            } catch (error) {
                const resultDiv = document.getElementById('api-result');
                resultDiv.classList.remove('hidden');
                resultDiv.innerHTML = `
                    <h3 class="font-semibold mb-2 text-red-600">Error:</h3>
                    <p class="text-red-600">${error.message}</p>
                `;
            }
        }
    </script>
</body>
</html>