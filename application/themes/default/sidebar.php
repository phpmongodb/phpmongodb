<?php defined('PMDDA') or die('Restricted access'); ?>

<aside class="w-60 fixed top-[52px] bottom-0 bg-[#F4F4F4] border-r border-gray-300 overflow-y-auto text-sm z-10">
    <?php
    $dbList = Widget::get('DBList');
    $chttp = new Chttp();
    $dbName = $chttp->getParam('db');
    ?>

    <ul class="space-y-1 py-4">
        <?php foreach ($dbList as $db): ?>
            <?php $isActive = $dbName === $db['name']; ?>

            <li class="px-4">
                <a href="<?php echo Theme::URL('Collection/Index', ['db' => $db['name']]); ?>"
                    class="flex justify-between items-center px-3 py-2 rounded hover:bg-[#E0F7E9] <?php echo $isActive ? 'bg-[#D1FADF] font-semibold' : ''; ?>">
                    <span>
                        <i class="icon-database mr-2 inline-block w-4 h-4"></i>
                        <?php echo $db['name']; ?>
                    </span>
                    <span class="bg-green-600 text-white text-xs font-semibold rounded-full px-2">
                        <?php echo $db['noOfCollecton']; ?>
                    </span>
                </a>

                <?php if ($isActive && ($collectionList = Widget::get('CollectonList'))): ?>
                    <ul class="ml-5 mt-1 space-y-1 text-gray-700">
                        <?php foreach ($collectionList as $collection): ?>
                            <li>
                                <a href="<?php echo Theme::URL('Collection/Record', ['db' => $db['name'], 'collection' => $collection['name']]); ?>"
                                    class="flex justify-between px-2 py-1 hover:bg-gray-200 rounded">
                                    <span><i class="icon-collection mr-1"></i><?php echo $collection['name']; ?></span>
                                    <span class="text-xs text-gray-500">(<?php echo $collection['count']; ?>)</span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </li>

        <?php endforeach; ?>
    </ul>
</aside>