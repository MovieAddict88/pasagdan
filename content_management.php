<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header('location: login.php');
    exit;
}

include 'header.php';
?>

<div class="page-header">
    <h2><?php echo translate('content_management'); ?></h2>
</div>

<div class="list-group">
    <a href="promo_manager.php" class="list-group-item list-group-item-action">
        <?php echo translate('promo_manager'); ?>
    </a>
    <a href="configuration_manager.php" class="list-group-item list-group-item-action">
        <?php echo translate('configuration_manager'); ?>
    </a>
</div>

<?php include 'footer.php'; ?>
