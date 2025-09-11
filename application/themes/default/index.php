<?php defined('PMDDA') or die('Restricted access'); ?>
<?php $isLogedIn = Application::getInstance('Session')->isLogedIn(); ?>
<?php include('header.php'); ?>
<?php $isLogedIn ? include('sidebar.php') : ''; ?>

<main class="<?php echo $isLogedIn ? 'ml-60' : ''; ?> pt-4 px-6 min-h-screen bg-white">
    <?php
    $success = isset(View::getMessage()->sucess);
    $error = isset(View::getMessage()->error);
    ?>
    <?php if ($success || $error): ?>
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded relative mb-4">
            <strong class="font-bold">Note:</strong>
            <span class="block sm:inline">
                <?php echo $error ? View::getMessage()->error : View::getMessage()->sucess; ?>
            </span>
        </div>
    <?php endif; ?>

    <div id="middle-content">
        <?php echo View::getContent(); ?>
    </div>
</main>

<?php include('footer.php'); ?>