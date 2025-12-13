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
            <p>With Working Proxies & Headers Database</p>

            <h4>🛠️ GENERAL TROUBLESHOOTING STEPS</h4>

            <h5>1. Connection Issues (No Internet)</h5>
            <p><strong>Step-by-Step Diagnosis:</strong></p>
            <pre><code># 1. Check basic internet connectivity
ping 8.8.8.8

# 2. Check DNS resolution
nslookup google.com

# 3. Check proxy connectivity
telnet [PROXY_IP] [PORT]

# 4. Check VPN server connectivity
telnet am1.vpnjantit.com 1194
</code></pre>
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
            <pre><code># Stability settings
keepalive 10 60
ping-timer-rem
persist-tun
persist-key
reneg-sec 86400
resolv-retry infinite
remote-random

# Timeout settings
connect-timeout 60
connect-retry 5
connect-retry-max 999
</code></pre>

            <h5>3. Slow Speeds</h5>
            <p>Optimization settings:</p>
            <pre><code># Speed optimization
tun-mtu 1500
mssfix 1450
sndbuf 393216
rcvbuf 393216
txqueuelen 4000
socket-flags TCP_NODELAY
comp-lzo adaptive
fast-io
</code></pre>

            <h5>4. Authentication Errors</h5>
            <p>Solutions:</p>
            <ol>
                <li>Check username/password</li>
                <li>Ensure promo is active</li>
                <li>Clear VPN app cache/data</li>
                <li>Reinstall VPN client</li>
            </ol>

            <hr>

            <h4>📋 CARRIER-SPECIFIC TROUBLESHOOTING</h4>

            <h5>Globe/TM Issues:</h5>
            <pre><code>APN: http.globe.com.ph
Proxy detection: Globe has aggressive DPI
Solution: Rotate headers every 2-3 hours
Special: Use vip.facebook.com for better stability
</code></pre>

            <h5>Smart/TNT Issues:</h5>
            <pre><code>APN: internet
Proxy detection: Blocks high bandwidth VPN
Solution: Use 10.x.x.x internal proxies
Special: Change User-Agent frequently
</code></pre>

            <h5>DITO Issues:</h5>
            <pre><code>APN: internet.dito.ph
Proxy detection: Newer, more advanced
Solution: Use app-specific headers
Special: 5G proxies work better
</code></pre>

            <hr>

            <h4>🔄 WORKING PROXY DATABASE (Updated)</h4>

            <h5>GLOBE PROXIES:</h5>
            <pre><code># Main Proxies:
110.78.141.147 8080  # Most reliable
203.177.42.214 8080   # Alternative
112.198.115.44 3128   # Squid proxy
110.78.149.200 80     # Video optimized
203.177.135.129 8080  # Stable

# Backup Proxies:
112.198.78.120 80
203.177.135.130 3128
110.78.149.28 8080
112.198.76.130 3128

# Port Variations:
110.78.141.147 80
110.78.141.147 3128
110.78.141.147 8000
110.78.141.147 8888
</code></pre>

            <h5>SMART/TNT PROXIES:</h5>
            <pre><code># Internal Proxies (10.x.x.x):
10.102.61.1 8080      # Main
10.102.61.2 80        # HTTP
10.102.61.3 3128      # Squid
10.102.61.4 8080      # Backup
10.102.61.5 80        # Alternative

# Public Proxies:
122.54.201.210 8080
122.54.201.211 80
122.54.201.212 3128
122.54.201.213 8080

# Specialized Proxies:
10.102.64.1 8080      # 5G optimized
10.102.80.1 8080      # Instagram
10.102.90.1 8080      # TikTok
10.102.95.1 8080      # Facebook
</code></pre>

            <h5>DITO PROXIES:</h5>
            <pre><code># Primary Proxies:
10.200.1.1 8080       # Main
10.200.1.2 80         # HTTP
10.200.1.3 3128       # Squid
10.200.1.4 8080       # Video
10.200.1.5 80         # Gaming

# Specialized Proxies:
10.200.2.1 8080       # Gaming
10.200.3.1 8080       # TikTok
10.200.10.1 8080      # App Booster
10.200.11.1 8080      # Gaming Boost
10.200.12.1 8080      # Video Boost

# Public Proxies:
122.55.1.1 8080
122.55.1.2 80
122.55.1.3 3128
</code></pre>

            <hr>

            <h4>📝 WORKING HEADERS DATABASE</h4>

            <h5>GENERIC BYPASS HEADERS:</h5>
            <p>Option 1 (Basic):</p>
            <pre><code>http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 13)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: facebook.com"
