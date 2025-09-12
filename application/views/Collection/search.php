<?php require_once '_menu.php'; ?>

<div class="bg-white border rounded shadow p-4"
    x-data="{ tab: 'field', jsonError: '' }">

    <!-- Tabs -->
    <div class="border-b mb-4">
        <nav class="flex gap-4 text-sm">
            <button type="button" @click="tab='field'"
                :class="tab==='field' ? 'border-b-2 border-green-600 text-green-700 font-medium' : 'text-gray-600 hover:text-gray-800'"
                class="pb-2"><?php I18n::p('F_V'); ?></button>
            <button type="button" @click="tab='array'"
                :class="tab==='array' ? 'border-b-2 border-green-600 text-green-700 font-medium' : 'text-gray-600 hover:text-gray-800'"
                class="pb-2"><?php I18n::p('Array'); ?></button>
            <button type="button" @click="tab='json'"
                :class="tab==='json' ? 'border-b-2 border-green-600 text-green-700 font-medium' : 'text-gray-600 hover:text-gray-800'"
                class="pb-2"><?php I18n::p('JSON'); ?></button>
        </nav>
    </div>

    <!-- Key Value Search -->
    <div x-show="tab==='field'" x-cloak>
        <form method="get" action="index.php" class="space-y-6">
            <!-- Field conditions -->
            <div x-data="{ rows: [{field:'', operator:'=', value:''}] }">
                <table class="w-full text-sm border border-gray-200">
                    <thead class="bg-gray-50 text-gray-700">
                        <tr>
                            <th class="px-2 py-1">Field</th>
                            <th class="px-2 py-1">Operator</th>
                            <th class="px-2 py-1">Value</th>
                            <th class="px-2 py-1">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(row, index) in rows" :key="index">
                            <tr class="border-t">
                                <!-- Field validation -->
                                <td class="px-2 py-1">
                                    <input type="text" :name="'query[]'" x-model="row.field"
                                        placeholder="Enter Attribute" required
                                        pattern="^[A-Za-z0-9_]+$"
                                        title="Only letters, numbers, and underscore allowed"
                                        class="w-full border rounded px-2 py-1 text-sm focus:ring-green-200 focus:border-green-400" />
                                </td>

                                <td class="px-2 py-1">
                                    <select :name="'query[]'" x-model="row.operator"
                                        class="w-full border rounded px-2 py-1 text-sm">
                                        <option value="=">=</option>
                                        <option value="$gt">&gt;</option>
                                        <option value="$gte">&gt;=</option>
                                        <option value="$lt">&lt;</option>
                                        <option value="$lte">&lt;=</option>
                                        <option value="$ne">!=</option>
                                    </select>
                                </td>

                                <!-- Value required -->
                                <td class="px-2 py-1">
                                    <input type="text" :name="'query[]'" x-model="row.value"
                                        placeholder="Enter Value" required
                                        class="w-full border rounded px-2 py-1 text-sm focus:ring-green-200 focus:border-green-400" />
                                </td>

                                <!-- Add/Remove -->
                                <td class="px-2 py-1 text-center">
                                    <button type="button" @click="rows.push({field:'',operator:'=',value:''})"
                                        class="text-green-600 hover:text-green-800" title="Add">➕</button>
                                    <button type="button" @click="rows.splice(index,1)" x-show="rows.length>1"
                                        class="text-red-600 hover:text-red-800" title="Remove">➖</button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Order By -->
            <div x-data="{ order: [{field:'_id', dir:'1'}] }">
                <table class="w-full text-sm border border-gray-200">
                    <thead class="bg-gray-50 text-gray-700">
                        <tr>
                            <th class="px-2 py-1">Order By</th>
                            <th class="px-2 py-1">Order</th>
                            <th class="px-2 py-1">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(o, index) in order" :key="index">
                            <tr class="border-t">
                                <td class="px-2 py-1">
                                    <input type="text" :name="'order_by[]'" x-model="o.field"
                                        placeholder="Enter Attribute"
                                        pattern="^[A-Za-z0-9_]+$"
                                        title="Only letters, numbers, and underscore allowed"
                                        required
                                        class="w-full border rounded px-2 py-1 text-sm focus:ring-green-200 focus:border-green-400" />
                                </td>
                                <td class="px-2 py-1">
                                    <select :name="'orders[]'" x-model="o.dir"
                                        class="w-full border rounded px-2 py-1 text-sm">
                                        <option value="1">ASC</option>
                                        <option value="-1">DESC</option>
                                    </select>
                                </td>
                                <td class="px-2 py-1 text-center">
                                    <button type="button" @click="order.push({field:'',dir:'1'})"
                                        class="text-green-600 hover:text-green-800">➕</button>
                                    <button type="button" @click="order.splice(index,1)" x-show="order.length>1"
                                        class="text-red-600 hover:text-red-800">➖</button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div>
                <button class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                    <?php I18n::p('GO'); ?>
                </button>
            </div>

            <!-- Hidden fields -->
            <input type="hidden" name="load" value="Collection/Record" />
            <input type="hidden" name="db" value="<?php echo $this->db; ?>" />
            <input type="hidden" name="collection" value="<?php echo $this->collection; ?>" />
            <input type="hidden" name="search" value="1" />
            <input type="hidden" name="type" value="fieldvalue" />
        </form>
    </div>

    <!-- Array Search -->
    <div x-show="tab==='array'" x-cloak>
        <form method="get" action="index.php" class="space-y-4">
            <textarea name="query" rows="5" required
                class="w-full border rounded px-3 py-2 text-sm font-mono">array (
)</textarea>
            <div>
                <button class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                    <?php I18n::p('GO'); ?>
                </button>
            </div>
            <input type="hidden" name="load" value="Collection/Record" />
            <input type="hidden" name="db" value="<?php echo $this->db; ?>" />
            <input type="hidden" name="collection" value="<?php echo $this->collection; ?>" />
            <input type="hidden" name="search" value="1" />
            <input type="hidden" name="type" value="array" />
        </form>
    </div>

    <!-- JSON Search -->
    <div x-show="tab==='json'" x-cloak>
        <form method="get" action="index.php"
            @submit.prevent="
                try {
                    JSON.parse($el.querySelector('textarea[name=query]').value);
                    jsonError='';
                    $el.submit();
                } catch(e) {
                    jsonError='Invalid JSON format';
                }
              "
            class="space-y-4">
            <textarea name="query" rows="5" required
                class="w-full border rounded px-3 py-2 text-sm font-mono">{
  
}</textarea>
            <p x-show="jsonError" class="text-red-600 text-sm" x-text="jsonError"></p>

            <div>
                <button class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                    <?php I18n::p('GO'); ?>
                </button>
            </div>
            <input type="hidden" name="load" value="Collection/Record" />
            <input type="hidden" name="db" value="<?php echo $this->db; ?>" />
            <input type="hidden" name="collection" value="<?php echo $this->collection; ?>" />
            <input type="hidden" name="search" value="1" />
            <input type="hidden" name="type" value="json" />
        </form>
    </div>
</div>