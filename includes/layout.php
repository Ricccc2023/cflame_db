<?php
require_once __DIR__ . "/header.php";
?>
<!-- FULL WIDTH HEADER -->
<div class="topbar">
    MEDICALE HEALTHCARE CENTER
    <div style="font-size:13px;font-weight:normal;">
        Welcome, <?= htmlspecialchars($_SESSION['user']['name'] ?? 'User') ?>
    </div>
</div>


<!-- SIDEBAR + CONTENT -->
<div class="app">

    <?php if (!isset($hideSidebar) || !$hideSidebar): ?>
        <?php require_once __DIR__ . "/sidebar.php"; ?>
    <?php endif; ?>

    <div class="main">
        <div class="page">
            <?= $content ?? '' ?>
        </div>
    </div>

</div>

<?php require_once __DIR__ . "/footer.php"; ?>