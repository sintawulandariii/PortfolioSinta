<?php
include 'konek/koneksi.php';
$statusPesan = ''; // Variabel untuk menyimpan status pengiriman pesan

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kirim'])) {
    // Ambil data dari form
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $pesan = mysqli_real_escape_string($koneksi, $_POST['pesan']);
    $waktu_dikirim = date('Y-m-d H:i:s'); // Timestamp saat data dikirim

    // Query untuk memasukkan data ke dalam tabel 'find_me'
    $queryInsertPesan = "INSERT INTO find_me (name, email, message, created_at, id_user) 
                         VALUES ('$nama', '$email', '$pesan', '$waktu_dikirim', 1)";

    if (mysqli_query($koneksi, $queryInsertPesan)) {
        $statusPesan = 'Pesan Anda telah terkirim!';
    } else {
        $statusPesan = 'Terjadi kesalahan: ' . mysqli_error($koneksi);
    }

    // Redirect untuk mencegah kotak dialog muncul kembali saat refresh
    header("Location: index.php?status=" . urlencode($statusPesan));
    exit;
}

// Tangkap status dari URL (jika ada)
if (isset($_GET['status'])) {
    $statusPesan = urldecode($_GET['status']);
}

// Mengambil data pengguna
$queryUser = "SELECT * FROM users WHERE id_user = 1"; // Misal kita ambil user dengan ID 1
$hasilUser = mysqli_query($koneksi, $queryUser);
$dataPengguna = mysqli_fetch_assoc($hasilUser);

// Mengambil data pendidikan
$queryPendidikan = "SELECT * FROM education WHERE id_user = 1";
$hasilPendidikan = mysqli_query($koneksi, $queryPendidikan);

// Mengambil data pengalaman
$queryPengalaman = "SELECT * FROM experience WHERE id_user = 1";
$hasilPengalaman = mysqli_query($koneksi, $queryPengalaman);

// Mengambil data keterampilan
$queryKeterampilan = "SELECT * FROM skills WHERE id_user = 1";
$hasilKeterampilan = mysqli_query($koneksi, $queryKeterampilan);

// Mengambil data portfolio
$queryPortofolio = "SELECT * FROM portfolio WHERE id_user = 1";
$hasilPortofolio = mysqli_query($koneksi, $queryPortofolio);

// Query untuk mengambil data dari tabel 'find_me'
$queryFindMe = "SELECT * FROM find_me WHERE id_user = 1 ORDER BY id_find_me ASC";
$hasilFindMe = mysqli_query($koneksi, $queryFindMe);

$koneksi->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>3D Art - Biodata</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="logo-sticky">3D</div>
        <!-- Navbar -->
        <nav>
        <ul>
                <li><a href="#biodata">Biodata</a></li>
                <li><a href="#education">Education</a></li>
                <li><a href="#experience">Experience</a></li>
                <li><a href="#skills">Skills</a></li>
                <li><a href="#portfolio">Portfolio</a></li>
                <li><a href="#findme">Find Me</a></li>
            </ul>
        </nav>
    </header>

    <!-- Biodata Section -->
    <section id="biodata">
        <div class="intro">
            <h1>I'm a <span class="hollow-text"><?php echo $dataPengguna['profession']; ?></span></h1>
            <p><?php echo $dataPengguna['bio']; ?></p>
        </div>
        <div class="profile-picture">
            <img class="pulse" src="<?php echo $dataPengguna['profile_picture']; ?>" alt="Foto Profil">
        </div>
    </section>

    <!-- Pendidikan Section -->
    <section id="education">
        <h2>Education</h2>
        <div class="card-container-edu">
            <?php while ($pendidikan = mysqli_fetch_assoc($hasilPendidikan)) { ?>
                <div class="cardedu">
                    <div class="card-content">
                        <div class="text-content">
                            <h4><?php echo $pendidikan['institution_name']; ?></h4>
                            <h5><?php echo nl2br($pendidikan['major']); ?></h5>
                            <p><?php echo $pendidikan['start_year']; ?> - <?php echo $pendidikan['end_year']; ?></p>
                        </div>
                        <div class="image-content">
                            <img src="<?php echo $pendidikan['image']; ?>" alt="Logo Sekolah">
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>

    <!-- Pengalaman Section -->
    <section id="experience">
        <h2>Experience</h2>
        <div class="card-container">
            <?php foreach ($hasilPengalaman as $pengalaman) { ?>
                <div class="card center-card">
                    <img src="<?php echo $pengalaman['image']; ?>" alt="Gambar Pengalaman">
                    <h3><?php echo $pengalaman['position']; ?></h3><br>
                    <h4><?php echo $pengalaman['year']; ?></h4><br>
                    <p><?php echo $pengalaman['description']; ?></p>
                </div>
            <?php } ?>
        </div>
    </section>

    <!-- Keterampilan Section -->
    <section id="skills">
        <h2>Skills</h2>
        <div class="card-container-skills">
            <?php foreach ($hasilKeterampilan as $keterampilan) { ?>
                <div class="card2">
                    <i class="<?php echo $keterampilan['icon']; ?>"></i>
                    <h3 class="card-subtitle"><?php echo $keterampilan['skill_name']; ?></h3>
                    <p class="card-text"><?php echo $keterampilan['skill_description']; ?></p>
                </div>
            <?php } ?>
        </div>
    </section>

    <!-- Portofolio Section -->
    <section id="portfolio">
        <h2>Portfolio</h2>
        <div class="portfolio-container">
            <?php foreach ($hasilPortofolio as $portofolio) { ?>
                <div class="portfolio-card">
                    <img src="<?php echo $portofolio['image']; ?>" alt="Gambar Portofolio" class="card-image">
                    <h3 class="card-subtitle2"><?php echo $portofolio['title']; ?></h3>
                </div>
            <?php } ?>
        </div>
    </section>

    <!-- Find Me Section -->
    <section id="findme">
        <div class="content-findme">
            <h1>Find Me On</h1>
            <h2>Your vision matters to us.<br>Let’s connect and make it<br>happen!</h2>
            <div class="social-icons">
                <?php foreach ($hasilFindMe as $findMe) { ?>
                    <?php if (!empty($findMe['url']) && !empty($findMe['icon'])) { ?>
                        <a href="<?php echo $findMe['url']; ?>" class="social-btn" target="_blank">
                            <i class="<?php echo $findMe['icon']; ?>"></i>
                        </a>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
        <div class="right-column">
    <form class="contact-form" method="POST" action="">
        <input type="text" name="name" placeholder="Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <textarea name="message" placeholder="Message" required></textarea>
        <button type="submit" name="submit">Send Message</button>
    </form>
</div>
    </section>

    <footer class="footer">
    <p>Copyright &#169; 2024 Sinta Wulandari 3D Art | All rights reserved.</p>
    </footer> 

    <?php if ($statusPesan != ''): ?>
        <script type="text/javascript">
            alert("<?php echo $statusPesan; ?>");
        </script>
    <?php endif; ?>
</body>
</html>
