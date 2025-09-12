<!-- Page Header -->
<div class="bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-4 shadow flex items-center justify-between">
    <h1 class="text-lg sm:text-xl font-semibold tracking-wide flex items-center gap-2">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M5 13l4 4L19 7" />
        </svg>
        Execute Command
    </h1>
    <small class="hidden sm:inline text-green-100 font-mono">Run custom MongoDB commands</small>
</div>

<!-- Execute Section -->
<div class="bg-white shadow rounded-lg border border-gray-200 p-6 mt-6"
    x-data="{
        code: `<?php echo $this->data['code']; ?>`,
        db: '<?php echo $this->data['db']; ?>',
        response: '',
        execute() {
          fetch('index.php?load=Server/Execute&theme=false', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ code: this.code, db: this.db })
          })
          .then(res => res.text())
          .then(html => { 
              try { this.response = JSON.stringify(JSON.parse(html), null, 2) }
              catch { this.response = html }
          })
          .catch(err => { this.response = 'Error: ' + err });
        },
        copyOutput() {
          navigator.clipboard.writeText(this.response);
          alert('Output copied to clipboard!');
        }
     }">

    <!-- Code Editor -->
    <label for="execute_code" class="block text-sm font-medium text-gray-700 mb-2">Code</label>
    <textarea id="execute_code" x-model="code" name="code"
        class="w-full font-mono text-sm bg-gray-900 text-green-200 border border-gray-700 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 min-h-[200px] shadow-inner"></textarea>

    <!-- Database Selector -->
    <div class="mt-4 flex items-center gap-3">
        <label for="execute_db" class="text-sm font-medium text-gray-700">Database:</label>
        <select id="execute_db" x-model="db" name="db"
            class="px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500 shadow-sm">
            <?php
            foreach ($this->data['databases'] as $db) {
                $selected = ($this->data['db'] == $db['name'] ? 'selected="selected"' : '');
                echo '<option value="' . $db['name'] . '" ' . $selected . '>' . $db['name'] . '</option>';
            }
            ?>
        </select>
    </div>

    <!-- Execute Button -->
    <div class="mt-6 flex items-center gap-4">
        <button type="button"
            @click="response='Running...'; execute()"
            class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow text-sm font-medium transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-5.197-3.028A1 1 0 008 9.028v5.944a1 1 0 001.555.832l5.197-3.028a1 1 0 000-1.664z" />
            </svg>
            <?php I18n::p('Execute'); ?>
        </button>

        <button type="button"
            @click="copyOutput"
            class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded shadow text-sm transition">
            📋 Copy Output
        </button>
    </div>

    <!-- Output -->
    <div class="mt-6">
        <?php if (!empty($this->data['output'])) { ?>
            <pre class="text-sm font-mono bg-gray-900 text-green-200 border border-gray-700 rounded px-3 py-3 max-h-[400px] overflow-auto whitespace-pre-wrap shadow-inner"><?php print_r($this->data['output']); ?></pre>
        <?php } ?>
        <pre x-text="response"
            class="text-sm font-mono bg-gray-900 text-green-200 border border-gray-700 rounded px-3 py-3 max-h-[400px] overflow-auto mt-4 whitespace-pre-wrap shadow-inner"></pre>
    </div>
</div>