http-proxy-option CUSTOM-HEADER "X-Online-Host: facebook.com"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"
</code></pre>
            <p>Option 2 (Advanced):</p>
            <pre><code>http-proxy-option AGENT "Dalvik/2.1.0 (Linux; U; Android 11)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: vip.facebook.com"
http-proxy-option CUSTOM-HEADER "X-Online-Host: vip.facebook.com"
http-proxy-option CUSTOM-HEADER "X-Forward-Host: vip.facebook.com"
http-proxy-option CUSTOM-HEADER "Proxy-Connection: keep-alive"
</code></pre>

            <h5>PROMO-SPECIFIC HEADERS:</h5>

            <p>Globe Promos:</p>
            <pre><code># Go+99:
Host: goplus99.globe.com.ph
X-Online-Host: goplus.globe.com.ph

# GoWATCH:
Host: gowatch99.globe.com.ph
X-Online-Host: gowatch.globe.com.ph

# GoSHARE:
Host: goshare99.globe.com.ph
X-Online-Host: share.globe.com.ph

# Instagram:
Host: ig.globe.com.ph
X-Online-Host: instagram.com
</code></pre>

            <p>Smart/TNT Promos:</p>
            <pre><code># POWER ALL:
Host: powerall.smart.com.ph
X-Online-Host: powerall99.smart.com.ph

# UNLI 5G+:
Host: unli5g.tnt.com.ph
X-Online-Host: 5gplus.tnt.com.ph

# SAYA ALL:
Host: saya50.smart.com.ph
X-Online-Host: saya.smart.com.ph

# TikTok:
Host: tiktok.smart.com.ph
X-Online-Host: tt.smart.com.ph
</code></pre>

            <p>DITO Promos:</p>
            <pre><code># Level-Up:
Host: levelup99.dito.ph
X-Online-Host: codm.tiktok.dito.ph

# Play It! Boost:
Host: playit.dito.ph
X-Online-Host: appbooster.dito.ph

# Gaming:
Host: game.dito.ph
X-Online-Host: gaming.dito.ph
</code></pre>

            <hr>

            <h4>🔧 PROXY TESTING & ROTATION SCRIPT</h4>
            <p>For Android (Termux):</p>
            <pre><code>#!/bin/bash
# Save as test_proxies.sh

PROXIES=(
    "110.78.141.147 8080"
    "203.177.42.214 8080"
    "112.198.115.44 3128"
    "10.102.61.1 8080"
    "10.200.1.1 8080"
    "122.54.201.210 8080"
)

HEADERS=(
    "Host: facebook.com"
    "Host: vip.facebook.com"
    "Host: m.facebook.com"
    "Host: 0.facebook.com"
)

echo "Testing proxies..."
for proxy in "${PROXIES[@]}"; do
    IP=$(echo $proxy | cut -d' ' -f1)
    PORT=$(echo $proxy | cut -d' ' -f2)

    echo -n "Testing $IP:$PORT... "

    # Test connectivity
    timeout 3 curl -s -o /dev/null -w "%{http_code}" \
        --proxy http://$IP:$PORT \
        -H "Host: facebook.com" \
        http://google.com

    if [ $? -eq 0 ]; then
        echo "✓ WORKING"
        echo "$IP:$PORT" >> working_proxies.txt
    else
        echo "✗ FAILED"
    fi
done

echo ""
echo "Working proxies saved to working_proxies.txt"
</code></pre>

            <hr>

            <h4>🚨 ERROR CODES & SOLUTIONS</h4>
            <p>Common OpenVPN Errors:</p>
            <p>TLS Error: TLS key negotiation failed</p>
            <pre><code># Solution:
cipher AES-128-CBC
auth SHA1
remote-cert-tls server
</code></pre>
            <p>AUTH_FAILED</p>
            <ol>
                <li>Wrong credentials</li>
                <li>Promo expired</li>
                <li>Account locked</li>
                <li>Too many connections</li>
            </ol>
            <p>SOCKET ERROR</p>
            <pre><code># Add to config:
proto tcp
tun-mtu 1500
mssfix 1450
</code></pre>
            <p>DNS RESOLUTION ERROR</p>
            <pre><code># Add to config:
dhcp-option DNS 8.8.8.8
dhcp-option DNS 8.8.4.4
dhcp-option DNS 1.1.1.1
</code></pre>

            <hr>

            <h4>📊 PROMO STATUS CHECKING</h4>
            <p>Globe:</p>
            <pre><code>*143# → Check balance
