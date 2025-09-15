<?php defined('PMDDA') or die('Restricted access'); ?>
<?php $isLogedIn = Application::getInstance('Session')->isLogedIn(); ?>
<?php include('header.php'); ?>
<?php $isLogedIn ? include('sidebar.php') : ''; ?>

<main class="<?php echo $isLogedIn ? 'ml-60' : ''; ?> pt-4 px-6 min-h-screen bg-white">
    <?php
    $success = isset(View::getMessage()->sucess);
    $error = isset(View::getMessage()->error);
    ?>
    <?php if ($success): ?>
        <!-- Success Alert -->
        <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded relative mb-4">
            <strong class="font-bold">Success:</strong>
            <span class="block sm:inline">
                <?php echo View::getMessage()->sucess; ?>
            </span>
        </div>
    <?php elseif ($error): ?>
        <!-- Error Alert -->
        <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded relative mb-4">
            <strong class="font-bold">Error:</strong>
            <span class="block sm:inline">
                <?php echo View::getMessage()->error; ?>
            </span>
        </div>
    <?php endif; ?>

    <div id="middle-content">
        <?php echo View::getContent(); ?>
    </div>
</main>

<?php include('footer.php'); ?>