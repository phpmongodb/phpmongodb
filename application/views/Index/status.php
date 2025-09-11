<div class="header">
    <h1 class="page-title">
        <i class="icon-database"></i>
        <a href="javascript:void(0)" class="text-info">Server Status</a>
        <small style="font-size: 14px; color: #999;">&nbsp;db.runCommand({ serverStatus: 1 })</small>
    </h1>
</div>

<!-- Add margin-top here -->
<div class="well" style="margin-top: 20px;">
    <pre style="font-family: monospace; font-size: 14px; background: #f9f9f9; padding: 15px; border: 1px solid #ddd; border-radius: 4px; max-height: 600px; overflow: auto;">
<?= htmlspecialchars($this->data['status'], ENT_QUOTES, 'UTF-8') ?>
    </pre>
</div>