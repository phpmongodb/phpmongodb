<!-- Page Header -->
<div class="bg-gray-100 border-b border-gray-300 py-4 px-6 mb-4">
    <h1 class="text-xl font-semibold text-gray-800">Output</h1>
</div>

<!-- Output Container -->
<div class="bg-white shadow rounded-md border border-gray-200 p-6">
    <div id="execute-response">
        <?php if (!empty($this->data['output'])) { ?>
            <pre class="text-sm font-mono bg-gray-50 border border-gray-200 rounded px-3 py-3 max-h-[500px] overflow-auto">
<?php print_r($this->data['output']); ?>
      </pre>
        <?php } elseif (isset($this->data['error'])) { ?>
            <div class="flex items-start gap-2 bg-red-50 border border-red-300 text-red-700 rounded px-4 py-3">
                <!-- Error Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01M21 12A9 9 0 113 12a9 9 0 0118 0z" />
                </svg>
                <div>
                    <strong class="block font-semibold">Note:</strong>
                    <span><?php echo $this->data['error']; ?></span>
                </div>
            </div>
        <?php } ?>
    </div>
</div>