<?php if (!Application::isReadonly() && !empty($this->data['total'])) { ?>
    <div class="flex items-center justify-between mb-3">
        <label class="flex items-center gap-2 text-sm text-gray-700">
            <input type="checkbox" id="check-all" class="rounded border-gray-300" x-data x-on:click="
                document.querySelectorAll('.checkbox-remove').forEach(cb => cb.checked = $el.checked)">
            Check All / Uncheck All
        </label>
        <button id="delete-all"
            class="text-red-600 text-sm hover:underline"
            onclick="document.getElementById('delete-form').submit();">
            Delete
        </button>
        <input type="hidden" id="db-hidden" value="<?php echo $this->db; ?>" />
        <input type="hidden" id="collection-hidden" value="<?php echo $this->collection; ?>" />
    </div>
<?php } ?>

<div class="bg-white rounded border shadow p-4" x-data="{ tab: 'json' }">
    <!-- Tabs -->
    <div class="border-b mb-3">
        <ul class="flex gap-4 text-sm">
            <li><a href="javascript:void(0)" @click="tab='json'" :class="tab==='json' ? 'text-blue-600 font-semibold border-b-2 border-blue-600 pb-1' : 'text-gray-600 hover:text-gray-800'">JSON</a></li>
            <li><a href="javascript:void(0)" @click="tab='array'" :class="tab==='array' ? 'text-blue-600 font-semibold border-b-2 border-blue-600 pb-1' : 'text-gray-600 hover:text-gray-800'">Array</a></li>
            <li><a href="javascript:void(0)" @click="tab='jsonv2'" :class="tab==='jsonv2' ? 'text-blue-600 font-semibold border-b-2 border-blue-600 pb-1' : 'text-gray-600 hover:text-gray-800'">MongoDB Extended JSON v2</a></li>
        </ul>
    </div>

    <!-- Records -->
    <?php foreach ($this->data['format'] as $format) {
        if (!isset($this->data['record'][$format])) continue; ?>
        <div x-show="tab === '<?php echo $format; ?>'">
            <?php
            foreach ($this->data['record'][$format] as $index => $cursor) {
                if (!Application::isReadonly()) {
                    list($pkv, $idType) = explode('-', $index);
            ?>
                    <div class="flex items-center gap-2 mb-2">
                        <input type="checkbox" value="<?php echo $pkv . '-' . $idType; ?>" class="checkbox-remove rounded border-gray-300">
                        <!-- Edit -->
                        <a href="javascript:void(0)" onclick="callAjax('<?php echo Theme::URL('Collection/EditRecord', ['db' => $this->db, 'collection' => $this->collection, 'id' => $pkv, 'format' => ($format == 'document' ? 'json' : $format), 'id_type' => $idType]); ?>')"
                            class="text-blue-600 hover:text-blue-800" title="Edit">
                            ✏️
                        </a>
                        <!-- Delete -->
                        <a href="<?php echo Theme::URL('Collection/DeleteRecord', ['db' => $this->db, 'collection' => $this->collection, 'id' => $pkv, 'id_type' => $idType]); ?>"
                            class="text-red-600 hover:text-red-800" title="Delete">
                            ❌
                        </a>
                    </div>
                <?php } ?>
                <pre class="bg-gray-50 border rounded p-3 mb-4 text-sm overflow-x-auto"><?php
                                                                                        if ($format == 'document') {
                                                                                            echo htmlentities(print_r($cursor, true), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                                                                                        } else {
                                                                                            print_r($cursor);
                                                                                        }
                                                                                        ?></pre>
            <?php } ?>
        </div>
    <?php } ?>

    <?php if (empty($this->data['total'])) { ?>
        <p class="text-gray-500 text-sm"><?php I18n::p('N_R_F'); ?></p>
    <?php } ?>
</div>

<!-- Pagination -->
<div class="mt-4">
    <?php Theme::pagination($this->data['total']); ?>
</div>