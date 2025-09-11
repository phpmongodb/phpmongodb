<!-- Page Header -->
<div class="bg-gray-100 border-b border-gray-300 py-4 px-6 mb-4">
    <h1 class="text-xl font-semibold text-gray-800"><?php I18n::p('DB'); ?></h1>
</div>

<div class="flex flex-wrap gap-6">
    <!-- Database List Block -->
    <div class="w-full md:w-[48%] bg-white rounded shadow border border-gray-200" x-data="{ open: true }">

        <!-- Block Heading -->
        <button @click="open = !open" class="w-full flex justify-between items-center px-4 py-2 font-semibold text-gray-700 border-b">
            <span><?php I18n::p('DB'); ?></span>
            <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <!-- Block Body -->
        <div x-show="open" class="p-4">
            <table class="w-full table-auto border border-gray-200 text-sm">
                <thead class="bg-gray-50 text-gray-700 font-medium">
                    <tr>
                        <th class="px-3 py-2 text-left"><?php I18n::p('NAME'); ?></th>
                        <th class="px-3 py-2 text-left"><?php I18n::p('S_O_D'); ?></th>
                        <th class="px-3 py-2 text-center">&nbsp;</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php
                    if (isset($this->data['databases']) && is_array($this->data['databases'])) {
                        foreach ($this->data['databases'] as $db) {
                    ?>
                            <tr class="hover:bg-gray-50">
                                <!-- Database name -->
                                <td class="px-3 py-2">
                                    <a href="<?php echo Theme::URL('Collection/Index', ['db' => $db['name']]); ?>" class="text-green-700 hover:underline">
                                        <?php echo $db['name']; ?>
                                    </a>
                                </td>

                                <!-- Size on Disk -->
                                <td class="px-3 py-2 text-gray-600">
                                    <?php echo $db['sizeOnDisk']; ?>
                                </td>

                                <!-- Actions -->
                                <?php if (!Application::isReadonly()) { ?>
                                    <td class="px-3 py-2 text-center flex items-center justify-center gap-3">
                                        <!-- Edit -->
                                        <button
                                            @click="$dispatch('open-modal', { type: 'edit-db', db: '<?php echo $db['name']; ?>' })"
                                            class="text-blue-600 hover:text-blue-800"
                                            title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L7.5 21H3v-4.5L16.732 3.732z" />
                                            </svg>
                                        </button>

                                        <!-- Delete -->
                                        <button
                                            @click="$dispatch('open-modal', { type: 'delete-db', db: '<?php echo $db['name']; ?>' })"
                                            class="text-red-600 hover:text-red-800"
                                            title="Remove">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </td>
                                <?php } ?>
                            </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create DB Form -->
    <?php if (!Application::isReadonly()) require_once '_create.php'; ?>
</div>

<?php if (!Application::isReadonly()) require_once '_form.php'; ?>