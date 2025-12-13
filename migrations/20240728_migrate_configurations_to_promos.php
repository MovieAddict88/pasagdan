<?php
try {
    // Add new columns to promos table if they don't exist
    $columns = $pdo->query("SHOW COLUMNS FROM `promos`")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('carrier', $columns)) {
        $pdo->exec('ALTER TABLE promos ADD COLUMN carrier VARCHAR(255)');
    }
    if (!in_array('config_text', $columns)) {
        $pdo->exec('ALTER TABLE promos ADD COLUMN config_text TEXT');
    }
    if (!in_array('is_active', $columns)) {
        $pdo->exec('ALTER TABLE promos ADD COLUMN is_active BOOLEAN NOT NULL DEFAULT 1');
    }

    // Copy data from configurations to promos
    $stmt_select = $pdo->query("SELECT * FROM configurations");
    $configurations = $stmt_select->fetchAll(PDO::FETCH_ASSOC);

    foreach ($configurations as $config) {
        $sql = 'INSERT INTO promos (promo_name, icon_promo_path, carrier, config_text, is_active) VALUES (:promo_name, :icon_promo_path, :carrier, :config_text, :is_active)';
        if ($stmt_insert = $pdo->prepare($sql)) {
            $stmt_insert->bindParam(':promo_name', $config['name'], PDO::PARAM_STR);
            $stmt_insert->bindValue(':icon_promo_path', 'assets/promo/default.png', PDO::PARAM_STR); // Set a default icon
            $stmt_insert->bindParam(':carrier', $config['carrier'], PDO::PARAM_STR);
            $stmt_insert->bindParam(':config_text', $config['config_text'], PDO::PARAM_STR);
            $stmt_insert->bindParam(':is_active', $config['is_active'], PDO::PARAM_INT);
            $stmt_insert->execute();
        }
    }

    // Drop the configurations table
    $pdo->exec('DROP TABLE configurations');

    echo "Data migration completed successfully!";

} catch (PDOException $e) {
    die("Migration failed: " . $e->getMessage());
}
?>