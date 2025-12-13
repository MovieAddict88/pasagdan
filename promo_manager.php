<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header('location: login.php');
    exit;
}

require_once 'db_config.php';
require_once 'utils.php';

// Handle Add Promo
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['promo_name'])) {
    $promo_name = trim($_POST['promo_name']);
    $icon_promo_path = trim($_POST['icon_promo_path']);

    if (!empty($promo_name) && !empty($icon_promo_path)) {
        $sql = 'INSERT INTO promos (promo_name, icon_promo_path) VALUES (:promo_name, :icon_promo_path)';
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(':promo_name', $promo_name, PDO::PARAM_STR);
            $stmt->bindParam(':icon_promo_path', $icon_promo_path, PDO::PARAM_STR);
            $stmt->execute();
        }
    }
    header('location: promo_manager.php');
    exit;
}

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
        <h3><?php echo translate('add_new_promo'); ?></h3>
    </div>
    <div class="card-body">
        <form action="promo_manager.php" method="post">
            <div class="form-group">
                <label class="form-label"><?php echo translate('promo_name'); ?></label>
                <input type="text" name="promo_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label"><?php echo translate('icon'); ?></label>
                <select name="icon_promo_path" class="form-control" required>
                    <?php
                    $promo_icons = glob('assets/promo/*.png');
                    foreach ($promo_icons as $icon) {
                        echo "<option value='" . htmlspecialchars($icon) . "'>" . htmlspecialchars(basename($icon)) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="<?php echo translate('add_promo'); ?>">
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><?php echo translate('existing_promos'); ?></h3>
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
                    echo "<td><a href='promo_manager.php?delete=" . $promo['id'] . "' class='btn btn-danger' onclick='return confirm(\"" . htmlspecialchars(translate('are_you_sure'), ENT_QUOTES) . "\")'>" . translate('delete') . "</a></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
