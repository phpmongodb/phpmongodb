<?php require_once '_menu.php'; ?>

<form method="post" action="index.php" enctype="multipart/form-data" class="space-y-6">
    <!-- Import Block -->
    <div class="bg-white shadow border rounded">
        <div class="border-b px-4 py-2 font-semibold bg-gray-50">
            <?php echo I18n::t('I_I_T_C', $this->collection); ?>
        </div>
        <div class="p-4">
            <label for="import_file" class="block mb-2 font-medium text-gray-700">
                <?php I18n::p('JSON_FILE'); ?>
            </label>
            <input type="file" name="import_file" id="import_file"
                class="w-full border rounded px-3 py-2 text-sm file:mr-3 file:py-1 file:px-3 file:border-0 file:bg-green-600 file:text-white hover:file:bg-green-700" />
        </div>
    </div>

    <!-- Hidden Inputs -->
    <input type="hidden" name="db" value="<?php echo $this->db; ?>" />
    <input type="hidden" name="collection" value="<?php echo $this->collection; ?>" />
    <input type="hidden" name="load" value="Collection/Import" />

    <!-- Submit Button -->
    <button type="submit"
        class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded shadow">
        <?php I18n::p('IMPORT'); ?>
    </button>
</form>