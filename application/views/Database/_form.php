<form method="post" name="form-drop-db" id="form-drop-db" action="index.php"
    x-data="{ open: false, mode: '', dbName: '', oldDb: '', dbExist: '' }"
    @open-modal.window="
        open = true; 
        mode = $event.detail.type; 
        dbName = $event.detail.db || ''; 
        oldDb = $event.detail.db || ''; 
        dbExist = $event.detail.exist || 'yes';
      ">

    <!-- Modal Overlay -->
    <div x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
        x-transition.opacity>

        <!-- Modal Box -->
        <div class="bg-white w-full max-w-md rounded shadow-lg overflow-hidden" @click.away="open = false">

            <!-- Header -->
            <div class="flex justify-between items-center px-4 py-3 border-b">
                <h3 class="text-lg font-semibold text-gray-800" x-text="mode === 'delete-db' ? '<?php I18n::p('DEL_C'); ?>' : 'Rename Database'"></h3>
                <button type="button" class="text-gray-500 hover:text-gray-700" @click="open = false">×</button>
            </div>

            <!-- Body -->
            <div class="px-4 py-4 space-y-3">
                <!-- Input (only for rename) -->
                <div x-show="mode === 'edit-db'">
                    <label for="pop-up-database" class="block text-sm font-medium text-gray-600 mb-1">
                        <?php I18n::p('NAME'); ?>
                    </label>
                    <input type="text" id="pop-up-database" name="db" x-model="dbName" required
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500" />
                </div>

                <!-- Warning text (only for delete) -->
                <p x-show="mode === 'delete-db'" class="text-red-600 text-sm flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12A9 9 0 113 12a9 9 0 0118 0z" />
                    </svg>
                    <?php I18n::p('A_Y_W_T_D_D'); ?>
                </p>
            </div>

            <!-- Footer -->
            <div class="px-4 py-3 flex justify-end gap-3 border-t">
                <button type="button" class="px-4 py-2 rounded border text-gray-600 hover:bg-gray-100"
                    @click="open = false"><?php I18n::p('CANCEL'); ?></button>

                <!-- Rename Save -->
                <button type="submit" x-show="mode === 'edit-db'"
                    @click="document.getElementById('pop-up-load').value='Database/Update'"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <?php I18n::p('SAVE'); ?>
                </button>

                <!-- Delete -->
                <button type="submit" x-show="mode === 'delete-db'"
                    @click="document.getElementById('pop-up-load').value='Database/Drop'"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded shadow">
                    <?php I18n::p('DELETE'); ?>
                </button>
            </div>
        </div>
    </div>

    <!-- Hidden Inputs -->
    <input type="hidden" id="pop-up-load" name="load" :value="mode === 'delete-db' ? 'Database/Drop' : 'Database/Update'" />
    <input type="hidden" id="pop-up-old-database" name="old_db" :value="oldDb" />
    <input type="hidden" id="pop-up-db-exist" name="db-exist" :value="dbExist" />
</form>