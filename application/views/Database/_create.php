<div class="w-full md:w-[48%] bg-white rounded shadow border border-gray-200 p-4">
    <!-- Block Heading -->
    <p class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">
        <?php I18n::p('C_DB'); ?>
    </p>

    <!-- Form -->
    <form id="form-create-database" method="post" action="index.php" class="space-y-4">
        <!-- Input: Database Name -->
        <div>
            <label for="database" class="block text-sm font-medium text-gray-600 mb-1">
                <?php I18n::p('NAME'); ?>
            </label>
            <input
                type="text"
                id="database"
                name="db"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" />
        </div>

        <!-- Hidden Load Action -->
        <input type="hidden" id="load-create" name="load" value="Database/Save" />

        <!-- Submit Button -->
        <button
            type="submit"
            name="btnCreateDb"
            class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow text-sm font-medium transition">
            <!-- Save Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <?php I18n::p('SAVE'); ?>
        </button>
    </form>
</div>