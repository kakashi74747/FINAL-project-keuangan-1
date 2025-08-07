<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div class="sb-sidenav-menu-heading">Utama</div>
                    <a class="nav-link <?php echo ($active_page == 'dashboard') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>index.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Dashboard
                    </a>
                    <a class="nav-link <?php echo ($active_page == 'transactions') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>transactions.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-exchange-alt"></i></div>
                        Transaksi
                    </a>
                    <div class="sb-sidenav-menu-heading">Fitur</div>
                    <a class="nav-link <?php echo ($active_page == 'budgeting') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>budgeting.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-tasks"></i></div>
                        Anggaran
                    </a>
                    <a class="nav-link <?php echo ($active_page == 'assets') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>assets.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-chart-line"></i></div>
                        Aset
                    </a>
                    <a class="nav-link <?php echo ($active_page == 'goals') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>goals.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-bullseye"></i></div>
                        Target
                    </a>
                    <a class="nav-link <?php echo ($active_page == 'debts') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>debts.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-hand-holding-usd"></i></div>
                        Utang/Piutang
                    </a>
                    <a class="nav-link <?php echo ($active_page == 'emergency_fund') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>emergency_fund.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-shield-alt"></i></div>
                        Dana Darurat
                    </a>
                    <a class="nav-link <?php echo ($active_page == 'reports') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>reports.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-chart-bar"></i></div>
                        Laporan
                    </a>
                    <div class="sb-sidenav-menu-heading">Pengaturan</div>
                     <a class="nav-link <?php echo ($active_page == 'accounts') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>accounts.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-wallet"></i></div>
                        Kelola Akun
                    </a>
                    <a class="nav-link <?php echo ($active_page == 'categories') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>categories.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-tags"></i></div>
                        Kelola Kategori
                    </a>
                </div>
            </div>
            <div class="sb-sidenav-footer">
                <div class="small">Masuk sebagai:</div>
                <?php echo htmlspecialchars($_SESSION['full_name']); ?>
            </div>
        </nav>
    </div>
    <div id="layoutSidenav_content">