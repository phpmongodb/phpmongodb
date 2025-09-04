<div class="tab-pane fade" id="IndexesCreate">
    <form id="tab1" method="post" action="index.php" class="">
        <table id="tbl-create-indexes" class="table">
            <tr>
                <td><strong><?php I18n::p('NAME'); ?></strong></td>
                <td colspan="3">
                    <input type="text" class="input-xxlarge" name="name" required="required">
                </td>
            </tr>

            <tr>
                <td><strong><?php I18n::p('FIELDS'); ?></strong></td>
                <td>
                    <input type="text" class="input-xxlarge" name="fields[]" required="required">
                </td>
                <td>
                    <select name="orders[]" class="input-small">
                        <option value="1">ASC</option>
                        <option value="-1">DESC</option>
                    </select>
                </td>
                <td>
                    <a href="javascript:void(0)" onclick="PMDIN.appendTR();" class="icon-plus" title="Add">&nbsp;</a>
                </td>
            </tr>
        </table>
        <table class="table">
            <tr>
                <td><strong><?php I18n::p('UNIQUE'); ?></strong></td>
                <td colspan="3">
                    <input type="checkbox" value="1" name="unique" id="index_unique" onclick="PMDIN.isCheck(this)">
                </td>
            </tr>

            <tr id="drop_duplicates" style="display: none">
                <td><strong><?php I18n::p('DROP_DUPLICATES'); ?></strong></td>
                <td colspan="3"><input type="checkbox" value="1" name="drop"></td>
            </tr>

            <tr>
                <td><strong><?php I18n::p('TTL'); ?></strong></td>
                <td colspan="3">
                    <input type="number" class="input-xxlarge" name="expireAfterSeconds" placeholder="e.g. 3600">
                </td>
            </tr>

            <tr>
                <td><strong><?php I18n::p('SPARSE'); ?></strong></td>
                <td colspan="3"><input type="checkbox" value="1" name="sparse"></td>
            </tr>

            <tr>
                <td><strong><?php I18n::p('HIDDEN'); ?></strong></td>
                <td colspan="3"><input type="checkbox" value="1" name="hidden"></td>
            </tr>

            <tr>
                <td><strong><?php I18n::p('BACKGROUND'); ?></strong></td>
                <td colspan="3"><input type="checkbox" value="1" name="background"></td>
            </tr>

            <tr>
                <td><strong><?php I18n::p('PARTIAL_FILTER_EXPRESSION'); ?></strong></td>
                <td colspan="3">
                    <input type="text" class="input-xxlarge" name="partialFilterExpression" placeholder='e.g. {"status": "active"}'>
                </td>
            </tr>

            <tr>
                <td><strong><?php I18n::p('COLLATION'); ?></strong></td>
                <td colspan="3">
                    <input type="text" class="input-xxlarge" name="collation" placeholder='e.g. {"locale": "en", "strength": 2}'>
                </td>
            </tr>
        </table>


        <div>
            <button class="btn btn-primary"><?php I18n::p('CREATE'); ?></button>
        </div>

        <input type="hidden" name="load" value="Collection/CreateIndexes" />
        <input type="hidden" name="db" value="<?php echo $this->db; ?>" />
        <input type="hidden" name="collection" value="<?php echo $this->collection; ?>" />
    </form>
</div>