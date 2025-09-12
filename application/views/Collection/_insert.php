<div
    x-data="{ activeTab: 'keyValue' }"
    id="container-insert"
    class="p-4 bg-white border rounded shadow"
    style="<?php echo isset($this->data['isAjax']) ? 'display:block' : 'display:none'; ?>">
    <!-- Tabs -->
    <div class="border-b mb-4">
        <nav class="flex space-x-4 text-sm">
            <button
                @click="activeTab = 'keyValue'"
                :class="activeTab === 'keyValue' ? 'text-green-700 border-b-2 border-green-700' : 'text-gray-600 hover:text-gray-800'"
                class="pb-2 font-medium">
                <?php I18n::p('F_V'); ?>
            </button>
            <button
                @click="activeTab = 'array'"
                :class="activeTab === 'array' ? 'text-green-700 border-b-2 border-green-700' : 'text-gray-600 hover:text-gray-800'"
                class="pb-2 font-medium">
                <?php I18n::p('Array'); ?>
            </button>
            <button
                @click="activeTab = 'json'"
                :class="activeTab === 'json' ? 'text-green-700 border-b-2 border-green-700' : 'text-gray-600 hover:text-gray-800'"
                class="pb-2 font-medium">
                <?php I18n::p('JSON'); ?>
            </button>
        </nav>
    </div>

    <!-- Tab Content -->
    <div>
        <!-- Key Value Form -->
        <div x-show="activeTab === 'keyValue'" class="space-y-4">
            <form method="post" action="index.php" class="space-y-4">
                <table id="tbl-fiedl-value" class="w-full border text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left"><?php I18n::p('ATTRIBUTE'); ?></th>
                            <th class="px-3 py-2 text-left"><?php I18n::p('VALUE'); ?></th>
                            <th class="px-3 py-2 text-center">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border px-2 py-1">
                                <input type="text" name="fields[]" required placeholder="Enter Key"
                                    class="w-full px-2 py-1 border rounded focus:ring-2 focus:ring-green-500">
                            </td>
                            <td class="border px-2 py-1">
                                <textarea name="values[]" rows="2" required placeholder="Enter Value"
                                    class="w-full px-2 py-1 border rounded focus:ring-2 focus:ring-green-500"></textarea>
                            </td>
                            <td class="border px-2 py-1 text-center">
                                <button type="button" onclick="PMDI.appendTR('insert')"
                                    class="text-green-600 hover:text-green-800" title="<?php I18n::p('ADD'); ?>">
                                    ➕
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div>
                    <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        <?php I18n::p('SAVE'); ?>
                    </button>
                </div>
                <input type="hidden" name="load" value="Collection/SaveRecord" />
                <input type="hidden" name="type" value="FieldValue" />
                <input type="hidden" name="db" value="<?php echo $this->db; ?>" />
                <input type="hidden" name="collection" value="<?php echo $this->collection; ?>" />
            </form>
        </div>

        <!-- Array Form -->
        <div x-show="activeTab === 'array'" class="space-y-4">
            <form method="post" action="index.php" class="space-y-4">
                <textarea name="data" rows="16"
                    class="w-full border rounded p-2 focus:ring-2 focus:ring-green-500">array (
)</textarea>
                <div>
                    <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        <?php I18n::p('SAVE'); ?>
                    </button>
                </div>
                <input type="hidden" name="load" value="Collection/SaveRecord" />
                <input type="hidden" name="type" value="Array" />
                <input type="hidden" name="db" value="<?php echo $this->db; ?>" />
                <input type="hidden" name="collection" value="<?php echo $this->collection; ?>" />
            </form>
        </div>

        <!-- JSON Form -->
        <div x-show="activeTab === 'json'" class="space-y-4">
            <form method="post" action="index.php" class="space-y-4">
                <textarea name="data" rows="16"
                    class="w-full border rounded p-2 focus:ring-2 focus:ring-green-500">{
  
}</textarea>
                <div>
                    <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        <?php I18n::p('SAVE'); ?>
                    </button>
                </div>
                <input type="hidden" name="load" value="Collection/SaveRecord" />
                <input type="hidden" name="type" value="JSON" />
                <input type="hidden" name="db" value="<?php echo $this->db; ?>" />
                <input type="hidden" name="collection" value="<?php echo $this->collection; ?>" />
            </form>
        </div>
    </div>
</div>