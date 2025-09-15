<div x-show="tab === 'create'" x-cloak x-data="indexForm()">
    <form method="post" action="index.php" class="space-y-6" @submit.prevent="validateForm($event)">
        <!-- Index Name -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1"><?php I18n::p('NAME'); ?></label>
            <input type="text" name="name" x-model="indexName" required
                pattern="^[a-zA-Z0-9_-]+$"
                title="Only letters, numbers, underscores (_) and hyphens (-) are allowed"
                class="w-full border rounded px-3 py-2 text-sm focus:ring focus:ring-green-200 focus:border-green-400" />
            <p x-show="indexName && !/^[a-zA-Z0-9_-]+$/.test(indexName)"
                class="text-red-600 text-xs mt-1">
                Index name may only contain letters, numbers, underscores (_) or hyphens (-).
            </p>
        </div>

        <!-- Fields -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2"><?php I18n::p('FIELDS'); ?></label>
            <template x-for="(field, index) in fields" :key="index">
                <div class="flex gap-3 mb-2 items-center">
                    <input type="text" :name="'fields[]'" x-model="field.name" required
                        pattern="^[a-zA-Z0-9_-]+$"
                        placeholder="Field name"
                        class="flex-1 border rounded px-3 py-2 text-sm focus:ring focus:ring-green-200 focus:border-green-400" />
                    <select :name="'orders[]'" x-model="field.order"
                        class="border rounded px-2 py-2 text-sm focus:ring focus:ring-green-200 focus:border-green-400">
                        <option value="1">ASC</option>
                        <option value="-1">DESC</option>
                    </select>
                    <!-- Add / Remove buttons -->
                    <button type="button" @click="fields.push({name:'', order:'1'})"
                        class="text-green-600 hover:text-green-800" title="Add Field">➕</button>
                    <button type="button" @click="fields.splice(index,1)" x-show="fields.length > 1"
                        class="text-red-600 hover:text-red-800" title="Remove Field">➖</button>
                </div>
            </template>
        </div>

        <!-- TTL + Checkboxes Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
            <!-- TTL Input -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1"><?php I18n::p('TTL'); ?></label>
                <input type="number" name="expireAfterSeconds" x-model="ttl" min="1"
                    placeholder="e.g. 3600"
                    class="w-full border rounded px-3 py-2 text-sm focus:ring focus:ring-green-200 focus:border-green-400" />
                <p x-show="ttl !== '' && ttl <= 0" class="text-red-600 text-xs mt-1">
                    TTL must be a positive number.
                </p>
            </div>

            <!-- Checkboxes -->
            <div class="flex flex-wrap gap-4">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="unique" value="1" x-model="unique"
                        class="rounded border-gray-300 text-green-600 focus:ring-green-500" />
                    <?php I18n::p('UNIQUE'); ?>
                </label>

                <label class="flex items-center gap-2">
                    <input type="checkbox" name="sparse" value="1"
                        class="rounded border-gray-300 text-green-600 focus:ring-green-500" />
                    <?php I18n::p('SPARSE'); ?>
                </label>

                <label class="flex items-center gap-2">
                    <input type="checkbox" name="hidden" value="1"
                        class="rounded border-gray-300 text-green-600 focus:ring-green-500" />
                    <?php I18n::p('HIDDEN'); ?>
                </label>

                <label class="flex items-center gap-2">
                    <input type="checkbox" name="background" value="1"
                        class="rounded border-gray-300 text-green-600 focus:ring-green-500" />
                    <?php I18n::p('BACKGROUND'); ?>
                </label>
            </div>
        </div>

        <!-- Drop Duplicates (toggle when Unique is checked) -->
        <div x-show="unique" x-cloak>
            <label class="flex items-center gap-2">
                <input type="checkbox" name="drop" value="1"
                    class="rounded border-gray-300 text-green-600 focus:ring-green-500" />
                <?php I18n::p('DROP_DUPLICATES'); ?>
            </label>
        </div>

        <!-- Partial Filter Expression -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1"><?php I18n::p('PARTIAL_FILTER_EXPRESSION'); ?></label>
            <input type="text" name="partialFilterExpression" x-model="partialFilter"
                placeholder='e.g. {"status": "active"}'
                class="w-full border rounded px-3 py-2 text-sm focus:ring focus:ring-green-200 focus:border-green-400" />
            <p x-show="partialFilter && !isValidJson(partialFilter)" class="text-red-600 text-xs mt-1">
                Must be valid JSON.
            </p>
        </div>

        <!-- Collation -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1"><?php I18n::p('COLLATION'); ?></label>
            <input type="text" name="collation" x-model="collation"
                placeholder='e.g. {"locale": "en", "strength": 2}'
                class="w-full border rounded px-3 py-2 text-sm focus:ring focus:ring-green-200 focus:border-green-400" />
            <p x-show="collation && !isValidJson(collation)" class="text-red-600 text-xs mt-1">
                Must be valid JSON.
            </p>
        </div>

        <!-- Submit -->
        <div>
            <button type="submit"
                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                <?php I18n::p('CREATE'); ?>
            </button>
        </div>

        <!-- Hidden fields -->
        <input type="hidden" name="load" value="Collection/CreateIndexes" />
        <input type="hidden" name="db" value="<?php echo $this->db; ?>" />
        <input type="hidden" name="collection" value="<?php echo $this->collection; ?>" />
    </form>
</div>

<script>
    function indexForm() {
        return {
            indexName: '',
            fields: [{
                name: '',
                order: '1'
            }],
            ttl: '',
            unique: false,
            partialFilter: '',
            collation: '',
            validateForm(e) {
                // Index name validation
                if (!/^[a-zA-Z0-9_-]+$/.test(this.indexName)) {
                    alert("Invalid index name: only letters, numbers, underscores (_) and hyphens (-) allowed.");
                    return false;
                }
                // Fields validation
                for (let f of this.fields) {
                    if (!/^[a-zA-Z0-9_-]+$/.test(f.name)) {
                        alert("Invalid field name: " + f.name);
                        return false;
                    }
                }
                // TTL validation
                if (this.ttl !== '' && this.ttl <= 0) {
                    alert("TTL must be a positive number.");
                    return false;
                }
                // JSON validation
                if (this.partialFilter && !this.isValidJson(this.partialFilter)) {
                    alert("Partial Filter Expression must be valid JSON.");
                    return false;
                }
                if (this.collation && !this.isValidJson(this.collation)) {
                    alert("Collation must be valid JSON.");
                    return false;
                }

                // ✅ Agar sab pass ho gaya to form submit
                e.target.submit();
            },
            isValidJson(str) {
                try {
                    JSON.parse(str);
                    return true;
                } catch {
                    return false;
                }
            }
        }
    }
</script>