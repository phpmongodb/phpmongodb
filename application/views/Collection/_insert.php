<!-- Insert Record Section -->
<div
    class="bg-white shadow rounded p-4 mb-6"
    id="container-insert"
    style="<?php echo isset($this->data['isAjax']) ? 'display:block' : 'display:none'; ?>"
    x-data="{ activeTab: 'fieldValue', rows: [{ key: '', value: '' }] }">
    <!-- Tabs -->
    <div class="border-b mb-4 flex space-x-6 text-sm font-medium">
        <button
            @click="activeTab='fieldValue'"
            :class="activeTab==='fieldValue' ? 'border-b-2 border-green-600 text-green-700' : 'text-gray-600'"
            class="pb-2">
            <?php I18n::p('F_V'); ?>
        </button>
        <button
            @click="activeTab='array'"
            :class="activeTab==='array' ? 'border-b-2 border-green-600 text-green-700' : 'text-gray-600'"
            class="pb-2">
            <?php I18n::p('Array'); ?>
        </button>
        <button
            @click="activeTab='json'"
            :class="activeTab==='json' ? 'border-b-2 border-green-600 text-green-700' : 'text-gray-600'"
            class="pb-2">
            <?php I18n::p('JSON'); ?>
        </button>
    </div>

    <!-- Field Value Tab -->
    <div x-show="activeTab==='fieldValue'" class="space-y-4">
        <form method="post" action="index.php" class="space-y-3">
            <table class="w-full text-sm border border-gray-200 rounded">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left"><?php I18n::p('FIELD'); ?></th>
                        <th class="px-3 py-2 text-left"><?php I18n::p('VALUE'); ?></th>
                        <th class="px-3 py-2 text-center">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(row, index) in rows" :key="index">
                        <tr class="border-t">
                            <td class="px-3 py-2">
                                <input type="text" x-model="row.key" name="fields[]" placeholder="Enter Key"
                                    class="w-full border rounded px-2 py-1 focus:ring-2 focus:ring-green-500" required>
                            </td>
                            <td class="px-3 py-2">
                                <textarea x-model="row.value" name="values[]" placeholder="Enter Value"
                                    class="w-full border rounded px-2 py-1 focus:ring-2 focus:ring-green-500" rows="1" required></textarea>
                            </td>
                            <td class="px-3 py-2 text-center">
                                <button type="button"
                                    @click="rows.push({key:'', value:''})"
                                    class="text-green-600 hover:text-green-800"
                                    title="Add">
                                    +
                                </button>
                                <button type="button"
                                    @click="rows.splice(index,1)"
                                    x-show="rows.length > 1"
                                    class="text-red-600 hover:text-red-800 ml-2"
                                    title="Remove">
                                    −
                                </button>
                            </td>
                        </tr>
                    </template>
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

    <!-- Array Tab -->
    <div x-show="activeTab==='array'">
        <form method="post" action="index.php" class="space-y-3">
            <textarea name="data" rows="16" class="w-full border rounded px-2 py-1 focus:ring-2 focus:ring-green-500">array (
)</textarea>
            <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                <?php I18n::p('SAVE'); ?>
            </button>
            <input type="hidden" name="load" value="Collection/SaveRecord" />
            <input type="hidden" name="type" value="Array" />
            <input type="hidden" name="db" value="<?php echo $this->db; ?>" />
            <input type="hidden" name="collection" value="<?php echo $this->collection; ?>" />
        </form>
    </div>

    <!-- JSON Tab -->
    <div x-show="activeTab==='json'">
        <form method="post" action="index.php" class="space-y-3">
            <textarea name="data" rows="16" class="w-full border rounded px-2 py-1 focus:ring-2 focus:ring-green-500">{
  
}</textarea>
            <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                <?php I18n::p('SAVE'); ?>
            </button>
            <input type="hidden" name="load" value="Collection/SaveRecord" />
            <input type="hidden" name="type" value="JSON" />
            <input type="hidden" name="db" value="<?php echo $this->db; ?>" />
            <input type="hidden" name="collection" value="<?php echo $this->collection; ?>" />
        </form>
    </div>
</div>