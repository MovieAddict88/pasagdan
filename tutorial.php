<?php
session_start();
require_once 'auth.php';
require_once 'db_config.php';
include 'header.php';

$stmt = $pdo->prepare("SELECT * FROM configurations WHERE is_active = 1 ORDER BY carrier, name");
$stmt->execute();
$configurations = $stmt->fetchAll(PDO::FETCH_ASSOC);

$carriers = [];
foreach ($configurations as $config) {
    $carriers[$config['carrier']][] = $config;
}
?>

<div class="page-header">
    <h1><span class="material-icons">description</span> VPN Configuration Tutorial</h1>
</div>

<div class="container tutorial-page">

    <div class="card">
        <div class="card-body">
            <h2 class="card-title">⚠️ Important Disclaimer</h2>
            <p>These configurations are for educational purposes only. Use them exclusively on networks you own or have explicit permission to test. Unauthorized access is strictly prohibited and illegal.</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h2 class="card-title">📋 Quick Navigation</h2>
            <div class="quick-links">
                <?php foreach (array_keys($carriers) as $carrier): ?>
                    <a href="#carrier-<?php echo strtolower(str_replace(' ', '-', $carrier)); ?>" class="btn btn-outline-primary btn-sm">
                        <span class="material-icons">router</span> <?php echo htmlspecialchars($carrier); ?>
                    </a>
                <?php endforeach; ?>
                <a href="#troubleshooting" class="btn btn-outline-secondary btn-sm">
                    <span class="material-icons">build</span> Troubleshooting
                </a>
            </div>
        </div>
    </div>

    <?php foreach ($carriers as $carrier => $configs): ?>
        <div id="carrier-<?php echo strtolower(str_replace(' ', '-', $carrier)); ?>" class="card">
            <div class="card-header">
                <h3><span class="material-icons">public</span> <?php echo htmlspecialchars($carrier); ?> Configurations</h3>
            </div>
            <div class="card-body">
                <div class="carrier-tabs">
                    <?php foreach ($configs as $index => $config): ?>
                        <button class="tab-btn <?php echo $index === 0 ? 'active' : ''; ?>" data-target="config-<?php echo $config['id']; ?>">
                            <?php echo htmlspecialchars($config['name']); ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <?php foreach ($configs as $index => $config): ?>
                    <div class="tab-content <?php echo $index === 0 ? 'active' : ''; ?>" id="config-<?php echo $config['id']; ?>">
                        <h4 class="config-title">
                            <?php echo htmlspecialchars($config['name']); ?>
                            <span class="badge badge-modern <?php echo !empty($config['is_free']) ? 'badge-success' : 'badge-premium'; ?>">
                                <?php echo htmlspecialchars(!empty($config['is_free']) ? 'Free' : 'Premium'); ?>
                            </span>
                        </h4>
                        <div class="config-container">
                            <pre><code class="language-generic"><?php echo htmlspecialchars($config['config_text']); ?></code></pre>
                            <button class="copy-btn" data-clipboard-target="#config-<?php echo $config['id']; ?> pre code">
                                <span class="material-icons">content_copy</span>
                                <span class="copy-text">Copy</span>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>

    <div id="troubleshooting" class="card">
        <div class="card-header">
            <h3><span class="material-icons">build</span> Troubleshooting Guide</h3>
        </div>
        <div class="card-body">
            <div class="troubleshooting-section">
                <h4>Common Issues and Solutions</h4>
                <p><strong>1. "Waiting for proxy server" error:</strong></p>
                <ul>
                    <li>Try different proxy IP addresses.</li>
                    <li>Change port (e.g., 8080, 80, 3128, 8000).</li>
                    <li>Ensure the proxy server is active and reachable.</li>
                </ul>

                <p><strong>2. Constant Reconnection:</strong></p>
                <p>Add the following lines to your configuration to improve stability:</p>
                <div class="config-container">
                    <pre><code id="troubleshoot-reconnect" class="language-generic">keepalive 10 60
ping-timer-rem
persist-tun
persist-key
reneg-sec 86400</code></pre>
                    <button class="copy-btn" data-clipboard-target="#troubleshoot-reconnect">
                        <span class="material-icons">content_copy</span>
                        <span class="copy-text">Copy</span>
                    </button>
                </div>

                <p><strong>3. Slow Speeds:</strong></p>
                <ul>
                    <li>Experiment with different carrier proxies.</li>
                    <li>Use during off-peak hours (e.g., 12 AM - 6 AM) for less network congestion.</li>
                    <li>Optimize MTU settings for your network:</li>
                </ul>
                <div class="config-container">
                    <pre><code id="troubleshoot-speed" class="language-generic">tun-mtu 1500
mssfix 1450
sndbuf 393216
rcvbuf 393216</code></pre>
                    <button class="copy-btn" data-clipboard-target="#troubleshoot-speed">
                        <span class="material-icons">content_copy</span>
                        <span class="copy-text">Copy</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
