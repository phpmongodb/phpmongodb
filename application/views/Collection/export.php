<?php require_once '_menu.php'; ?>

<?php if ($this->data['record']) { ?>
    <!-- Exported Data -->
    <div class="bg-white shadow rounded p-4">
        <a href="<?php echo Theme::URL('Collection/Export', ['db' => $this->db, 'collection' => $this->collection]); ?>"
            class="text-blue-600 hover:underline text-sm mb-3 inline-block">
            <?php I18n::p('BACK'); ?>
        </a>
        <textarea rows="10"
            class="w-full border rounded p-2 font-mono text-sm text-gray-700 bg-gray-50"
            readonly><?php echo $this->data['record']; ?></textarea>
    </div>

<?php } else { ?>

    <form method="post" action="index.php" x-data="{
    quickOrCustom: 'quick',
    allOrSome: 'all',
    textOrSave: 'save',
}">
        <div class="space-y-6">

            <!-- Export Method -->
            <div class="bg-white rounded shadow border">
                <div class="px-4 py-2 font-semibold border-b bg-gray-50">
                    <?php I18n::p('E_M'); ?>
                </div>
                <div class="p-4 space-y-2">
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="radio" value="quick" x-model="quickOrCustom" class="text-green-600">
                        <?php I18n::p('Q_D_O_T_M_O'); ?>
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="radio" value="custom" x-model="quickOrCustom" class="text-green-600">
                        <?php I18n::p('C_D_A_P_O'); ?>
                    </label>
                </div>
            </div>

            <!-- Rows -->
            <div class="bg-white rounded shadow border" x-show="quickOrCustom === 'custom'">
                <div class="px-4 py-2 font-semibold border-b bg-gray-50">
                    <?php I18n::p('ROWS'); ?>
                </div>
                <div class="p-4 space-y-3">
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="radio" value="all" x-model="allOrSome" class="text-green-600">
                        <?php I18n::p('D_A_R'); ?>
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="radio" value="custom" x-model="allOrSome" class="text-green-600">
                        <?php I18n::p('D_S_R'); ?>
                    </label>

                    <!-- Custom Row Options -->
                    <div class="space-y-2 ml-6" x-show="allOrSome === 'custom'">
                        <div class="flex items-center gap-3 text-sm">
                            <span><?php I18n::p('N_O_R'); ?></span>
                            <input type="text" name="limit" id="limit_to_export"
                                class="border rounded px-2 py-1 w-20 text-sm">
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <span><?php I18n::p('R_T_B_A'); ?></span>
                            <input type="text" name="skip" id="limit_from_export"
                                class="border rounded px-2 py-1 w-20 text-sm">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Output -->
            <div class="bg-white rounded shadow border" x-show="quickOrCustom === 'custom'">
                <div class="px-4 py-2 font-semibold border-b bg-gray-50">
                    <?php I18n::p('OUTPUT'); ?>
                </div>
                <div class="p-4 space-y-3">
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="radio" value="save" x-model="textOrSave" class="text-green-600">
                        <?php I18n::p('S_O_T_A_F'); ?>
                    </label>

                    <!-- File Options -->
                    <div class="space-y-2 ml-6" x-show="textOrSave === 'save'">
                        <div class="flex items-center gap-3 text-sm">
                            <span><?php I18n::p('F_N'); ?></span>
                            <input type="text" name="file_name" id="file_name_export"
                                value="<?php echo $this->collection; ?>"
                                class="border rounded px-2 py-1 text-sm w-48">
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <span><?php I18n::p('COMPRESSED'); ?></span>
                            <select name="compression" id="compression_export"
                                class="border rounded px-2 py-1 text-sm">
                                <option value="none"><?php I18n::p('None'); ?></option>
                                <option value="zip">zipped</option>
                                <option value="gzip">gzipped</option>
                                <option value="bzip2">bzipped</option>
                            </select>
                        </div>
                    </div>

                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="radio" value="text" x-model="textOrSave" class="text-green-600">
                        <?php I18n::p('V_O_A_T'); ?>
                    </label>
                </div>
            </div>

            <!-- Data Dump Options -->
            <div class="bg-white rounded shadow border" x-show="quickOrCustom === 'custom'">
                <div class="px-4 py-2 font-semibold border-b bg-gray-50">
                    <?php I18n::p('D_D_O'); ?>
                </div>
                <div class="p-4 space-y-2">
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="radio" value="json" name="json" checked class="text-green-600">
                        <?php I18n::p('JSON'); ?>
                    </label>
                </div>
            </div>

            <!-- Hidden Inputs -->
            <input type="hidden" name="db" value="<?php echo $this->db; ?>" />
            <input type="hidden" name="collection" value="<?php echo $this->collection; ?>" />
            <input type="hidden" name="load" value="Collection/Export" />

            <!-- Submit -->
            <button type="submit"
                class="px-4 py-2 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                <?php I18n::p('EXPORT'); ?>
            </button>
        </div>
    </form>

<?php } ?>