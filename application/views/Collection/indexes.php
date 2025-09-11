<?php require_once '_menu.php'; ?>
<div class="well" id="container-indexes">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#IndexesList" data-toggle="tab"><?php I18n::p('LIST'); ?></a></li>
        <?php if (!Application::isReadonly()) { ?>
            <li><a href="#IndexesCreate" data-toggle="tab"><?php I18n::p('CREATE'); ?></a></li>
        <?php } ?>
    </ul>

    <div id="myTabContent" class="tab-content">
        <div class="tab-pane active in" id="IndexesList">


            <tbody>
                <?php
                if (isset($this->data['indexes']) && is_array($this->data['indexes'])) {
                    foreach ($this->data['indexes'] as $index) {
                ?>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th colspan="2">Index Details</th>
                                </tr>
                            </thead>
                            <?php

                            foreach ($index as $key => $value) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($key) . '</td>';
                                echo '<td>';
                                if (is_array($value)) {
                                    echo '<pre>' . $this->data['formatter']->highlight($this->data['formatter']->arrayToJSON($value)) . '</pre>';
                                } else {
                                    echo htmlspecialchars((string)$value);
                                }
                                echo '</td>';
                                echo '</tr>';
                            }
                            if (!Application::isReadonly() && isset($index['name']) && $index['name'] !== '_id_') {
                                echo '<tr><td>Action</td><td><a href="' . Theme::URL('Collection/DeleteIndexes', array('db' => $this->db, 'collection' => $this->collection, 'name' => $index['name'])) . '">Delete</a></td></tr>';
                            }
                            echo '<tr><td colspan="2"><hr></td></tr>';
                            ?>
            </tbody>
            </table>
    <?php
                    }
                }
    ?>


        </div>
        <?php
        if (!Application::isReadonly())
            require_once '_create_index.php';
        ?>

    </div>
</div>