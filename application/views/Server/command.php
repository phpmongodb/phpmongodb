<!-- Page Header -->
<div class="bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-4 shadow flex items-center justify-between">
    <h1 class="text-lg sm:text-xl font-semibold tracking-wide flex items-center gap-2">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M9 12h6m-6 4h6M5 8h14M5 16h14" />
        </svg>
        Output
    </h1>
</div>

<!-- Output Container -->
<div class="bg-white shadow rounded-lg border border-gray-200 p-6 mt-6"
    x-data="{ copied: false, copyText(id) { 
         let el = document.getElementById(id); 
         if(el){ navigator.clipboard.writeText(el.innerText); copied=true; setTimeout(()=>copied=false,2000);} 
     }}">

    <div id="execute-response">
        <?php if (!empty($this->data['output'])) { ?>
            <!-- Output Block -->
            <div class="relative">
                <button type="button" @click="copyText('output-block')"
                    class="absolute top-2 right-2 px-2 py-1 text-xs bg-gray-700 text-white rounded hover:bg-gray-800 transition">
                    <span x-show="!copied">📋 Copy</span>
                    <span x-show="copied" class="text-green-400">✔ Copied</span>
                </button>

                <pre id="output-block"
                    class="text-sm font-mono bg-gray-900 text-green-200 border border-gray-700 rounded px-3 py-3 max-h-[500px] overflow-auto shadow-inner whitespace-pre-wrap">
<?php print_r($this->data['output']); ?>
                </pre>
            </div>

        <?php } elseif (isset($this->data['error'])) { ?>
            <!-- Error Alert -->
            <div class="flex items-start gap-3 bg-red-50 border border-red-300 text-red-700 rounded px-4 py-3 shadow-sm">
                <!-- Error Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01M21 12A9 9 0 113 12a9 9 0 0118 0z" />
                </svg>
                <div>
                    <strong class="block font-semibold">Error:</strong>
                    <span><?php echo $this->data['error']; ?></span>
                </div>
            </div>
        <?php } else { ?>
            <p class="text-gray-500 text-sm">No output available.</p>
        <?php } ?>
    </div>
</div>