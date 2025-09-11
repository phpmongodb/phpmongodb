<!-- Page Header -->
<div class="bg-gray-100 border-b border-gray-300 py-4 px-6 mb-4 flex items-center">
    <h1 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
        <!-- Icon: MongoDB server -->
        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M12 3C7 3 3 5 3 7v10c0 2 4 4 9 4s9-2 9-4V7c0-2-4-4-9-4z" />
        </svg>

        <a href="javascript:void(0)" class="text-green-700 hover:underline">Server Status</a>

        <small class="text-sm text-gray-500 ml-2 hidden sm:inline">
            db.runCommand({ serverStatus: 1 })
        </small>
    </h1>
</div>

<!-- Server Status Output -->
<div class="mt-4 bg-white border border-gray-300 rounded-md shadow-sm px-4 py-4">
    <pre class="text-sm font-mono text-gray-800 max-h-[600px] overflow-auto">
<?= htmlspecialchars($this->data['status'], ENT_QUOTES, 'UTF-8') ?>
  </pre>
</div>