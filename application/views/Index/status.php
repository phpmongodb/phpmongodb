<!-- Page Header -->
<div class="bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-4 shadow flex items-center justify-between">
    <div class="flex items-center gap-3">
        <!-- Icon -->
        <div class="bg-white/20 rounded-full p-2">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 3C7 3 3 5 3 7v10c0 2 4 4 9 4s9-2 9-4V7c0-2-4-4-9-4z" />
            </svg>
        </div>

        <!-- Title -->
        <h1 class="text-lg sm:text-xl font-semibold tracking-wide">
            Server Status
        </h1>

        <!-- Subtitle -->
        <small class="hidden sm:inline text-green-100 font-mono ml-2">
            db.runCommand({ serverStatus: 1 })
        </small>
    </div>
</div>

<!-- Server Status Output -->
<div class="mt-6 bg-white border border-gray-200 rounded-lg shadow p-4">
    <h2 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M9 12h6m2 0a2 2 0 01-2 2H9a2 2 0 01-2-2m0 0a2 2 0 012-2h6a2 2 0 012 2z" />
        </svg>
        Status Output
    </h2>

    <div class="bg-gray-900 text-green-200 rounded-md p-4 text-sm font-mono max-h-[600px] overflow-auto border border-gray-700">
        <pre><?= htmlspecialchars($this->data['status'], ENT_QUOTES, 'UTF-8') ?></pre>
    </div>
</div>