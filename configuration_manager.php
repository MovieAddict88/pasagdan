<?php
session_start();
require_once 'auth.php';
require_once 'db_config.php';

if (!is_admin()) {
    header('Location: login.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM configurations ORDER BY carrier, name");
$stmt->execute();
$configurations = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<div class="page-header">
    <h2>Configuration Management</h2>
</div>

<div class="card">
    <div class="card-header">
        <h3>Configurations</h3>
        <a href="add_configuration.php" class="btn btn-primary">Add New Configuration</a>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Carrier</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($configurations as $config): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($config['carrier']); ?></td>
                        <td><?php echo htmlspecialchars($config['name']); ?></td>
                        <td><?php echo $config['is_active'] ? 'Active' : 'Inactive'; ?></td>
                        <td>
                            <a href="edit_configuration.php?id=<?php echo $config['id']; ?>" class="btn btn-primary">Edit</a>
                            <form action="delete_configuration.php" method="post" style="display: inline-block;">
                                <input type="hidden" name="id" value="<?php echo $config['id']; ?>">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this configuration?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>
