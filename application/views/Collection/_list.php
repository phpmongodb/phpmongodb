<?php if (!Application::isReadonly() && !empty($this->data['total'])) { ?>
    <!-- Bulk Actions -->
    <div class="flex items-center justify-between mb-3 bg-gray-50 px-3 py-2 rounded border">
        <label class="flex items-center gap-2 text-sm text-gray-700">
            <input type="checkbox" id="check-all"
                class="rounded border-gray-300 focus:ring-green-500"
                x-data
                x-on:click="document.querySelectorAll('.checkbox-remove').forEach(cb => cb.checked = $el.checked)">
            Select All
        </label>
        <button id="delete-all"
            class="inline-flex items-center gap-2 text-red-600 hover:text-red-700 text-sm font-medium"
            onclick="document.getElementById('delete-form').submit();">
            <!-- Trash Icon -->
            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-7 0h8" />
            </svg>
            Delete Selected
        </button>
        <input type="hidden" id="db-hidden" value="<?php echo $this->db; ?>" />
        <input type="hidden" id="collection-hidden" value="<?php echo $this->collection; ?>" />
    </div>
<?php } ?>

<!-- Record Tabs -->
<div class="bg-white rounded border shadow p-4" x-data="{ tab: 'json' }">
    <!-- Tabs -->
    <div class="border-b mb-3">
        <ul class="flex gap-4 text-sm font-medium">
            <li>
                <a href="javascript:void(0)"
                    @click="tab='json'"
                    :class="tab==='json' ? 'text-green-700 border-b-2 border-green-600 pb-1' : 'text-gray-600 hover:text-gray-800'">
                    JSON
                </a>
            </li>
            <li>
                <a href="javascript:void(0)"
                    @click="tab='array'"
                    :class="tab==='array' ? 'text-green-700 border-b-2 border-green-600 pb-1' : 'text-gray-600 hover:text-gray-800'">
                    Array
                </a>
            </li>
            <li>
                <a href="javascript:void(0)"
                    @click="tab='jsonv2'"
                    :class="tab==='jsonv2' ? 'text-green-700 border-b-2 border-green-600 pb-1' : 'text-gray-600 hover:text-gray-800'">
                    Extended JSON v2
                </a>
            </li>
        </ul>
    </div>

    <!-- Records -->
    <?php foreach ($this->data['format'] as $format) {
        if (!isset($this->data['record'][$format])) continue; ?>
        <div x-show="tab === '<?php echo $format; ?>'">
            <?php foreach ($this->data['record'][$format] as $index => $cursor) {
                if (!Application::isReadonly()) {
                    list($pkv, $idType) = explode('-', $index);
            ?>
                    <!-- Record Actions -->
                    <div class="flex items-center gap-3 mb-2">
                        <input type="checkbox"
                            value="<?php echo $pkv . '-' . $idType; ?>"
                            class="checkbox-remove rounded border-gray-300 focus:ring-green-500" />

                        <!-- Edit -->
                        <a href="javascript:void(0)"
                            onclick="callAjax('<?php echo Theme::URL('Collection/EditRecord', [
                                                    'db' => $this->db,
                                                    'collection' => $this->collection,
                                                    'id' => $pkv,
                                                    'format' => ($format == 'document' ? 'json' : $format),
                                                    'id_type' => $idType
                                                ]); ?>')"
                            class="flex items-center gap-1 text-green-600 hover:text-green-700 text-sm font-medium"
                            title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536M9 11l6.586-6.586a2 2 0 112.828 2.828L11.828 13.828H9v-2.828z" />
                            </svg>
                            Edit
                        </a>

                        <!-- Delete -->
                        <a href="<?php echo Theme::URL('Collection/DeleteRecord', [
                                        'db' => $this->db,
                                        'collection' => $this->collection,
                                        'id' => $pkv,
                                        'id_type' => $idType
                                    ]); ?>"
                            class="flex items-center gap-1 text-red-600 hover:text-red-700 text-sm font-medium"
                            title="Delete">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4h6v3m-7 0h8" />
                            </svg>
                            Delete
                        </a>
                    </div>
                <?php } ?>

                <!-- Record Data -->
                <pre class="bg-gray-900 text-green-100 border border-gray-700 rounded p-3 mb-4 text-sm overflow-x-auto">
<?php
                if ($format == 'document') {
                    echo htmlentities(print_r($cursor, true), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                } else {
                    print_r($cursor);
                }
?>
                </pre>
            <?php } ?>
        </div>
    <?php } ?>

    <!-- Empty State -->
    <?php if (empty($this->data['total'])) { ?>
        <p class="text-gray-500 text-sm"><?php I18n::p('N_R_F'); ?></p>
    <?php } ?>
</div>

<!-- Pagination -->
<div class="mt-4">
    <?php Theme::pagination($this->data['total']); ?>
</div>