Text BAL to 8080
Text GOSURF STATUS to 8080
</code></pre>
            <p>Smart/TNT:</p>
            <pre><code>*123# → Check promo
Text BAL to 9999 (Smart) / 4545 (TNT)
Text INFO to respective number
</code></pre>
            <p>DITO:</p>
            <pre><code>*185# → Check balance
Text BAL to 185
Text STATUS to 185
</code></pre>

            <hr>

            <h4>⚡ QUICK FIX CHECKLIST</h4>
            <p>Before changing anything:</p>
            <ul>
                <li>[ ] Restart device</li>
                <li>[ ] Toggle Airplane mode</li>
                <li>[ ] Check promo status</li>
                <li>[ ] Verify APN settings</li>
                <li>[ ] Test without VPN first</li>
            </ul>
            <p>If still not working:</p>
            <ul>
                <li>[ ] Change proxy server</li>
                <li>[ ] Change port (80, 8080, 3128)</li>
                <li>[ ] Rotate User-Agent</li>
                <li>[ ] Change Host header</li>
                <li>[ ] Try different promo headers</li>
            </ul>
            <p>Advanced fixes:</p>
            <ul>
                <li>[ ] Clear DNS cache</li>
                <li>[ ] Change network mode</li>
                <li>[ ] Use different VPN server</li>
                <li>[ ] Try SOCKS5 instead of HTTP</li>
                <li>[ ] Contact carrier support</li>
            </ul>

            <hr>

            <h4>🔐 SECURITY TIPS</h4>
            <p>Do NOT use:</p>
            <ul>
                <li>Public/free VPN servers</li>
                <li>Same config for more than 7 days</li>
                <li>Obvious fake headers</li>
                <li>High bandwidth during peak hours</li>
            </ul>
            <p>Best Practices:</p>
            <ul>
                <li>Rotate proxies daily</li>
                <li>Change headers weekly</li>
                <li>Monitor data usage</li>
                <li>Use legit-looking User-Agents</li>
                <li>Keep promo active</li>
            </ul>

            <hr>

            <h4>📱 DEVICE-SPECIFIC TIPS</h4>
            <p>Android:</p>
            <pre><code># Developer Options:
1. Enable "Stay awake"
2. Disable "Mobile data always active"
3. Set "Background process limit" to Standard

# Battery Optimization:
- Exclude VPN app
- Disable adaptive battery for VPN
</code></pre>
            <p>iOS:</p>
            <pre><code># VPN Settings:
1. Enable "Connect on Demand"
2. Disable "IPV6"
3. Use IKEv2 if OpenVPN fails
</code></pre>
            <p>Windows:</p>
            <pre><code># Network Settings:
1. Disable IPV6
2. Set DNS to 8.8.8.8
3. Disable proxy auto-detection
</code></pre>

            <hr>

            <h4>🎯 FINAL TROUBLESHOOTING FLOWCHART</h4>
            <pre><code>Start → No Internet
       ↓
1. Test without VPN
       ↓
2. Check promo status
       ↓
3. Test different proxy
       ↓
4. Rotate headers
       ↓
5. Change APN
       ↓
6. Contact carrier
       ↓
End → Working/Not Working
</code></pre>

            <hr>

            <h4>💾 CONFIG BACKUP TEMPLATE</h4>
            <p>Save this as backup_config.ovpn:</p>
            <pre><code>client
dev tun
proto tcp
remote [SERVER] [PORT]

# Headers Template
http-proxy-option AGENT "[USER_AGENT]"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [ONLINE_HOST]"
http-proxy [PROXY_IP] [PROXY_PORT]

# Standard Settings
nobind
persist-key
persist-tun
auth-user-pass
keepalive 10 60
cipher AES-128-CBC
auth SHA1
remote-cert-tls server
verb 3

# Add certificates here
&lt;ca&gt;
...
&lt;/ca&gt;
</code></pre>

            <hr>

            <h4>📞 EMERGENCY CONTACTS</h4>
            <p>Globe: *143# or 211<br>
            Smart:*123# or 8888<br>
            TNT:*123# or 4545<br>
            DITO:*185# or 185</p>
            <p>Save these: Have at least ₱10 load for emergency registration.</p>

            <hr>

            <h4>✅ SUMMARY</h4>
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
            <p>Remember: What works today might not work tomorrow. Always have 3-4 working configurations ready to switch between.</p>
            <p>Need help with a specific error or configuration? Let me know the exact error message and your current setup!</p>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
