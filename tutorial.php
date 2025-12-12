<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VPN Bypass Configuration Guide</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }

        header {
            background: linear-gradient(135deg, #1a237e 0%, #283593 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }

        .warning-box {
            background: #fff3cd;
            border-left: 5px solid #ffc107;
            padding: 15px;
            margin: 20px;
            border-radius: 5px;
            font-size: 14px;
        }

        .section {
            padding: 30px;
            border-bottom: 1px solid #eee;
        }

        .section:nth-child(even) {
            background: #f8f9fa;
        }

        h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        h2 {
            color: #283593;
            margin: 25px 0 15px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #283593;
        }

        h3 {
            color: #5c6bc0;
            margin: 20px 0 10px 0;
        }

        .card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            margin: 15px 0;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .config-code {
            background: #282c34;
            color: #abb2bf;
            padding: 20px;
            border-radius: 8px;
            overflow-x: auto;
            margin: 15px 0;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            line-height: 1.4;
        }

        .config-code pre {
            margin: 0;
        }

        .carrier-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 20px 0;
        }

        .tab-btn {
            padding: 12px 25px;
            background: #e8eaf6;
            border: 2px solid #5c6bc0;
            border-radius: 30px;
            cursor: pointer;
            font-weight: bold;
            color: #283593;
            transition: all 0.3s ease;
        }

        .tab-btn:hover {
            background: #5c6bc0;
            color: white;
        }

        .tab-btn.active {
            background: #283593;
            color: white;
            border-color: #283593;
        }

        .tab-content {
            display: none;
            animation: fadeIn 0.5s ease;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .quick-links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }

        .quick-link {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 8px;
            text-decoration: none;
            color: #1565c0;
            font-weight: bold;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .quick-link:hover {
            background: #bbdefb;
            transform: translateX(5px);
        }

        .quick-link i {
            font-size: 1.2em;
        }

        .copy-btn {
            background: #4caf50;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
            transition: background 0.3s ease;
        }

        .copy-btn:hover {
            background: #388e3c;
        }

        .success-message {
            background: #4caf50;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
            text-align: center;
            display: none;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from { transform: translateY(-10px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        footer {
            background: #1a237e;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }

        @media (max-width: 768px) {
            .container {
                border-radius: 10px;
                margin: 10px;
            }

            header {
                padding: 25px;
            }

            h1 {
                font-size: 1.8em;
            }

            .section {
                padding: 20px;
            }

            .carrier-tabs {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
<?php
require_once 'db_config.php';
$sql = 'SELECT content FROM tutorial WHERE id = 1';
$stmt = $pdo->prepare($sql);
$stmt->execute();
$tutorial = $stmt->fetch();
$tutorial_content = $tutorial ? $tutorial['content'] : 'Tutorial content not found.';
echo $tutorial_content;
?>

<script>
        // Tab switching functionality
        function showTab(tabId) {
            // Hide all tab content
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });

            // Remove active class from all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // Show selected tab content
            document.getElementById(tabId).classList.add('active');

            // Set active button
            event.target.classList.add('active');
        }

        // Copy configuration to clipboard
        function copyConfig(configId) {
            const configs = {
                'gosurf': `# Globe GoSURF Configuration
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 13)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: gosurf.globe.com.ph"
http-proxy-option CUSTOM-HEADER "X-Online-Host: globe.com.ph"
http-proxy-option CUSTOM-HEADER "X-Forward-Host: gosurf.globe.com.ph"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"

# Choose your proxy server
http-proxy 110.78.141.147 8080
# Alternative: http-proxy 203.177.135.129 80`,

                'goshare': `# Globe GoSHARE99 Configuration
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 13)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: share.globe.com.ph"
http-proxy-option CUSTOM-HEADER "X-Online-Host: goshare99.globe.com.ph"
http-proxy-option CUSTOM-HEADER "X-Forward-Host: globeshare.com.ph"
http-proxy-option CUSTOM-HEADER "Referer: http://share.globe.com.ph/goshare99"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"

# Globe sharing proxy
http-proxy 110.78.141.147 8080
http-proxy-timeout 30`,

                'gowatch': `# Globe GoWATCH Configuration
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 13)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: assets.nflxext.com"
http-proxy-option CUSTOM-HEADER "X-Online-Host: netflix.com"
http-proxy-option CUSTOM-HEADER "X-Forward-Host: nflxvideo.net"
http-proxy-option CUSTOM-HEADER "Referer: https://www.netflix.com/"
http-proxy-option CUSTOM-HEADER "Accept: video/webm,video/ogg,video/*;q=0.9"

# Globe video proxy
http-proxy 203.177.135.150 80
http-proxy-timeout 45`,

                'smartbasic': `# Smart Basic Configuration
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: internet.smart.com.ph"
http-proxy-option CUSTOM-HEADER "X-Online-Host: smart.com.ph"
http-proxy-option CUSTOM-HEADER "X-Forward-Host: smart.com.ph"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"

# Smart Proxy
http-proxy 10.102.61.1 8080
http-proxy-timeout 30`,

                'smartbro': `# Smart Bro Configuration
http-proxy-option AGENT "Mozilla/5.0 (Windows NT 10.0; Win64; x64)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: smartbro.com.ph"
http-proxy-option CUSTOM-HEADER "X-Online-Host: bro.smart.com.ph"
http-proxy-option CUSTOM-HEADER "X-Forward-Host: home.smart.com.ph"
http-proxy-option CUSTOM-HEADER "X-Forwarded-For: 192.168.1.100"

# Smart Bro proxy
http-proxy 10.102.62.1 8080`,

                'saya': `# SAYA ALL 50 Configuration
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 13)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: saya50.smart.com.ph"
http-proxy-option CUSTOM-HEADER "X-Online-Host: saya.smart.com.ph"
http-proxy-option CUSTOM-HEADER "X-Forward-Host: sayaall50.ph"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"
http-proxy-option CUSTOM-HEADER "Referer: https://saya.smart.com.ph/register"

# Smart SAYA Proxy
http-proxy 10.102.70.1 8080
http-proxy-timeout 30`,

                'powerallfb99': `# POWER ALL FB 99 Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
# Please provide the correct Host, X-Online-Host, etc.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"

# Smart Proxy (example, might need to be changed)
http-proxy 10.102.61.1 8080
http-proxy-timeout 30`,
                'powerallkhanacademy99': `# POWER ALL KHAN ACADEMY 99 Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
# Please provide the correct Host, X-Online-Host, etc.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"

# Smart Proxy (example, might need to be changed)
http-proxy 10.102.61.1 8080
http-proxy-timeout 30`,
                'powerall59': `# POWER ALL 59 now w/ 5G DATA Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
# Please provide the correct Host, X-Online-Host, etc.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"

# Smart Proxy (example, might need to be changed)
http-proxy 10.102.61.1 8080
http-proxy-timeout 30`,
                'watchapp10': `# WATCHAPP 10 Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
# Please provide the correct Host, X-Online-Host, etc.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"

# Smart Proxy (example, might need to be changed)
http-proxy 10.102.61.1 8080
http-proxy-timeout 30`,
                'gigapower75': `# GIGA POWER 75 Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
# Please provide the correct Host, X-Online-Host, etc.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"

# Smart Proxy (example, might need to be changed)
http-proxy 10.102.61.1 8080
http-proxy-timeout 30`,
                'gigavideo60': `# GIGA VIDEO 60 Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
# Please provide the correct Host, X-Online-Host, etc.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"

# Smart Proxy (example, might need to be changed)
http-proxy 10.102.61.1 8080
http-proxy-timeout 30`,
                'gigastories60': `# GIGA STORIES 60 Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
# Please provide the correct Host, X-Online-Host, etc.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"

# Smart Proxy (example, might need to be changed)
http-proxy 10.102.61.1 8080
http-proxy-timeout 30`,
                'alldata50': `# ALL DATA 50 Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
# Please provide the correct Host, X-Online-Host, etc.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"

# Smart Proxy (example, might need to be changed)
http-proxy 10.102.61.1 8080
http-proxy-timeout 30`,
                'alldata99': `# ALL DATA 99 Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
# Please provide the correct Host, X-Online-Host, etc.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"

# Smart Proxy (example, might need to be changed)
http-proxy 10.102.61.1 8080
http-proxy-timeout 30`,
                'allaccess99': `# ALL ACCESS 99 Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
# Please provide the correct Host, X-Online-Host, etc.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"

# Smart Proxy (example, might need to be changed)
http-proxy 10.102.61.1 8080
http-proxy-timeout 30`,
                'magicappsfbig99': `# MAGIC APPS for FB & IG 99 Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
# Please provide the correct Host, X-Online-Host, etc.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"

# Smart Proxy (example, might need to be changed)
http-proxy 10.102.61.1 8080
http-proxy-timeout 30`,
                'magicappsytgmail99': `# MAGIC APPS for YT & Gmail 99 Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
# Please provide the correct Host, X-Online-Host, etc.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"

# Smart Proxy (example, might need to be changed)
http-proxy 10.102.61.1 8080
http-proxy-timeout 30`,
                'magicappsmlbb99': `# MAGIC APPS for MLBB 99 Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
# Please provide the correct Host, X-Online-Host, etc.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"

# Smart Proxy (example, might need to be changed)
http-proxy 10.102.61.1 8080
http-proxy-timeout 30`,
                'ctc20': `# CTC 20 Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
# Please provide the correct Host, X-Online-Host, etc.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"

# Smart Proxy (example, might need to be changed)
http-proxy 10.102.61.1 8080
http-proxy-timeout 30`,
                'tu50': `# TU 50 Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
# Please provide the correct Host, X-Online-Host, etc.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"

# Smart Proxy (example, might need to be changed)
http-proxy 10.102.61.1 8080
http-proxy-timeout 30`,
                'ssp20': `# SSP 20 Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
# Please provide the correct Host, X-Online-Host, etc.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"

# Smart Proxy (example, might need to be changed)
http-proxy 10.102.61.1 8080
http-proxy-timeout 30`,
                'uct50': `# UCT 50 Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
# Please provide the correct Host, X-Online-Host, etc.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"

# Smart Proxy (example, might need to be changed)
http-proxy 10.102.61.1 8080
http-proxy-timeout 30`,
                'uct30': `# UCT 30 Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
# Please provide the correct Host, X-Online-Host, etc.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"

# Smart Proxy (example, might need to be changed)
http-proxy 10.102.61.1 8080
http-proxy-timeout 30`,
                'aos99': `# AOS 99 Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
# Please provide the correct Host, X-Online-Host, etc.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"

# Smart Proxy (example, might need to be changed)
http-proxy 10.102.61.1 8080
http-proxy-timeout 30`,
                'aos50': `# AOS 50 Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
# Please provide the correct Host, X-Online-Host, etc.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"

# Smart Proxy (example, might need to be changed)
http-proxy 10.102.61.1 8080
http-proxy-timeout 30`,
                'aos30': `# AOS 30 Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
# Please provide the correct Host, X-Online-Host, etc.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"

# Smart Proxy (example, might need to be changed)
http-proxy 10.102.61.1 8080
http-proxy-timeout 30`,
                'aos20': `# AOS 20 Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
# Please provide the correct Host, X-Online-Host, etc.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"

# Smart Proxy (example, might need to be changed)
http-proxy 10.102.61.1 8080
http-proxy-timeout 30`,
                'allnet30': `# Allnet 30 Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
# Please provide the correct Host, X-Online-Host, etc.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"

# Smart Proxy (example, might need to be changed)
http-proxy 10.102.61.1 8080
http-proxy-timeout 30`,
                'tripledataloc75': `# TRIPLE DATA STORIES+ 75 Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
# Please provide the correct Host, X-Online-Host, etc.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"

# Smart Proxy (example, might need to be changed)
http-proxy 10.102.61.1 8080
http-proxy-timeout 30`,
                'tripledatavideo75': `# TRIPLE DATA VIDEO+ 75 Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
# Please provide the correct Host, X-Online-Host, etc.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"

# Smart Proxy (example, might need to be changed)
http-proxy 10.102.61.1 8080
http-proxy-timeout 30`,
                'doublegigastories75': `# DOUBLE GIGA STORIES+ 75 Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
# Please provide the correct Host, X-Online-Host, etc.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"

# Smart Proxy (example, might need to be changed)
http-proxy 10.102.61.1 8080
http-proxy-timeout 30`,
                'allnetlandline99': `# ALLNET now w/ Landline 99 Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
# Please provide the correct Host, X-Online-Host, etc.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"

# Smart Proxy (example, might need to be changed)
http-proxy 10.102.61.1 8080
http-proxy-timeout 30`,
                'allnetlandline50': `# ALLNET now w/ Landline 50 Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
# Please provide the correct Host, X-Online-Host, etc.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"

# Smart Proxy (example, might need to be changed)
http-proxy 10.102.61.1 8080
http-proxy-timeout 30`,
                'unli5g99day': `# UNLI 5G+ w/ 5GB/day 99 Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
# Please provide the correct Host, X-Online-Host, etc.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"

# Smart Proxy (example, might need to be changed)
http-proxy 10.102.61.1 8080
http-proxy-timeout 30`,

                'dito_levelup99codmtiktok': `# Level-Up 99 CODM TikTok Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"
http-proxy 10.102.61.1 8080`,
                'dito_playitappbooster': `# Play It! App Booster Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"
http-proxy 10.102.61.1 8080`,
                'dito_levelup99mlbbtiktok': `# Level-Up 99 MLBB TikTok Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"
http-proxy 10.102.61.1 8080`,
                'dito_levelupyoutube99': `# Level-Up YouTube 99 Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"
http-proxy 10.102.61.1 8080`,
                'dito_levelupsocials70': `# Level-Up Socials 70 Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"
http-proxy 10.102.61.1 8080`,
                'dito_levelupsocials50': `# Level-Up Socials 50 Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"
http-proxy 10.102.61.1 8080`,
                'dito_data50': `# DATA 50 Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"
http-proxy 10.102.61.1 8080`,
                'dito_newlevelup99': `# NEW DITO Level-Up 99 Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"
http-proxy 10.102.61.1 8080`,
                'dito_levelup99': `# DITO Level-Up 99 Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"
http-proxy 10.102.61.1 8080`,
                'dito_workitappbooster': `# Work It! App Booster Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"
http-proxy 10.102.61.1 8080`,
                'dito_liveitappbooster': `# Live It! App Booster Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"
http-proxy 10.102.61.1 8080`,
                'dito_levelupgaming39': `# Level-Up Gaming 39 Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"
http-proxy 10.102.61.1 8080`,
                'dito_levelupgaming59': `# Level-Up Gaming 59 Configuration
# ATTENTION: The specific HTTP headers for this promo are missing.
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: [PLEASE-PROVIDE-HOST]"
http-proxy-option CUSTOM-HEADER "X-Online-Host: [PLEASE-PROVIDE-ONLINE-HOST]"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"
http-proxy 10.102.61.1 8080`,

                'tntbasic': `# TNT Basic Configuration
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 13)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: internet.smart.com.ph"
http-proxy-option CUSTOM-HEADER "X-Online-Host: tnt.smart.com.ph"
http-proxy-option CUSTOM-HEADER "X-Forward-Host: tnt.smart.com.ph"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"

# TNT Proxy
http-proxy 10.102.61.46 8080
http-proxy-timeout 30`,

                'unli5g99': `# TNT UNLI 5G+ NSD 99 Configuration
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: unli5g.tnt.com.ph"
http-proxy-option CUSTOM-HEADER "X-Online-Host: nsd99.tnt.com.ph"
http-proxy-option CUSTOM-HEADER "X-Forward-Host: 5gplus.tnt.com.ph"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"
http-proxy-option CUSTOM-HEADER "Referer: https://tnt.smart.com.ph/unli5g"

# TNT 5G Proxy
http-proxy 10.102.64.1 8080
http-proxy-timeout 30`,

                'unli5g149': `# TNT UNLI 5G+ NSD 149 Configuration
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 14)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: nsd149.tnt.com.ph"
http-proxy-option CUSTOM-HEADER "X-Online-Host: unli5g149.tnt.com.ph"
http-proxy-option CUSTOM-HEADER "X-Forward-Host: 5g149.tnt.com.ph"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"
http-proxy-option CUSTOM-HEADER "Referer: https://tnt.smart.com.ph/unli5g149"

# TNT NSD 149 Proxy
http-proxy 10.102.66.1 8080
http-proxy-timeout 30`,

                'pisobasic': `# PISO WIFI Basic Configuration
http-proxy-option AGENT "Mozilla/5.0 (Linux; Android 10)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: free.facebook.com"
http-proxy-option CUSTOM-HEADER "X-Online-Host: 0.facebook.com"
http-proxy-option CUSTOM-HEADER "X-Forward-Host: m.facebook.com"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"
http-proxy-option CUSTOM-HEADER "Accept: text/html"

# Common PISO WIFI Gateway IPs (Try one at a time):
http-proxy 192.168.0.1 8080      # Most common
# http-proxy 192.168.1.1 8080    # Alternative
# http-proxy 192.168.8.1 8080    # Huawei routers

http-proxy-timeout 10`,

                'pisonet': `# PISONET Configuration
http-proxy-option AGENT "Mozilla/5.0 (Windows NT 6.1; Win64; x64)"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: captive.apple.com"
http-proxy-option CUSTOM-HEADER "X-Online-Host: connectivitycheck.android.com"
http-proxy-option CUSTOM-HEADER "X-Forward-Host: clients3.google.com"
http-proxy-option CUSTOM-HEADER "X-Forwarded-For: 8.8.8.8"
http-proxy-option CUSTOM-HEADER "Referer: http://pisonet-portal/"

# PISONET common gateway IPs
http-proxy 192.168.254.254 8080    # Common for PLDT-based
# http-proxy 192.168.123.1 80      # TP-Link default

http-proxy-timeout 10`,

                'timebypass': `# PISO WIFI Time Extension
http-proxy-option AGENT "iPhone"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: time.pisowifi.com"
http-proxy-option CUSTOM-HEADER "X-Online-Host: extend.pisonet.com"
http-proxy-option CUSTOM-HEADER "X-Forward-Host: unlimited.pisowifi.ph"
http-proxy-option CUSTOM-HEADER "Connection: keep-alive"
http-proxy-option CUSTOM-HEADER "Cache-Control: no-cache"

# Try different ports if standard doesn't work
http-proxy 192.168.0.1 80        # Port 80 sometimes works better
# http-proxy 192.168.1.1 3128    # Squid port

http-proxy-timeout 10`,

                'igglobe': `# Globe Instagram Promo Configuration
http-proxy-option AGENT "Instagram 319.0.0.0.000 Android"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: instagram.com"
http-proxy-option CUSTOM-HEADER "X-Online-Host: ig.globe.com.ph"
http-proxy-option CUSTOM-HEADER "X-Forward-Host: ig10.globe.com.ph"
http-proxy-option CUSTOM-HEADER "Connection: Keep-Alive"
http-proxy-option CUSTOM-HEADER "Referer: https://instagram.com/"

# Globe IG Proxies
http-proxy 110.78.141.200 8080
http-proxy-timeout 30`,

                'igsmart': `# Smart/TNT Instagram Configuration
http-proxy-option AGENT "Instagram 320.0.0.0.000 iPhone"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: ig.smart.com.ph"
http-proxy-option CUSTOM-HEADER "X-Online-Host: instagram.tnt.com.ph"
http-proxy-option CUSTOM-HEADER "X-Forward-Host: igcdn.smart.com.ph"
http-proxy-option CUSTOM-HEADER "Referer: https://instagram.com/"

# Smart IG Proxies
http-proxy 10.102.80.1 8080
http-proxy-timeout 30`,

                'igvideo': `# Instagram Video/Stories Configuration
http-proxy-option AGENT "Instagram 321.0.0.0.000 Android"
http-proxy-option VERSION 1.1
http-proxy-option CUSTOM-HEADER "Host: igvideo.smart.com.ph"
http-proxy-option CUSTOM-HEADER "X-Online-Host: scontent.cdninstagram.com"
http-proxy-option CUSTOM-HEADER "X-Forward-Host: igstories.ph"
http-proxy-option CUSTOM-HEADER "X-Forwarded-For: 157.240.235.35"

# Video optimized proxy
http-proxy 10.102.80.2 80
http-proxy-timeout 30`,

                'template': `client
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
route-delay 2`
            };

            const configText = configs[configId];

            // Create temporary textarea
            const textarea = document.createElement('textarea');
            textarea.value = configText;
            document.body.appendChild(textarea);
            textarea.select();
            textarea.setSelectionRange(0, 99999); // For mobile devices

            // Copy text
            document.execCommand('copy');
            document.body.removeChild(textarea);

            // Show success message
            const successMessage = document.getElementById('successMessage');
            successMessage.style.display = 'block';

            // Hide message after 3 seconds
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 3000);
        }

        // Smooth scrolling for anchor links
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
</body>
</html>
