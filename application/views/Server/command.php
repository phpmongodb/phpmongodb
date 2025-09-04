<div class="header">
    <h1 class="page-title">Output</h1>
</div>


<div class="well">


    <p id="execute-response">
        <?php
        if (!empty($this->data['output'])) {
            echo "<pre>";
            print_r($this->data['output']);
            echo "</pre>";
        } else if (isset($this->data['error'])) { ?>
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>Note:</strong> <?php echo $this->data['error']; ?>
    </div>
<?php } ?>

</p>
</div>