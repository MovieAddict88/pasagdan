<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header('location: login.php');
    exit;
}

require_once 'db_config.php';
require_once 'utils.php';

// Handle Delete Promo
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = 'DELETE FROM promos WHERE id = :id';
    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
    header('location: promo_manager.php');
    exit;
}

include 'header.php';
?>

<div class="page-header">
    <h2><?php echo translate('promo_manager'); ?></h2>
</div>

<div class="card">
    <div class="card-header">
        <h3><?php echo translate('existing_promos'); ?></h3>
        <a href="add_promo.php" class="btn btn-primary"><?php echo translate('add_new_promo'); ?></a>
    </div>
    <div class="card-body">
        <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th><?php echo translate('id'); ?></th>
                    <th><?php echo translate('promo_name'); ?></th>
                    <th><?php echo translate('icon'); ?></th>
                    <th><?php echo translate('actions'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = 'SELECT * FROM promos ORDER BY id DESC';
                $promos = $pdo->query($sql)->fetchAll();
                $base_url = get_base_url();
                foreach ($promos as $promo) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($promo['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($promo['promo_name']) . "</td>";
                    echo "<td><img src='" . htmlspecialchars($base_url . $promo['icon_promo_path']) . "' alt='icon' width='30'></td>";
                    echo "<td>";
                    echo "<a href='edit_promo.php?id=" . $promo['id'] . "' class='btn btn-primary'>" . translate('edit') . "</a> ";
                    echo "<a href='promo_manager.php?delete=" . $promo['id'] . "' class='btn btn-danger' onclick='return confirm(\"" . htmlspecialchars(translate('are_you_sure'), ENT_QUOTES) . "\")'>" . translate('delete') . "</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
