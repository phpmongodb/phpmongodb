<div class="bg-gray-100 border-b border-gray-300 py-4 px-6 mb-4">
    <h1 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
        <?php echo $this->db; ?>
    </h1>
</div>

<div class="flex flex-wrap gap-6">
    <!-- Collections List -->
    <div class="w-full md:w-[48%] bg-white rounded shadow border border-gray-200" x-data="{ open: true }">
        <!-- Heading -->
        <button @click="open = !open"
            class="w-full flex justify-between items-center px-4 py-2 font-semibold text-gray-700 border-b">
            <span><?php I18n::p('COLLECTION'); ?></span>
            <svg :class="{ 'rotate-180': open }"
                class="w-4 h-4 transform transition-transform duration-300"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <!-- Table -->
        <div x-show="open" class="p-4">
            <table class="w-full table-auto border border-gray-200 text-sm">
                <thead class="bg-gray-50 text-gray-700 font-medium">
                    <tr>
                        <th class="px-3 py-2 text-left"><?php I18n::p('NAME'); ?></th>
                        <th class="px-3 py-2 text-left"><?php I18n::p('T_C'); ?></th>
                        <th class="px-3 py-2 text-center">&nbsp;</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach ($this->data['collectionList'] as $collection) { ?>
                        <tr class="hover:bg-gray-50">
                            <!-- Name -->
                            <td class="px-3 py-2">
                                <a href="<?php echo Theme::URL('Collection/Record', ['db' => $this->db, 'collection' => $collection['name']]); ?>"
                                    class="text-green-700 hover:underline">
                                    <?php echo $collection['name']; ?>
                                </a>
                            </td>

                            <!-- Count -->
                            <td class="px-3 py-2 text-gray-600">
                                <?php echo $collection['count']; ?>
                            </td>

                            <!-- Actions -->
                            <?php if (!Application::isReadonly()) { ?>
                                <td class="px-3 py-2 text-center flex items-center justify-center gap-3">
                                    <!-- Edit -->
                                    <button
                                        @click="$dispatch('open-modal', { type: 'edit-collection', collection: '<?php echo $collection['name']; ?>' })"
                                        class="text-blue-600 hover:text-blue-800"
                                        title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L7.5 21H3v-4.5L16.732 3.732z" />
                                        </svg>
                                    </button>

                                    <!-- Delete -->
                                    <button
                                        @click="$dispatch('open-modal', { type: 'delete-collection', collection: '<?php echo $collection['name']; ?>' })"
                                        class="text-red-600 hover:text-red-800"
                                        title="Remove">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Collection -->
    <?php if (!Application::isReadonly()) require_once '_create.php'; ?>
</div>

<?php if (!Application::isReadonly()) require_once '_form.php'; ?>