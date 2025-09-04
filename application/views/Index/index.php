<div class="row-fluid">
    <div class="block span6">
        <a href="#tablewidget" class="block-heading" data-toggle="collapse"><?PHP I18n::p('W_S'); ?></a>
        <div id="tablewidget" class="block-body collapse in">
            <table class="table">
                <tbody>
                    <tr>
                        <td><?PHP I18n::p('PHP_V'); ?></td>
                        <td><?php echo $this->data['phpversion']; ?></td>
                    </tr>
                    <tr>
                        <td><?PHP I18n::p('W_S'); ?></td>
                        <td><?php echo $this->data['webserver']; ?></td>
                    </tr>
                    <?php if (isset($this->data['mongoinfo']['version'])) { ?>
                        <tr>
                            <td><?PHP I18n::p('MONGODB_V'); ?></td>
                            <td><?php echo $this->data['mongoinfo']['version']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="block span6">
        <a href="#widget1container" class="block-heading" data-toggle="collapse"><?PHP I18n::p('B_I'); ?></a>
        <div id="widget1container" class="block-body collapse in">
            <table class="table">
                <tbody>
                    <?php
                    if (is_array($this->data['mongoinfo'])) {
                        foreach ($this->data['mongoinfo'] as $k => $v) {
                            if (is_array($v)) {
                                echo "<tr><td colspan='2'><strong>$k</strong></td></tr>";
                                foreach ($v as $subKey => $subVal) {
                                    echo "<tr><td>&nbsp;&nbsp;&nbsp;$subKey</td><td>";
                                    echo is_array($subVal) ? json_encode($subVal) : htmlspecialchars((string)$subVal);
                                    echo "</td></tr>";
                                }
                            } else {
                                echo "<tr><td>$k</td><td>" . htmlspecialchars((string)$v) . "</td></tr>";
                            }
                        }
                    }
                    ?>

                </tbody>
            </table>
        </div>
    </div>
</div>