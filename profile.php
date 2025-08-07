<?php
require 'includes/koneksi.php';
require 'includes/auth_check.php';

$user_id = $_SESSION['user_id'];
$pesan_sukses_profil = '';
$pesan_error_profil = '';
$pesan_sukses_password = '';
$pesan_error_password = '';

// Variabel untuk menentukan tab mana yang aktif
$active_tab = 'profile'; // Default tab

// --- LOGIKA FORM ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Memproses form pembaruan profil
    if (isset($_POST['update_profile'])) {
        $active_tab = 'profile'; // Set tab aktif ke profil
        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);
        $username = trim($_POST['username']);

        if (empty($first_name) || empty($username)) {
            $pesan_error_profil = "Nama Awal dan Username tidak boleh kosong.";
        } else {
            // Cek apakah username baru sudah digunakan oleh user lain
            $stmt_check = mysqli_prepare($koneksi, "SELECT id FROM users WHERE username = ? AND id != ?");
            mysqli_stmt_bind_param($stmt_check, "si", $username, $user_id);
            mysqli_stmt_execute($stmt_check);
            mysqli_stmt_store_result($stmt_check);

            if (mysqli_stmt_num_rows($stmt_check) > 0) {
                $pesan_error_profil = "Username sudah digunakan. Silakan pilih yang lain.";
            } else {
                $stmt_update = mysqli_prepare($koneksi, "UPDATE users SET first_name=?, last_name=?, username=? WHERE id=?");
                mysqli_stmt_bind_param($stmt_update, "sssi", $first_name, $last_name, $username, $user_id);
                if (mysqli_stmt_execute($stmt_update)) {
                    $_SESSION['full_name'] = trim($first_name . ' ' . $last_name);
                    $_SESSION['username'] = $username;
                    $pesan_sukses_profil = "Profil berhasil diperbarui.";
                } else {
                    $pesan_error_profil = "Gagal memperbarui profil.";
                }
            }
        }
    }

    // Memproses form ganti password
    if (isset($_POST['change_password'])) {
        $active_tab = 'password'; // Set tab aktif ke password
        $password_lama = $_POST['password_lama'];
        $password_baru = $_POST['password_baru'];
        $konfirmasi_password = $_POST['konfirmasi_password'];

        $stmt_get_pass = mysqli_prepare($koneksi, "SELECT password FROM users WHERE id = ?");
        mysqli_stmt_bind_param($stmt_get_pass, "i", $user_id);
        mysqli_stmt_execute($stmt_get_pass);
        $result = mysqli_stmt_get_result($stmt_get_pass);
        $user = mysqli_fetch_assoc($result);

        // Langsung bandingkan plain text password
        if ($user && $user['password'] === $password_lama) {
             if ($password_baru !== $konfirmasi_password) {
                $pesan_error_password = "Konfirmasi password baru tidak cocok.";
            } elseif (empty($password_baru)) {
                $pesan_error_password = "Password baru tidak boleh kosong.";
            } else {
                // Simpan password baru sebagai plain text
                $stmt_update_pass = mysqli_prepare($koneksi, "UPDATE users SET password = ? WHERE id = ?");
                mysqli_stmt_bind_param($stmt_update_pass, "si", $password_baru, $user_id);
                if (mysqli_stmt_execute($stmt_update_pass)) {
                    $pesan_sukses_password = "Password berhasil diubah.";
                } else {
                    $pesan_error_password = "Gagal mengubah password.";
                }
            }
        } else {
            $pesan_error_password = "Password lama salah.";
        }
    }
}

// Mengambil data pengguna terkini untuk ditampilkan di form
$stmt_user = mysqli_prepare($koneksi, "SELECT first_name, last_name, username FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt_user, "i", $user_id);
mysqli_stmt_execute($stmt_user);
$current_user_data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_user));

$page_title = "Profil Saya - Uangmu App";
$active_page = 'profile'; 
include 'includes/header.php';
include 'includes/sidebar.php';
?>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Profil Saya</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Profil</li>
        </ol>

        <div class="card mb-4">
            <div class="card-header bg-light d-flex align-items-center">
                <i class="fas fa-user-circle fa-2x me-3 text-primary"></i>
                <div>
                    <h5 class="mb-0"><?= htmlspecialchars($_SESSION['full_name']); ?></h5>
                    <small class="text-muted">@<?= htmlspecialchars($_SESSION['username']); ?></small>
                </div>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= $active_tab == 'profile' ? 'active' : '' ?>" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab">Informasi Profil</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= $active_tab == 'password' ? 'active' : '' ?>" id="password-tab" data-bs-toggle="tab" data-bs-target="#password-tab-pane" type="button" role="tab">Ganti Password</button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade <?= $active_tab == 'profile' ? 'show active' : '' ?>" id="profile-tab-pane" role="tabpanel" tabindex="0">
                        <div class="p-3">
                            <?php if ($pesan_sukses_profil) echo "<div class='alert alert-success'>$pesan_sukses_profil</div>"; ?>
                            <?php if ($pesan_error_profil) echo "<div class='alert alert-danger'>$pesan_error_profil</div>"; ?>
                            <form action="profile.php" method="POST">
                                <input type="hidden" name="update_profile" value="1">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="inputFirstName" class="form-label">Nama Awal</label>
                                        <input class="form-control" id="inputFirstName" name="first_name" type="text" value="<?= htmlspecialchars($current_user_data['first_name']); ?>" required />
                                    </div>
                                    <div class="col-md-6">
                                        <label for="inputLastName" class="form-label">Nama Akhir</label>
                                        <input class="form-control" id="inputLastName" name="last_name" type="text" value="<?= htmlspecialchars($current_user_data['last_name']); ?>" />
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="inputUsername" class="form-label">Username</label>
                                    <input class="form-control" id="inputUsername" name="username" type="text" value="<?= htmlspecialchars($current_user_data['username']); ?>" required />
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan Profil</button>
                            </form>
                        </div>
                    </div>
                    <div class="tab-pane fade <?= $active_tab == 'password' ? 'show active' : '' ?>" id="password-tab-pane" role="tabpanel" tabindex="0">
                        <div class="p-3">
                             <?php if ($pesan_sukses_password) echo "<div class='alert alert-success'>$pesan_sukses_password</div>"; ?>
                             <?php if ($pesan_error_password) echo "<div class='alert alert-danger'>$pesan_error_password</div>"; ?>
                            <form action="profile.php" method="POST">
                                <input type="hidden" name="change_password" value="1">
                                <div class="mb-3">
                                    <label for="inputPasswordLama" class="form-label">Password Lama</label>
                                    <input class="form-control" id="inputPasswordLama" name="password_lama" type="password" required />
                                </div>
                                <div class="mb-3">
                                    <label for="inputPasswordBaru" class="form-label">Password Baru</label>
                                    <input class="form-control" id="inputPasswordBaru" name="password_baru" type="password" required />
                                </div>
                                <div class="mb-3">
                                    <label for="inputKonfirmasiPassword" class="form-label">Konfirmasi Password Baru</label>
                                    <input class="form-control" id="inputKonfirmasiPassword" name="konfirmasi_password" type="password" required />
                                </div>
                                <button type="submit" class="btn btn-danger">Ubah Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
include 'includes/footer.php';
?>