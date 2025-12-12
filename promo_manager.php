<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header('location: login.php');
    exit;
}

require_once 'db_config.php';
require_once 'utils.php';

// Handle Add/Edit Promo
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['promo_name'])) {
    $promo_name = trim($_POST['promo_name']);
    $icon_promo_path = trim($_POST['icon_promo_path']);
    $carrier_id = trim($_POST['carrier_id']);
    $promo_id = isset($_POST['promo_id']) ? $_POST['promo_id'] : null;

    if (!empty($promo_name) && !empty($icon_promo_path) && !empty($carrier_id)) {
        if ($promo_id) {
            // Update existing promo
            $sql = 'UPDATE promos SET promo_name = :promo_name, icon_promo_path = :icon_promo_path, carrier_id = :carrier_id WHERE id = :id';
            if ($stmt = $pdo->prepare($sql)) {
                $stmt->bindParam(':promo_name', $promo_name, PDO::PARAM_STR);
                $stmt->bindParam(':icon_promo_path', $icon_promo_path, PDO::PARAM_STR);
                $stmt->bindParam(':carrier_id', $carrier_id, PDO::PARAM_INT);
                $stmt->bindParam(':id', $promo_id, PDO::PARAM_INT);
                $stmt->execute();
            }
        } else {
            // Add new promo
            $sql = 'INSERT INTO promos (promo_name, icon_promo_path, carrier_id) VALUES (:promo_name, :icon_promo_path, :carrier_id)';
            if ($stmt = $pdo->prepare($sql)) {
                $stmt->bindParam(':promo_name', $promo_name, PDO::PARAM_STR);
                $stmt->bindParam(':icon_promo_path', $icon_promo_path, PDO::PARAM_STR);
                $stmt->bindParam(':carrier_id', $carrier_id, PDO::PARAM_INT);
                $stmt->execute();
            }
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

// Fetch promo for editing
$edit_promo = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql = 'SELECT * FROM promos WHERE id = :id';
    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $edit_promo = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

include 'header.php';
?>

<div class="page-header">
    <h2><?php echo translate('promo_manager'); ?></h2>
</div>

<div class="card">
    <div class="card-header">
        <h3><?php echo $edit_promo ? 'Edit Promo' : 'Add New Promo'; ?></h3>
    </div>
    <div class="card-body">
        <form action="promo_manager.php" method="post">
            <input type="hidden" name="promo_id" value="<?php echo $edit_promo ? $edit_promo['id'] : ''; ?>">
            <div class="form-group">
                <label class="form-label"><?php echo translate('promo_name'); ?></label>
                <input type="text" name="promo_name" class="form-control" value="<?php echo $edit_promo ? htmlspecialchars($edit_promo['promo_name']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label"><?php echo translate('icon'); ?></label>
                <select name="icon_promo_path" class="form-control" required>
                    <?php
                    $promo_icons = glob('assets/promo/*.png');
                    foreach ($promo_icons as $icon) {
                        $selected = ($edit_promo && $edit_promo['icon_promo_path'] == $icon) ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($icon) . "' $selected>" . htmlspecialchars(basename($icon)) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Carrier</label>
                <select name="carrier_id" class="form-control" required>
                    <?php
                    $sql = 'SELECT * FROM carriers ORDER BY name';
                    $carriers = $pdo->query($sql)->fetchAll();
                    foreach ($carriers as $carrier) {
                        $selected = ($edit_promo && $edit_promo['carrier_id'] == $carrier['id']) ? 'selected' : '';
                        echo "<option value='" . $carrier['id'] . "' $selected>" . htmlspecialchars($carrier['name']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="<?php echo $edit_promo ? 'Update Promo' : 'Add Promo'; ?>">
                <?php if ($edit_promo): ?>
                    <a href="promo_manager.php" class="btn btn-secondary">Cancel Edit</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><?php echo translate('existing_promos'); ?></h3>
    </div>
    <div class="card-body">
        <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th><?php echo translate('id'); ?></th>
                    <th><?php echo translate('promo_name'); ?></th>
                    <th><?php echo translate('icon'); ?></th>
                    <th>Carrier</th>
                    <th><?php echo translate('actions'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = 'SELECT p.*, c.name as carrier_name FROM promos p LEFT JOIN carriers c ON p.carrier_id = c.id ORDER BY p.id DESC';
                $promos = $pdo->query($sql)->fetchAll();
                $base_url = get_base_url();
                foreach ($promos as $promo) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($promo['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($promo['promo_name']) . "</td>";
                    echo "<td><img src='" . htmlspecialchars($base_url . $promo['icon_promo_path']) . "' alt='icon' width='30'></td>";
                    echo "<td>" . htmlspecialchars($promo['carrier_name']) . "</td>";
                    echo "<td>
                            <a href='promo_manager.php?edit=" . $promo['id'] . "' class='btn btn-secondary'>Edit</a>
                            <a href='promo_manager.php?delete=" . $promo['id'] . "' class='btn btn-danger' onclick='return confirm(\"" . htmlspecialchars(translate('are_you_sure'), ENT_QUOTES) . "\")'>" . translate('delete') . "</a>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
