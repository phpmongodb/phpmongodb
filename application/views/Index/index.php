<div class="flex flex-wrap gap-6 mt-6">
    <!-- PHP & Web Server Info -->
    <div class="w-full md:w-[48%] bg-white rounded shadow p-4" x-data="{ open: true }">
        <button @click="open = !open" class="w-full flex justify-between items-center font-semibold text-left text-gray-800">
            <span><?php I18n::p('W_S'); ?></span>
            <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>
        <div x-show="open" class="mt-4">
            <table class="w-full table-auto text-sm border border-gray-200">
                <tbody class="divide-y divide-gray-100">
                    <tr class="hover:bg-gray-50">
                        <td class="font-medium px-3 py-2"><?php I18n::p('PHP_V'); ?></td>
                        <td class="px-3 py-2"><?php echo $this->data['phpversion']; ?></td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="font-medium px-3 py-2"><?php I18n::p('W_S'); ?></td>
                        <td class="px-3 py-2"><?php echo $this->data['webserver']; ?></td>
                    </tr>
                    <?php if (isset($this->data['mongoinfo']['version'])) { ?>
                        <tr class="hover:bg-gray-50">
                            <td class="font-medium px-3 py-2"><?php I18n::p('MONGODB_V'); ?></td>
                            <td class="px-3 py-2"><?php echo $this->data['mongoinfo']['version']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Build Info -->
    <div class="w-full md:w-[48%] bg-white rounded shadow p-4" x-data="{ open: true }">
        <button @click="open = !open" class="w-full flex justify-between items-center font-semibold text-left text-gray-800">
            <span><?php I18n::p('B_I'); ?></span>
            <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>
        <div x-show="open" class="mt-4">
            <table class="w-full table-auto text-sm border border-gray-200">
                <tbody class="divide-y divide-gray-100">
                    <?php
                    if (is_array($this->data['mongoinfo'])) {
                        foreach ($this->data['mongoinfo'] as $k => $v) {
                            if (is_array($v)) {
                                echo "<tr><td colspan='2' class='px-3 py-2 font-bold bg-gray-50'>$k</td></tr>";
                                foreach ($v as $subKey => $subVal) {
                                    echo "<tr class='hover:bg-gray-50'>";
                                    echo "<td class='px-3 py-2'>&nbsp;&nbsp;&nbsp;$subKey</td>";
                                    echo "<td class='px-3 py-2'>";
                                    echo is_array($subVal) ? json_encode($subVal) : htmlspecialchars((string)$subVal);
                                    echo "</td></tr>";
                                }
                            } else {
                                echo "<tr class='hover:bg-gray-50'>";
                                echo "<td class='px-3 py-2'>$k</td>";
                                echo "<td class='px-3 py-2'>" . htmlspecialchars((string)$v) . "</td></tr>";
                            }
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>