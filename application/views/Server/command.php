<?php if (!empty($this->data['output'])) { ?>
    <!-- Terminal Style Output -->
    <div class="bg-black text-green-400 font-mono text-sm rounded-md shadow-inner border border-gray-700 p-4 max-h-[600px] overflow-auto">
        <pre class="whitespace-pre-wrap leading-snug"><?php print_r($this->data['output']); ?></pre>
    </div>
<?php } elseif (isset($this->data['error'])) { ?>
    <!-- Error Box -->
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