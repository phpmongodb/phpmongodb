<?php if (isset($this->data['error'])) { ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <strong>Note:</strong> <?php echo $this->data['error']; ?>
    </div>
<?php } ?>

<!-- Page Header -->
<div class="bg-gray-100 border-b border-gray-300 py-4 px-6 mb-4">
    <h1 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
        <a href="<?php echo Theme::URL('Collection/Index', ['db' => $this->db]); ?>"
            class="text-green-700 hover:underline">
            <?php echo $this->db; ?>
        </a>
        <span class="text-gray-500">/</span>
        <span class="text-gray-700"><?php echo $this->collection; ?></span>
    </h1>
</div>

<!-- Toolbar with Alpine state -->
<div class="flex flex-wrap gap-2 mb-4"
    x-data="{ activeTab: '<?php echo $this->application->view; ?>' }">

    <!-- Browse -->
    <a href="javascript:void(0)"
        @click="activeTab = 'record'; callAjax('<?php echo Theme::URL('Collection/Record', ['db' => $this->db, 'collection' => $this->collection]); ?>')"
        :class="activeTab === 'record' ? 'px-3 py-1 rounded border text-sm bg-green-600 text-white' : 'px-3 py-1 rounded border text-sm bg-blue-600 text-white hover:bg-blue-700'">
        <?php I18n::p('BROWSE'); ?>
    </a>

    <?php if (!Application::isReadonly()) { ?>
        <!-- Insert -->
        <a href="javascript:void(0)"
            @click="activeTab = 'insert'; callAjax('<?php echo Theme::URL('Collection/Insert', ['db' => $this->db, 'collection' => $this->collection]); ?>')"
            :class="activeTab === 'insert' ? 'px-3 py-1 rounded border text-sm bg-green-600 text-white' : 'px-3 py-1 rounded border text-sm bg-blue-600 text-white hover:bg-blue-700'">
            <?php I18n::p('INSERT'); ?>
        </a>
    <?php } ?>

    <!-- Export -->
    <a href="javascript:void(0)"
        @click="activeTab = 'export'; callAjax('<?php echo Theme::URL('Collection/Export', ['db' => $this->db, 'collection' => $this->collection]); ?>')"
        :class="activeTab === 'export' ? 'px-3 py-1 rounded border text-sm bg-green-600 text-white' : 'px-3 py-1 rounded border text-sm bg-blue-600 text-white hover:bg-blue-700'">
        <?php I18n::p('EXPORT'); ?>
    </a>

    <?php if (!Application::isReadonly()) { ?>
        <!-- Import -->
        <a href="javascript:void(0)"
            @click="activeTab = 'import'; callAjax('<?php echo Theme::URL('Collection/Import', ['db' => $this->db, 'collection' => $this->collection]); ?>')"
            :class="activeTab === 'import' ? 'px-3 py-1 rounded border text-sm bg-green-600 text-white' : 'px-3 py-1 rounded border text-sm bg-blue-600 text-white hover:bg-blue-700'">
            <?php I18n::p('IMPORT'); ?>
        </a>
    <?php } ?>

    <!-- Indexes -->
    <a href="javascript:void(0)"
        @click="activeTab = 'indexes'; callAjax('<?php echo Theme::URL('Collection/Indexes', ['db' => $this->db, 'collection' => $this->collection]); ?>')"
        :class="activeTab === 'indexes' ? 'px-3 py-1 rounded border text-sm bg-green-600 text-white' : 'px-3 py-1 rounded border text-sm bg-blue-600 text-white hover:bg-blue-700'">
        <?php I18n::p('INDEXES'); ?>
    </a>

    <!-- Search -->
    <a href="javascript:void(0)"
        @click="activeTab = 'search'; callAjax('<?php echo Theme::URL('Collection/Search', ['db' => $this->db, 'collection' => $this->collection]); ?>')"
        :class="activeTab === 'search' ? 'px-3 py-1 rounded border text-sm bg-green-600 text-white' : 'px-3 py-1 rounded border text-sm bg-blue-600 text-white hover:bg-blue-700'">
        <?php I18n::p('SEARCH'); ?>
    </a>
</div>