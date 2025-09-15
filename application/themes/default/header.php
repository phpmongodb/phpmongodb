<?php defined('PMDDA') or die('Restricted access'); ?>
<!DOCTYPE html>
<html lang="en" x-data x-cloak>

<head>
    <meta charset="utf-8" />
    <title>PHPmongoDB | MongoDB Admin Tool</title>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Favicon -->
    <link rel="icon" href="<?php echo Theme::getPath(); ?>images/favicon.ico" type="image/x-icon" />

    <style>
        [x-cloak] {
            display: none;
        }
    </style>
</head>

<body class="bg-gray-100 text-sm text-gray-800">
    <!-- Navbar -->
    <nav class="bg-[#116149] text-white shadow">
        <div class="max-w-full mx-auto px-6 py-3 flex items-center justify-between">
            <!-- Brand -->
            <a href="<?php echo Theme::getHome(); ?>" class="text-xl font-semibold tracking-wide">
                { <span class="text-gray-300 italic">PHP</span> <span class="font-bold"> mongoDB </span>}
            </a>

            <!-- Nav Menu -->
            <ul class="flex items-center gap-6">
                <?php if ($isLogedIn): ?>
                    <li><a href="<?php echo Theme::URL('Server/Execute'); ?>" class="hover:underline"><?php echo I18n::t('Execute'); ?></a></li>
                    <li><a href="<?php echo Theme::URL('Database/Index'); ?>" class="hover:underline"><?php echo I18n::t('DB'); ?></a></li>
                    <li><a href="<?php echo Theme::URL('Index/Status'); ?>" class="hover:underline"><?php echo I18n::t('STATUS'); ?></a></li>
                    <li><a href="<?php echo Theme::URL('Login/Logout'); ?>" class="hover:underline"><?php echo I18n::t('LOGOUT'); ?></a></li>
                <?php endif; ?>

                <!-- Language Dropdown -->
                <li class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-1 focus:outline-none">
                        <span class="text-sm"><?php echo I18n::t('LAN'); ?></span>
                        <svg class="w-4 h-4 transform transition-transform duration-300" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <ul
                        x-show="open"
                        @click.outside="open = false"
                        class="absolute right-0 mt-2 bg-white text-gray-800 rounded shadow w-36 z-50">
                        <?php foreach (Widget::get('languageList') as $key => $val): ?>
                            <li>
                                <a href="<?php echo Theme::URL('Index/SetLanguage', ['language' => $key]); ?>" class="block px-4 py-2 hover:bg-gray-100">
                                    <?php echo $val; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>