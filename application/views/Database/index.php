<!-- Page Header -->
<div class="bg-green-700 text-white py-4 px-6 mb-4 flex items-center justify-between rounded">
    <h1 class="text-xl font-semibold flex items-center gap-2">
        <!-- Database Icon -->
        <svg xmlns="http://www.w3.org/2000/svg"
            class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24"
            stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 3c-4.97 0-9 1.79-9 4v10c0 2.21 4.03 4 9 4s9-1.79 9-4V7c0-2.21-4.03-4-9-4z" />
        </svg>
        <?php I18n::p('DB'); ?>
    </h1>

    <!-- Small reference -->
    <small class="text-sm text-green-100 hidden sm:inline font-mono">
        show databases
    </small>
</div>

<div class="flex flex-wrap gap-6">
    <!-- Database List Block -->
    <div class="w-full md:w-[48%] bg-white rounded shadow border border-gray-200" x-data="{ open: true }">

        <!-- Block Heading -->
        <button @click="open = !open"
            class="w-full flex justify-between items-center px-4 py-2 font-semibold text-gray-800 border-b bg-gray-50">
            <span><?php I18n::p('DB'); ?></span>
            <svg :class="{ 'rotate-180': open }"
                class="w-4 h-4 transform transition-transform duration-300 text-gray-500"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <!-- Block Body -->
        <div x-show="open" class="p-4">
            <table class="w-full text-sm border border-gray-200 rounded">
                <thead class="bg-gray-100 text-gray-700 font-medium">
                    <tr>
                        <th class="px-3 py-2 text-left"><?php I18n::p('NAME'); ?></th>
                        <th class="px-3 py-2 text-left"><?php I18n::p('S_O_D'); ?></th>
                        <th class="px-3 py-2 text-center"><?php I18n::p('ACTIONS'); ?></th>
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
                                    <a href="<?php echo Theme::URL('Collection/Index', ['db' => $db['name']]); ?>"
                                        class="text-green-700 hover:text-green-800 font-medium">
                                        <?php echo $db['name']; ?>
                                    </a>
                                </td>

                                <!-- Size on Disk -->
                                <td class="px-3 py-2 text-gray-600">
                                    <?php echo $db['sizeOnDisk']; ?>
                                </td>

                                <!-- Actions -->
                                <?php if (!Application::isReadonly()) { ?>
                                    <td class="px-3 py-2 text-center flex items-center justify-center gap-2">
                                        <!-- Edit -->
                                        <button @click="$dispatch('open-modal', { type: 'edit-db', db: '<?php echo $db['name']; ?>' })"
                                            class="inline-flex items-center gap-1 px-2 py-1 border border-gray-300 rounded hover:bg-gray-100 text-gray-600"
                                            title="Edit Database">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L7.5 21H3v-4.5L16.732 3.732z" />
                                            </svg>
                                            <span class="hidden sm:inline">Rename</span>
                                        </button>

                                        <!-- Delete -->
                                        <button @click="$dispatch('open-modal', { type: 'delete-db', db: '<?php echo $db['name']; ?>' })"
                                            class="inline-flex items-center gap-1 px-2 py-1 border border-red-300 text-red-600 rounded hover:bg-red-50"
                                            title="Delete Database">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            <span class="hidden sm:inline">Delete</span>
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