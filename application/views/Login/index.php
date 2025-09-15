<?php $autocomplete = isset(Config::$autocomplete) && Config::$autocomplete == TRUE ? 'on' : 'off'; ?>

<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md bg-white rounded shadow-md border border-gray-200 p-6">

        <!-- Heading -->
        <p class="text-xl font-semibold text-gray-800 mb-4">Sign In</p>

        <!-- Form -->
        <form id="tab2" method="post" action="index.php" class="space-y-4">

            <!-- Username -->
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    <?php I18n::p('USERNAME'); ?>
                </label>
                <input type="text" name="username" autocomplete="<?php echo $autocomplete; ?>" autofocus
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" />
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    <?php I18n::p('PASSWORD'); ?>
                </label>
                <input type="password" name="password" value="" autocomplete="<?php echo $autocomplete; ?>"
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" />
            </div>

            <!-- Database (only if required) -->
            <?php if (Config::$authentication['authentication']) { ?>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">
                        <?php I18n::p('Database'); ?>
                    </label>
                    <input type="text" name="db" value="" autocomplete="<?php echo $autocomplete; ?>"
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" />
                </div>
            <?php } ?>

            <!-- Submit -->
            <div class="flex justify-end">
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow text-sm font-medium transition">
                    <?php I18n::p('LOGIN'); ?>
                </button>
            </div>

            <!-- Hidden field -->
            <input type="hidden" name="load" value="Login/Index" />
        </form>
    </div>
</div>