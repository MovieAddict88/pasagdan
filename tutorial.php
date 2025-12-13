<?php
session_start();
require_once 'auth.php';
require_once 'db_config.php';
include 'header.php';

$stmt = $pdo->prepare("SELECT * FROM promos WHERE is_active = 1 ORDER BY carrier, promo_name");
$stmt->execute();
$promos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$carriers = [];
foreach ($promos as $promo) {
    $carriers[$promo['carrier']][] = $promo;
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
                            <?php echo htmlspecialchars($config['promo_name']); ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <?php foreach ($configs as $index => $config): ?>
                    <div class="tab-content <?php echo $index === 0 ? 'active' : ''; ?>" id="config-<?php echo $config['id']; ?>">
                        <h4 class="config-title">
                            <?php echo htmlspecialchars($config['promo_name']); ?>
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
            <h3><span class="material-icons">build</span> COMPLETE TROUBLESHOOTING GUIDE FOR ALL PROMOS</h3>
        </div>
        <div class="card-body">
            <div class="accordion">
                <div class="accordion-item">
                    <button class="accordion-header">🛠️ GENERAL TROUBLESHOOTING STEPS</button>
                    <div class="accordion-content">
                        <h5>1. Connection Issues (No Internet)</h5>
                        <p><strong>Step-by-Step Diagnosis:</strong></p>
                        <pre><code>...</code></pre>
                        <p><strong>Quick Fixes:</strong></p>
                        <ol>
                            <li>Restart phone/device</li>
                            <li>Toggle Airplane mode (on/off)</li>
                            <li>Clear APN settings and reconfigure</li>
                            <li>Change network mode (3G/4G/5G)</li>
                            <li>Reinsert SIM card</li>
                        </ol>
                        <h5>2. VPN Connection Drops Frequently</h5>
                        <p>Add these to your .ovpn config:</p>
                        <pre><code>...</code></pre>
                        <h5>3. Slow Speeds</h5>
                        <p>Optimization settings:</p>
                        <pre><code>...</code></pre>
                        <h5>4. Authentication Errors</h5>
                        <p>Solutions:</p>
                        <ol>
                            <li>Check username/password</li>
                            <li>Ensure promo is active</li>
                            <li>Clear VPN app cache/data</li>
                            <li>Reinstall VPN client</li>
                        </ol>
                    </div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-header">📋 CARRIER-SPECIFIC TROUBLESHOOTING</button>
                    <div class="accordion-content">
                        <h5>Globe/TM Issues:</h5>
                        <pre><code>...</code></pre>
                        <h5>Smart/TNT Issues:</h5>
                        <pre><code>...</code></pre>
                        <h5>DITO Issues:</h5>
                        <pre><code>...</code></pre>
                    </div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-header">🔄 WORKING PROXY DATABASE (Updated)</button>
                    <div class="accordion-content">
                        <h5>GLOBE PROXIES:</h5>
                        <pre><code>...</code></pre>
                        <h5>SMART/TNT PROXIES:</h5>
                        <pre><code>...</code></pre>
                        <h5>DITO PROXIES:</h5>
                        <pre><code>...</code></pre>
                    </div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-header">📝 WORKING HEADERS DATABASE</button>
                    <div class="accordion-content">
                        <h5>GENERIC BYPASS HEADERS:</h5>
                        <pre><code>...</code></pre>
                        <h5>PROMO-SPECIFIC HEADERS:</h5>
                        <pre><code>...</code></pre>
                    </div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-header">🔧 PROXY TESTING & ROTATION SCRIPT</button>
                    <div class="accordion-content">
                        <p>For Android (Termux):</p>
                        <pre><code>...</code></pre>
                    </div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-header">🚨 ERROR CODES & SOLUTIONS</button>
                    <div class="accordion-content">
                        <p>Common OpenVPN Errors:</p>
                        <pre><code>...</code></pre>
                    </div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-header">📊 PROMO STATUS CHECKING</button>
                    <div class="accordion-content">
                        <p>Globe:</p>
                        <pre><code>...</code></pre>
                        <p>Smart/TNT:</p>
                        <pre><code>...</code></pre>
                        <p>DITO:</p>
                        <pre><code>...</code></pre>
                    </div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-header">⚡ QUICK FIX CHECKLIST</button>
                    <div class="accordion-content">
                        <p>Before changing anything:</p>
                        <ul>
                            <li>[ ] Restart device</li>
                            <li>[ ] Toggle Airplane mode</li>
                            <li>[ ] Check promo status</li>
                            <li>[ ] Verify APN settings</li>
                            <li>[ ] Test without VPN first</li>
                        </ul>
                    </div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-header">🔐 SECURITY TIPS</button>
                    <div class="accordion-content">
                        <p>Do NOT use:</p>
                        <ul>
                            <li>Public/free VPN servers</li>
                            <li>Same config for more than 7 days</li>
                            <li>Obvious fake headers</li>
                            <li>High bandwidth during peak hours</li>
                        </ul>
                    </div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-header">📱 DEVICE-SPECIFIC TIPS</button>
                    <div class="accordion-content">
                        <p>Android:</p>
                        <pre><code>...</code></pre>
                        <p>iOS:</p>
                        <pre><code>...</code></pre>
                        <p>Windows:</p>
                        <pre><code>...</code></pre>
                    </div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-header">🎯 FINAL TROUBLESHOOTING FLOWCHART</button>
                    <div class="accordion-content">
                        <pre><code>...</code></pre>
                    </div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-header">💾 CONFIG BACKUP TEMPLATE</button>
                    <div class="accordion-content">
                        <p>Save this as backup_config.ovpn:</p>
                        <pre><code>...</code></pre>
                    </div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-header">📞 EMERGENCY CONTACTS</button>
                    <div class="accordion-content">
                        <p>Globe: *143# or 211<br>
                        Smart:*123# or 8888<br>
                        TNT:*123# or 4545<br>
                        DITO:*185# or 185</p>
                    </div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-header">✅ SUMMARY</button>
                    <div class="accordion-content">
                        <ol>
                            <li>Always test proxies before use</li>
                            <li>Rotate headers every few hours</li>
                            <li>Keep multiple configs ready</li>
                            <li>Monitor data usage regularly</li>
                            <li>Have backup load for re-registration</li>
                            <li>Update configs monthly</li>
                            <li>Use legit-looking User-Agents</li>
                            <li>Avoid peak hours (7-10AM, 6-11PM)</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const accordionHeaders = document.querySelectorAll('.accordion-header');

    accordionHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const content = this.nextElementSibling;
            if (content.style.display === 'block') {
                content.style.display = 'none';
            } else {
                content.style.display = 'block';
            }
        });
    });
});
</script>

<?php include 'footer.php'; ?>
