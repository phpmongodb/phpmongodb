<?php require_once '_menu.php'; ?>

<div class="bg-white shadow border rounded" x-data="{ tab: 'list' }" id="container-indexes">
    <!-- Tabs -->
    <div class="border-b flex">
        <!-- List Tab -->
        <button @click="tab = 'list'"
            :class="tab === 'list' ? 'border-b-2 border-green-600 text-green-700' : 'text-gray-600 hover:text-gray-800'"
            class="px-4 py-2 text-sm font-medium focus:outline-none">
            <?php I18n::p('LIST'); ?>
        </button>

        <!-- Create Tab -->
        <?php if (!Application::isReadonly()) { ?>
            <button @click="tab = 'create'"
                :class="tab === 'create' ? 'border-b-2 border-green-600 text-green-700' : 'text-gray-600 hover:text-gray-800'"
                class="px-4 py-2 text-sm font-medium focus:outline-none">
                <?php I18n::p('CREATE'); ?>
            </button>
        <?php } ?>
    </div>

    <!-- Tab Content -->
    <div class="p-4">
        <!-- Index List -->
        <div x-show="tab === 'list'" x-cloak>
            <?php
            if (isset($this->data['indexes']) && is_array($this->data['indexes'])) {
                foreach ($this->data['indexes'] as $index) {
            ?>
                    <div class="mb-6 bg-white shadow rounded-lg overflow-hidden border border-gray-200">
                        <!-- Card Header -->
                        <div class="bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-800 border-b">
                            📑 Index Details
                        </div>

                        <!-- Table -->
                        <table class="w-full text-sm">
                            <tbody class="divide-y divide-gray-200">
                                <?php
                                foreach ($index as $key => $value) {
                                    echo '<tr class="hover:bg-gray-50">';
                                    echo '<td class="px-4 py-2 w-1/4 bg-gray-50 font-medium text-gray-700">' . htmlspecialchars($key) . '</td>';
                                    echo '<td class="px-4 py-2">';
                                    if (is_array($value)) {
                                        echo '<pre class="bg-gray-900 text-green-300 p-3 rounded text-xs overflow-x-auto border border-gray-700">' .
                                            $this->data['formatter']->highlight($this->data['formatter']->arrayToJSON($value)) .
                                            '</pre>';
                                    } else {
                                        echo '<span class="text-gray-800">' . htmlspecialchars((string)$value) . '</span>';
                                    }
                                    echo '</td>';
                                    echo '</tr>';
                                }

                                // Action Row
                                if (!Application::isReadonly() && isset($index['name']) && $index['name'] !== '_id_') {
                                    echo '<tr>';
                                    echo '<td class="px-4 py-2 bg-gray-50 font-medium text-gray-700">Action</td>';
                                    echo '<td class="px-4 py-2">';
                                    echo '<a href="' . Theme::URL('Collection/DeleteIndexes', array('db' => $this->db, 'collection' => $this->collection, 'name' => $index['name'])) . '" ';
                                    echo 'class="inline-block px-3 py-1 text-red-600 border border-red-600 rounded hover:bg-red-600 hover:text-white transition">';
                                    echo 'Delete</a>';
                                    echo '</td>';
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
            <?php
                }
            } else {
                echo '<p class="text-gray-600 text-sm">No indexes found.</p>';
            }
            ?>
        </div>

        <!-- Create Index -->
        <?php if (!Application::isReadonly()) { ?>
            <div x-show="tab === 'create'" x-cloak>
                <?php require_once '_create_index.php'; ?>
            </div>
        <?php } ?>
    </div>
</div>