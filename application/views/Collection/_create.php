<div class="w-full md:w-[48%] bg-white rounded shadow border border-gray-200">
    <div class="px-4 py-2 font-semibold text-gray-700 border-b">
        <?php echo I18n::t('CAE_COL'); ?>
    </div>

    <div class="p-4">
        <form id="form-create-collection" method="post" class="space-y-4" action="index.php">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1"><?php I18n::p('NAME'); ?></label>
                <input type="text" id="collection_name" name="collection" required
                    class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-green-500">
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" id="collection_capped" name="capped" value="1">
                <label for="collection_capped" class="text-sm text-gray-700"><?php I18n::p('IS_CAPPED'); ?></label>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1"><?php I18n::p('SIZE'); ?></label>
                <input type="text" id="collection_size" name="size"
                    class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1"><?php I18n::p('MAX'); ?></label>
                <input type="text" id="collection_max" name="max"
                    class="w-full border rounded px-3 py-2">
            </div>

            <input type="hidden" name="load" value="Collection/CreateCollection">
            <input type="hidden" name="db" value="<?php echo $this->db; ?>">

            <div class="pt-2">
                <button type="submit"
                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    <?php I18n::p('CREATE'); ?>
                </button>
            </div>
        </form>
    </div>
</div>