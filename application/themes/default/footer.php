<?php defined('PMDDA') or die('Restricted access'); ?>
<footer class="mt-10 py-4 text-sm text-center text-gray-500">
    <div class="container mx-auto">
        <a href="https://github.com/phpmongodb/phpmongodb" target="_blank" class="hover:underline">
            &copy; <?php echo date('Y'); ?> PHPmongoDB.org
        </a>
    </div>
</footer>
<script>
    function callAjax(url) {
        // Append theme false
        const finalUrl = url + '&theme=false';

        fetch(finalUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not OK');
                }
                return response.text();
            })
            .then(html => {
                const middle = document.getElementById('middle-content');
                if (middle) {
                    middle.innerHTML = html;
                }
            })
            .catch(error => {
                console.error('Error loading content:', error);
            });
    }
</script>

</body>

</html>