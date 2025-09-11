<div x-data="{ open: false, mode: '', collection: '', oldCollection: '', load: '' }"
    @open-modal.window="
        if ($event.detail.type === 'edit-collection') {
            open = true;
            mode = 'edit';
            collection = $event.detail.collection;
            oldCollection = $event.detail.collection;
            load = 'Collection/RenameCollection';
        } else if ($event.detail.type === 'delete-collection') {
            open = true;
            mode = 'delete';
            collection = $event.detail.collection;
            oldCollection = $event.detail.collection;
            load = 'Collection/DropCollection';
        }
     ">

    <!-- Overlay -->
    <div x-show="open" x-cloak
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <!-- Modal -->
        <form method="post" action="index.php"
            class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">

            <!-- Header -->
            <div class="flex justify-between items-center border-b pb-2 mb-4">
                <h3 class="text-lg font-semibold text-gray-800"
                    x-text="mode === 'edit' ? 'Edit Collection' : 'Delete Collection'"></h3>
                <button type="button" @click="open=false"
                    class="text-gray-400 hover:text-gray-600">×</button>
            </div>

            <!-- Body -->
            <div class="mb-4">
                <!-- Edit Mode -->
                <template x-if="mode === 'edit'">
                    <input type="text" name="collection" x-model="collection"
                        class="w-full border rounded px-3 py-2" required>
                </template>

                <!-- Delete Mode -->
                <template x-if="mode === 'delete'">
                    <p class="text-red-600 text-sm">
                        <?php I18n::p('A_Y_W_T_D_C'); ?>
                    </p>
                </template>
            </div>

            <!-- Footer -->
            <div class="flex justify-end gap-3">
                <button type="button" @click="open=false"
                    class="px-3 py-2 border rounded text-gray-600 hover:bg-gray-100">
                    <?php I18n::p('CANCEL'); ?>
                </button>

                <!-- Save -->
                <template x-if="mode === 'edit'">
                    <button type="submit"
                        class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        <?php I18n::p('SAVE'); ?>
                    </button>
                </template>

                <!-- Delete -->
                <template x-if="mode === 'delete'">
                    <button type="submit" name="delete" value="1"
                        class="px-3 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        <?php I18n::p('DELETE'); ?>
                    </button>
                </template>
            </div>

            <!-- Hidden Fields -->
            <input type="hidden" name="load" :value="load">
            <input type="hidden" name="db" value="<?php echo $this->db; ?>">
            <input type="hidden" name="old_collection" :value="oldCollection">
        </form>
    </div>
</div>