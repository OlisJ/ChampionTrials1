<?php
require("config.php"); // config.php now handles session_start

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] <= 0) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Library - The GameVault</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Navigation Header -->
    <header class="header" id="header">
        <nav class="nav-container">
            <a href="index.php" class="logo">
                <div class="logo-icon">
                    <div class="logo-prism">
                        <div class="prism-shape"></div>
                    </div>
                </div>
                <span class="logo-text">
                    <span class="prism">Game</span>
                    <span class="flux">Vault</span>
                </span>
            </a>
            
            <ul class="nav-menu" id="navMenu">
                <li><a href="index.php" class="nav-link">Home</a></li>
                <li><a href="index.php#about" class="nav-link">About</a></li>
                <li><a href="index.php#stats" class="nav-link">Metrics</a></li>
                <li><a href="index.php#skills" class="nav-link">Arsenal</a></li>
                <li><a href="library.php" class="nav-link active">Library</a></li>
                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0): ?>
                    <li><a href="logout.php" class="nav-link">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php" class="nav-link">Login</a></li>
                    <li><a href="signup.php" class="nav-link">Sign Up</a></li>
                <?php endif; ?>
            </ul>
            
            <div class="menu-toggle" id="menuToggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </nav>
    </header>

    <!-- Library Section -->
    <section class="stats-section" id="library" style="padding-top: 150px;">
        <div class="section-header">
            <h2 class="section-title">Game Library</h2>
            <p class="section-subtitle"><?php echo isset($_SESSION['user_name']) ? 'Welcome, ' . htmlspecialchars($_SESSION['user_name']) . '! ' : ''; ?>Explore our collection of premium games</p>
        </div>
        
        <!-- Static Game Cards (from carousel) -->
        <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; padding: 40px 20px;">
            <!-- Snake -->
            <div class="stat-card game-card" style="position: relative; transform: none; opacity: 1;">
                <div class="card" style="background: var(--bg-secondary); border-radius: 15px; padding: 30px; height: 100%; display: flex; flex-direction: column;">
                    <div class="card-number" style="position: absolute; top: 20px; right: 20px; font-size: 48px; font-weight: bold; color: var(--accent-purple); opacity: 0.3;">01</div>
                    <div class="card-image" style="width: 100%; height: 200px; border-radius: 10px; overflow: hidden; margin-bottom: 20px;">
                        <img src="images/neural-network.jpg" alt="Snake" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <h3 class="card-title" style="font-size: 24px; margin-bottom: 15px; color: var(--text-primary);">Snake</h3>
                    <p class="card-description" style="color: var(--text-secondary); margin-bottom: 20px; flex-grow: 1;">Advanced AI system with deep learning capabilities for predictive analytics and pattern recognition.</p>
                    <div class="card-tech" style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 20px;">
                        <span class="tech-badge" style="background: var(--accent-purple); color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px;">TensorFlow</span>
                        <span class="tech-badge" style="background: var(--accent-blue); color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px;">Python</span>
                        <span class="tech-badge" style="background: var(--accent-cyan); color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px;">CUDA</span>
                    </div>
                    <a href="snake/snake.html" class="card-cta" style="display: inline-block; background: linear-gradient(135deg, var(--accent-purple), var(--accent-blue)); color: white; border: none; padding: 12px 24px; border-radius: 8px; cursor: pointer; font-weight: bold; transition: transform 0.2s; text-decoration: none; text-align: center;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">Play Game</a>
                </div>
            </div>

            <!-- Mine Sweeper -->
            <div class="stat-card game-card" style="position: relative; transform: none; opacity: 1;">
                <div class="card" style="background: var(--bg-secondary); border-radius: 15px; padding: 30px; height: 100%; display: flex; flex-direction: column;">
                    <div class="card-number" style="position: absolute; top: 20px; right: 20px; font-size: 48px; font-weight: bold; color: var(--accent-purple); opacity: 0.3;">02</div>
                    <div class="card-image" style="width: 100%; height: 200px; border-radius: 10px; overflow: hidden; margin-bottom: 20px;">
                        <img src="images/quantum-cloud.jpg" alt="Mine Sweeper" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <h3 class="card-title" style="font-size: 24px; margin-bottom: 15px; color: var(--text-primary);">Mine Sweeper</h3>
                    <p class="card-description" style="color: var(--text-secondary); margin-bottom: 20px; flex-grow: 1;">Next-generation cloud infrastructure leveraging quantum computing for unprecedented processing power.</p>
                    <div class="card-tech" style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 20px;">
                        <span class="tech-badge" style="background: var(--accent-purple); color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px;">AWS</span>
                        <span class="tech-badge" style="background: var(--accent-blue); color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px;">Kubernetes</span>
                        <span class="tech-badge" style="background: var(--accent-cyan); color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px;">Docker</span>
                    </div>
                    <button class="card-cta" style="background: linear-gradient(135deg, var(--accent-purple), var(--accent-blue)); color: white; border: none; padding: 12px 24px; border-radius: 8px; cursor: pointer; font-weight: bold; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">Play Game</button>
                </div>
            </div>

            <!-- Tic Tac Toe -->
            <div class="stat-card game-card" style="position: relative; transform: none; opacity: 1;">
                <div class="card" style="background: var(--bg-secondary); border-radius: 15px; padding: 30px; height: 100%; display: flex; flex-direction: column;">
                    <div class="card-number" style="position: absolute; top: 20px; right: 20px; font-size: 48px; font-weight: bold; color: var(--accent-purple); opacity: 0.3;">03</div>
                    <div class="card-image" style="width: 100%; height: 200px; border-radius: 10px; overflow: hidden; margin-bottom: 20px;">
                        <img src="images/blockchain-vault.jpg" alt="Tic Tac Toe" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <h3 class="card-title" style="font-size: 24px; margin-bottom: 15px; color: var(--text-primary);">Tic Tac Toe</h3>
                    <p class="card-description" style="color: var(--text-secondary); margin-bottom: 20px; flex-grow: 1;">Secure decentralized storage solution using advanced encryption and distributed ledger technology.</p>
                    <div class="card-tech" style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 20px;">
                        <span class="tech-badge" style="background: var(--accent-purple); color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px;">Ethereum</span>
                        <span class="tech-badge" style="background: var(--accent-blue); color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px;">Solidity</span>
                        <span class="tech-badge" style="background: var(--accent-cyan); color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px;">Web3</span>
                    </div>
                    <button class="card-cta" style="background: linear-gradient(135deg, var(--accent-purple), var(--accent-blue)); color: white; border: none; padding: 12px 24px; border-radius: 8px; cursor: pointer; font-weight: bold; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">Play Game</button>
                </div>
            </div>

            <!-- Cyber Defense -->
            <div class="stat-card game-card" style="position: relative; transform: none; opacity: 1;">
                <div class="card" style="background: var(--bg-secondary); border-radius: 15px; padding: 30px; height: 100%; display: flex; flex-direction: column;">
                    <div class="card-number" style="position: absolute; top: 20px; right: 20px; font-size: 48px; font-weight: bold; color: var(--accent-purple); opacity: 0.3;">04</div>
                    <div class="card-image" style="width: 100%; height: 200px; border-radius: 10px; overflow: hidden; margin-bottom: 20px;">
                        <img src="images/cyber-defense.jpg" alt="Cyber Defense" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <h3 class="card-title" style="font-size: 24px; margin-bottom: 15px; color: var(--text-primary);">Cyber Defense</h3>
                    <p class="card-description" style="color: var(--text-secondary); margin-bottom: 20px; flex-grow: 1;">Military-grade cybersecurity framework with real-time threat detection and automated response.</p>
                    <div class="card-tech" style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 20px;">
                        <span class="tech-badge" style="background: var(--accent-purple); color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px;">Zero Trust</span>
                        <span class="tech-badge" style="background: var(--accent-blue); color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px;">AI Defense</span>
                        <span class="tech-badge" style="background: var(--accent-cyan); color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px;">Encryption</span>
                    </div>
                    <button class="card-cta" style="background: linear-gradient(135deg, var(--accent-purple), var(--accent-blue)); color: white; border: none; padding: 12px 24px; border-radius: 8px; cursor: pointer; font-weight: bold; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">Play Game</button>
                </div>
            </div>

            <!-- Data Nexus -->
            <div class="stat-card game-card" style="position: relative; transform: none; opacity: 1;">
                <div class="card" style="background: var(--bg-secondary); border-radius: 15px; padding: 30px; height: 100%; display: flex; flex-direction: column;">
                    <div class="card-number" style="position: absolute; top: 20px; right: 20px; font-size: 48px; font-weight: bold; color: var(--accent-purple); opacity: 0.3;">05</div>
                    <div class="card-image" style="width: 100%; height: 200px; border-radius: 10px; overflow: hidden; margin-bottom: 20px;">
                        <img src="images/data-nexus.jpg" alt="Data Nexus" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <h3 class="card-title" style="font-size: 24px; margin-bottom: 15px; color: var(--text-primary);">Data Nexus</h3>
                    <p class="card-description" style="color: var(--text-secondary); margin-bottom: 20px; flex-grow: 1;">Big data processing platform capable of analyzing petabytes of information in real-time.</p>
                    <div class="card-tech" style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 20px;">
                        <span class="tech-badge" style="background: var(--accent-purple); color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px;">Apache Spark</span>
                        <span class="tech-badge" style="background: var(--accent-blue); color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px;">Hadoop</span>
                        <span class="tech-badge" style="background: var(--accent-cyan); color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px;">Kafka</span>
                    </div>
                    <button class="card-cta" style="background: linear-gradient(135deg, var(--accent-purple), var(--accent-blue)); color: white; border: none; padding: 12px 24px; border-radius: 8px; cursor: pointer; font-weight: bold; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">Play Game</button>
                </div>
            </div>

            <!-- AR Interface -->
            <div class="stat-card game-card" style="position: relative; transform: none; opacity: 1;">
                <div class="card" style="background: var(--bg-secondary); border-radius: 15px; padding: 30px; height: 100%; display: flex; flex-direction: column;">
                    <div class="card-number" style="position: absolute; top: 20px; right: 20px; font-size: 48px; font-weight: bold; color: var(--accent-purple); opacity: 0.3;">06</div>
                    <div class="card-image" style="width: 100%; height: 200px; border-radius: 10px; overflow: hidden; margin-bottom: 20px;">
                        <img src="images/ar-interface.jpg" alt="AR Interface" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <h3 class="card-title" style="font-size: 24px; margin-bottom: 15px; color: var(--text-primary);">AR Interface</h3>
                    <p class="card-description" style="color: var(--text-secondary); margin-bottom: 20px; flex-grow: 1;">Augmented reality system for immersive data visualization and interactive experiences.</p>
                    <div class="card-tech" style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 20px;">
                        <span class="tech-badge" style="background: var(--accent-purple); color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px;">Unity</span>
                        <span class="tech-badge" style="background: var(--accent-blue); color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px;">ARCore</span>
                        <span class="tech-badge" style="background: var(--accent-cyan); color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px;">Computer Vision</span>
                    </div>
                    <button class="card-cta" style="background: linear-gradient(135deg, var(--accent-purple), var(--accent-blue)); color: white; border: none; padding: 12px 24px; border-radius: 8px; cursor: pointer; font-weight: bold; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">Play Game</button>
                </div>
            </div>

            <!-- IoT Matrix -->
            <div class="stat-card game-card" style="position: relative; transform: none; opacity: 1;">
                <div class="card" style="background: var(--bg-secondary); border-radius: 15px; padding: 30px; height: 100%; display: flex; flex-direction: column;">
                    <div class="card-number" style="position: absolute; top: 20px; right: 20px; font-size: 48px; font-weight: bold; color: var(--accent-purple); opacity: 0.3;">07</div>
                    <div class="card-image" style="width: 100%; height: 200px; border-radius: 10px; overflow: hidden; margin-bottom: 20px;">
                        <img src="images/iot-matrix.jpg" alt="IoT Matrix" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <h3 class="card-title" style="font-size: 24px; margin-bottom: 15px; color: var(--text-primary);">IoT Matrix</h3>
                    <p class="card-description" style="color: var(--text-secondary); margin-bottom: 20px; flex-grow: 1;">Intelligent IoT ecosystem connecting millions of devices with edge computing capabilities.</p>
                    <div class="card-tech" style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 20px;">
                        <span class="tech-badge" style="background: var(--accent-purple); color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px;">MQTT</span>
                        <span class="tech-badge" style="background: var(--accent-blue); color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px;">Edge AI</span>
                        <span class="tech-badge" style="background: var(--accent-cyan); color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px;">5G</span>
                    </div>
                    <button class="card-cta" style="background: linear-gradient(135deg, var(--accent-purple), var(--accent-blue)); color: white; border: none; padding: 12px 24px; border-radius: 8px; cursor: pointer; font-weight: bold; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">Play Game</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-brand">
                <div class="footer-logo">
                    <div class="logo-icon">
                        <div class="logo-prism">
                            <div class="prism-shape"></div>
                        </div>
                    </div>
                    <span class="logo-text">
                        <span class="prism">Game</span>
                        <span class="flux">Vault</span>
                    </span>
                </div>
                <p class="footer-description">
                    Your ultimate destination for premium gaming experiences.
                </p>
                <div class="footer-social">
                    <a href="#" class="social-icon">f</a>
                    <a href="#" class="social-icon">t</a>
                    <a href="#" class="social-icon">in</a>
                    <a href="#" class="social-icon">ig</a>
                </div>
            </div>
            
            <div class="footer-section">
                <h4>Services</h4>
                <div class="footer-links">
                    <a href="library.php">Game Library</a>
                    <a href="#">New Releases</a>
                    <a href="#">Top Rated</a>
                    <a href="#">Categories</a>
                </div>
            </div>
            
            <div class="footer-section">
                <h4>Company</h4>
                <div class="footer-links">
                    <a href="index.php#about">About Us</a>
                    <a href="#">Our Team</a>
                    <a href="#">Careers</a>
                    <a href="#">Press Kit</a>
                </div>
            </div>
            
            <div class="footer-section">
                <h4>Resources</h4>
                <div class="footer-links">
                    <a href="#">Documentation</a>
                    <a href="#">API Reference</a>
                    <a href="#">Blog</a>
                    <a href="#">Support</a>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div class="copyright">
                © 2026 GameVault. All rights reserved.
            </div>
            <div class="footer-credits">
                Designed by <a href="https://templatemo.com" rel="nofollow" target="_blank">TemplateMo</a>
            </div>
        </div>
    </footer>
<script src="main.js"></script>
</body>
</html>

