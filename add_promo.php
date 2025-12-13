<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header('location: login.php');
    exit;
}

require_once 'db_config.php';
require_once 'utils.php';

$promo_name = $icon_promo_path = '';
$promo_name_err = $icon_promo_path_err = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty(trim($_POST['promo_name']))) {
        $promo_name_err = 'Please enter a promo name.';
    } else {
        $promo_name = trim($_POST['promo_name']);
    }

    if (empty(trim($_POST['icon_promo_path']))) {
        $icon_promo_path_err = 'Please select an icon.';
    } else {
        $icon_promo_path = trim($_POST['icon_promo_path']);
    }

    if (empty($promo_name_err) && empty($icon_promo_path_err)) {
        $sql = 'INSERT INTO promos (promo_name, icon_promo_path) VALUES (:promo_name, :icon_promo_path)';
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(':promo_name', $promo_name, PDO::PARAM_STR);
            $stmt->bindParam(':icon_promo_path', $icon_promo_path, PDO::PARAM_STR);
            if ($stmt->execute()) {
                header('location: promo_manager.php');
                exit;
            } else {
                echo 'Something went wrong. Please try again later.';
            }
        }
    }
}

include 'header.php';
?>

<div class="page-header">
    <h2><?php echo translate('add_promo'); ?></h2>
</div>

<div class="card">
    <div class="card-header">
        <h3><?php echo translate('add_new_promo'); ?></h3>
    </div>
    <div class="card-body">
        <form action="add_promo.php" method="post">
            <div class="form-group <?php echo (!empty($promo_name_err)) ? 'has-error' : ''; ?>">
                <label class="form-label"><?php echo translate('promo_name'); ?></label>
                <input type="text" name="promo_name" class="form-control" value="<?php echo htmlspecialchars($promo_name); ?>">
                <span class="help-block"><?php echo $promo_name_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($icon_promo_path_err)) ? 'has-error' : ''; ?>">
                <label class="form-label"><?php echo translate('icon'); ?></label>
                <select name="icon_promo_path" class="form-control">
                    <option value="">-- Select Icon --</option>
                    <?php
                    $promo_icons = glob('assets/promo/*.png');
                    foreach ($promo_icons as $icon) {
                        echo "<option value='" . htmlspecialchars($icon) . "'>" . htmlspecialchars(basename($icon)) . "</option>";
                    }
                    ?>
                </select>
                <span class="help-block"><?php echo $icon_promo_path_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="<?php echo translate('submit'); ?>">
                <a href="promo_manager.php" class="btn btn-default"><?php echo translate('cancel'); ?></a>
            </div>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
