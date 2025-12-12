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
    <h2>📡 VPN Bypass Configuration Guide</h2>
    <p>Complete tutorial for carrier-specific VPN configurations</p>
</div>

<div class="container">
    <div class="warning-box">
        ⚠️ <strong>Important Disclaimer:</strong> These configurations are for educational purposes only.
        Use only on networks you own or have permission to test. Unauthorized access is illegal.
    </div>

    <div class="section">
        <h2>📋 Quick Navigation</h2>
        <div class="quick-links">
            <?php foreach (array_keys($carriers) as $carrier): ?>
                <a href="#<?php echo strtolower(str_replace(' ', '', $carrier)); ?>" class="quick-link">
                    🌐 <?php echo htmlspecialchars($carrier); ?>
                </a>
            <?php endforeach; ?>
            <a href="#troubleshoot" class="quick-link">🔧 Troubleshooting</a>
        </div>
    </div>

    <?php foreach ($carriers as $carrier => $configs): ?>
        <?php $carrierId = strtolower(str_replace(' ', '', $carrier)); ?>
        <div class="section" id="<?php echo $carrierId; ?>">
            <h2>🌐 <?php echo htmlspecialchars($carrier); ?> Configurations</h2>

            <div class="carrier-tabs">
                <?php foreach ($configs as $index => $config): ?>
                    <?php $tabId = strtolower(str_replace(' ', '', $carrier . '_' . $config['name'])); ?>
                    <button class="tab-btn <?php echo $index === 0 ? 'active' : ''; ?>" onclick="showTab('<?php echo $tabId; ?>', '<?php echo $carrierId; ?>')">
                        <?php echo htmlspecialchars($config['name']); ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <?php foreach ($configs as $index => $config): ?>
                <?php $tabId = strtolower(str_replace(' ', '', $carrier . '_' . $config['name'])); ?>
                <div class="tab-content <?php echo $index === 0 ? 'active' : ''; ?>" id="<?php echo $tabId; ?>" data-carrier="<?php echo strtolower(str_replace(' ', '', $carrier)); ?>">
                    <h3><?php echo htmlspecialchars($config['name']); ?></h3>
                    <div class="config-code">
                        <pre><?php echo htmlspecialchars($config['config_text']); ?></pre>
                    </div>
                    <button class="copy-btn" onclick="copyConfig('<?php echo $tabId; ?>')">Copy Configuration</button>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>

    <div class="section" id="troubleshoot">
        <h2>🔧 Troubleshooting Guide</h2>

        <div class="card">
            <h3>Common Issues and Solutions</h3>
            <p><strong>1. "Waiting for proxy server" error:</strong></p>
            <ul>
                <li>Try different proxy IP addresses</li>
                <li>Change port (8080, 80, 3128, 8000)</li>
                <li>Check if proxy server is active</li>
            </ul>

            <p><strong>2. Constant reconnection:</strong></p>
            <ul>
                <li>Add these to your config:</li>
            </ul>
            <div class="config-code">
                <pre>keepalive 10 60
ping-timer-rem
persist-tun
persist-key
reneg-sec 86400</pre>
            </div>

            <p><strong>3. Slow speeds:</strong></p>
            <ul>
                <li>Try different carrier proxies</li>
                <li>Use during off-peak hours (12AM-6AM)</li>
                <li>Optimize MTU settings:</li>
            </ul>
            <div class="config-code">
                <pre>tun-mtu 1500
mssfix 1450
sndbuf 393216
rcvbuf 393216</pre>
            </div>
        </div>

        <div class="card">
            <h3>General .ovpn Template</h3>
            <div class="config-code">
                <pre>client
dev tun
proto tcp

# VPN Server Connection
remote am1.vpnjantit.com 1194
remote-random
resolv-retry infinite

# HTTP Proxy Headers (Choose from above)
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 13)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [CHOOSE_HOST_HERE]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [CHOOSE_ONLINE_HOST]"

# Proxy Server (Choose based on carrier)
http-proxy [PROXY_IP] [PORT]
http-proxy-timeout 30
http-proxy-retry

# Basic Settings
nobind
persist-key
persist-tun
auth-user-pass
keepalive 10 60
verb 3

# Encryption
cipher AES-128-CBC
auth SHA1

# DNS
dhcp-option DNS 8.8.8.8
dhcp-option DNS 8.8.4.4

# Routing
redirect-gateway def1
route-delay 2</pre>
            </div>
            <button class="copy-btn" onclick="copyConfig('template')">Copy Template</button>
        </div>
    </div>
</div>

<div id="successMessage" class="success-message">✅ Configuration copied to clipboard!</div>

<script>
    function showTab(tabId, carrierId) {
        const carrierSection = document.getElementById(carrierId);
        // Hide all tab content within the same carrier section
        carrierSection.querySelectorAll('.tab-content').forEach(content => {
            content.classList.remove('active');
        });
        // Deactivate all tab buttons within the same carrier section
        carrierSection.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        // Show the selected tab content
        document.getElementById(tabId).classList.add('active');
        // Activate the selected tab button
        event.target.classList.add('active');
    }

    function copyConfig(tabId) {
        const configText = document.querySelector(`#${tabId} .config-code pre`).innerText;
        const textarea = document.createElement('textarea');
        textarea.value = configText;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);

        const successMessage = document.getElementById('successMessage');
        successMessage.style.display = 'block';
        setTimeout(() => {
            successMessage.style.display = 'none';
        }, 3000);
    }

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;

            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 20,
                    behavior: 'smooth'
                });
            }
        });
    });
</script>

<?php include 'footer.php'; ?>
