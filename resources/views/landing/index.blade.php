<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>SIPPA</title>
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="google-site-verification" content="GQp5vW-9Ib8kKhKg99rUe09s-Mf2jGEQXPhCfX_YQt4" />

  <!-- Favicons -->
  <link href="{{ asset('landing/assets/img/icon-favicon.jpg')}}" rel="icon">
  <link href="{{ asset('landing/assets/img/icon-favicon.jpg') }}" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('landing/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('landing/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('landing/assets/vendor/aos/aos.css') }}" rel="stylesheet">
  <link href="{{ asset('landing/assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ asset('landing/assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="{{ asset('landing/assets/css/main.css') }}" rel="stylesheet">
</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

      <a href="#hero" class="logo d-flex align-items-center me-auto">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.png" alt=""> -->
        <img src="{{ asset('landing/assets/img/icon-favicon.jpg')}}" alt="">
        <h1 class="sitename">SIPPA</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero" class="active">Home</a></li>
          <li><a href="#about">About</a></li>
          <li><a href="#services">Services</a></li>
          <li><a href="#faq">FAQ</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <a class="btn-getstarted" href="/login">Login</a>

    </div>
  </header>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section">

      <img src="{{ asset('landing/assets/img/hero-bg-abstract.jpg')}}" alt="" data-aos="fade-in" class="">

      <div class="container">
        <div class="row justify-content-center" data-aos="zoom-out">
          <div class="col-xl-7 col-lg-9 text-center">
            <h1>SIPPA</h1>
            <p>Sistem Informasi Pengajuan Pengembangan Aplikasi</p>
          </div>
        </div>
        <div class="text-center" data-aos="zoom-out" data-aos-delay="100">
          <a href="/login" class="btn-get-started">Mulai Pengajuan</a>
        </div>

        <div class="row gy-4 mt-5">
          <div class="col-md-6 col-lg-3" data-aos="zoom-out" data-aos-delay="100">
            <div class="icon-box">
              <div class="icon"><i class="bi bi-cloud"></i></div>
              <h4 class="title"><a href="">Pengajuan digital</a></h4>
              <p class="description">Form input pengajuan yang mudah dan paperless</p>
            </div>
          </div>
          <!--End Icon Box -->

          <div class="col-md-6 col-lg-3" data-aos="zoom-out" data-aos-delay="200">
            <div class="icon-box">
              <div class="icon"><i class="bi bi-check-circle"></i></div>
              <h4 class="title"><a href="">Approval Berjenjang</a></h4>
              <p class="description">Sistem persetujuan otomatis oleh epartemen terkait</p>
            </div>
          </div>
          <!--End Icon Box -->

          <div class="col-md-6 col-lg-3" data-aos="zoom-out" data-aos-delay="300">
            <div class="icon-box">
              <div class="icon"><i class="bi bi-activity"></i></div>
              <h4 class="title"><a href="">Real-time Monitoring</a></h4>
              <p class="description">Pantau status pengerjaan aplikasi kapan saja</p>
            </div>
          </div>
          <!--End Icon Box -->

          <div class="col-md-6 col-lg-3" data-aos="zoom-out" data-aos-delay="400">
            <div class="icon-box">
              <div class="icon"><i class="bi bi-database"></i></div>
              <h4 class="title"><a href="">Arsip Terpusat</a></h4>
              <p class="description">Riwayat pengajuan dan dokumentasi tersimpan aman</p>
            </div>
          </div>
          <!--End Icon Box -->

        </div>
      </div>

    </section><!-- /Hero Section -->

    <!-- About Section -->
    <section id="about" class="about section">

      <div class="container section-title" data-aos="fade-up">
        <h2>About</h2>
        <p>Mendukung Transformasi Digital di PT Pupuk Kujang Cikampek</p>
      </div>

      <div class="container">
        <div class="row gy-4">

          <div class="col-lg-6 content" data-aos="fade-up" data-aos-delay="100">
            <p>
              <strong>SIPPA</strong> merupakan platform terintegrasi yang berfungsi sebagai jembatan komunikasi antara
              unit
              kerja dengan Departemen TI PT Pupuk Kujang. Fokus utama sistem ini adalah
              memastikan
              setiap tahapan pengajuan berjalan dengan baik.
            </p>
            <ul>
              <li><i class="bi bi-check2-circle"></i> <span>Digitalisasi alur kerja guna mengurangi penggunaan dokumen
                  fisik
                  (Paperless).</span></li>
              <li><i class="bi bi-check2-circle"></i> <span>Akselerasi koordinasi dan birokrasi antar unit kerja secara
                  efisien.</span></li>
              <li><i class="bi bi-check2-circle"></i> <span>Transparansi monitoring status pengembangan aplikasi secara
                  real-time.</span></li>
            </ul>
          </div>

          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
            <p>
              Sistem ini dirancang untuk menyelaraskan kebutuhan inovasi digital unit kerja dengan dukungan teknis yang
              terukur. Dengan adanya SIPA, setiap permintaan pengembangan aplikasi dikelola secara profesional sesuai
              dengan
              standar tata kelola TI perusahaan, menjamin akuntabilitas serta kualitas pada hasil akhir perangkat lunak
              yang
              dikembangkan.
            </p>
            <a href="#services" class="read-more"><span>Lihat Layanan</span><i class="bi bi-arrow-right"></i></a>
          </div>

        </div>
      </div>

    </section>


    <!-- Services Section -->
    <section id="services" class="services section light-background">

      <div class="container section-title" data-aos="fade-up">
        <h2>Services</h2>
        <p>Fungsionalitas utama SIPA dalam mendukung siklus pengembangan aplikasi di PT Pupuk Kujang</p>
      </div>
      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="service-item item-cyan position-relative">
              <div class="icon">
                <svg width="100" height="100" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg">
                  <path stroke="none" stroke-width="0" fill="#f5f5f5"
                    d="M300,521.0016835830174C376.1290562159157,517.8887921683347,466.0731472004068,529.7835943286574,510.70327084640275,468.03025145048787C554.3714126377745,407.6079735673963,508.03601936045806,328.9844924480964,491.2728898941984,256.3432110539036C474.5976632858925,184.082847569629,479.9380746630129,96.60480741107993,416.23090153303,58.64404602377083C348.86323505073057,18.502131276798302,261.93793281208167,40.57373210992963,193.5410806939664,78.93577620505333C130.42746243093433,114.334589627462,98.30271207620316,179.96522072025542,76.75703585869454,249.04625023123273C51.97151888228291,328.5150500222984,13.704378332031375,421.85034740162234,66.52175969318436,486.19268352777647C119.04800174914682,550.1803526380478,217.28368757567262,524.383925680826,300,521.0016835830174">
                  </path>
                </svg>
                <i class="bi bi-file-earmark-plus"></i>
              </div>
              <a href="#" class="stretched-link">
                <h3>Pengajuan Digital</h3>
              </a>
              <p>Input pengajuan pengembangan aplikasi secara digital melalui form yang telah tervalidasi oleh sistem
                SIPPA.</p>
            </div>
          </div>

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="service-item item-orange position-relative">
              <div class="icon">
                <svg width="100" height="100" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg">
                  <path stroke="none" stroke-width="0" fill="#f5f5f5"
                    d="M300,582.0697525312426C382.5290701553225,586.8405444964366,449.9789794690241,525.3245884688669,502.5850820975895,461.55621195738473C556.606425686781,396.0723002908107,615.8543463187945,314.28637112970534,586.6730223649479,234.56875336149918C558.9533121215079,158.8439757836574,454.9685369536778,164.00468322053177,381.49747125262974,130.76875717737553C312.15926192815925,99.40240125094834,248.97055460311594,18.661163978235184,179.8680185752513,50.54337015887873C110.5421016452524,82.52863877960104,119.82277516462835,180.83849132639028,109.12597500060166,256.43424936330496C100.08760227029461,320.3096726198365,92.17705696193138,384.0621239912766,124.79988738764834,439.7174275375508C164.83382741302287,508.01625554203684,220.96474134820875,577.5009287672846,300,582.0697525312426">
                  </path>
                </svg>
                <i class="bi bi-shield-check"></i>
              </div>
              <a href="#" class="stretched-link">
                <h3>Approval</h3>
              </a>
              <p>Alur persetujuan berjenjang secara otomatis untuk menjamin akuntabilitas setiap
                pengajuan
                aplikasi.</p>
            </div>
          </div>

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="service-item item-teal position-relative">
              <div class="icon">
                <svg width="100" height="100" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg">
                  <path stroke="none" stroke-width="0" fill="#f5f5f5"
                    d="M300,541.5067337569781C382.14930387511276,545.0595476570109,479.8736841581634,548.3450877840088,526.4010558755058,480.5488172755941C571.5218469581645,414.80211281144784,517.5187510058486,332.0715597781072,496.52539010469104,255.14436215662573C477.37192572678356,184.95920475031193,473.57363656557914,105.61284051026155,413.0603344069578,65.22779650032875C343.27470386102294,18.654635553484475,251.2091493199835,5.337323636656869,175.0934190732945,40.62881213300186C97.87086631185822,76.43348514350839,51.98124368387456,156.15599469081315,36.44837278890362,239.84606092416172C21.716077023791087,319.22268207091537,43.775223500013084,401.1760424656574,96.891909868211,461.97329694683043C147.22146801428983,519.5804099606455,223.5754009179313,538.201503339737,300,541.5067337569781">
                  </path>
                </svg>
                <i class="bi bi-speedometer2"></i>
              </div>
              <a href="#" class="stretched-link">
                <h3>Real-time Tracking</h3>
              </a>
              <p>Pemohon dapat memantau status pengajuan
              </p>
            </div>
          </div>
        </div>

      </div>

    </section>

    <!-- FAQ Section -->
    <section id="faq" class="faq section">

      <div class="container section-title" data-aos="fade-up">
        <h2>Frequently Asked Questions</h2>
        <p>Pertanyaan yang Sering Diajukan</p>
      </div>

      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-10" data-aos="fade-up" data-aos-delay="100">

            <div class="accordion accordion-flush" id="faq-accordion">

              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed fw-bold" style="color: var(--accent-color);" type="button"
                    data-bs-toggle="collapse" data-bs-target="#faq-1">
                    Apa itu SIPPA dan apa tujuannya?
                  </button>
                </h2>
                <div id="faq-1" class="accordion-collapse collapse" data-bs-parent="#faq-accordion">
                  <div class="accordion-body">
                    SIPPA adalah Sistem Informasi Pengajuan Pengembangan Aplikasi yang dirancang untuk mendigitalisasi
                    alur
                    kerja pengajuan aplikasi dari unit kerja ke Departemen TI PT Pupuk Kujang, guna menciptakan proses
                    yang
                    transparan dan efisien.
                  </div>
                </div>
              </div>

              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed fw-bold" style="color: var(--accent-color);" type="button"
                    data-bs-toggle="collapse" data-bs-target="#faq-2">
                    Bagaimana cara mendapatkan akses masuk ke SIPPA?
                  </button>
                </h2>
                <div id="faq-2" class="accordion-collapse collapse" data-bs-parent="#faq-accordion">
                  <div class="accordion-body">
                    Akses diberikan kepada karyawan melalui akun internal perusahaan. Jika belum memiliki akun, silakan
                    hubungi admin
                    Departemen TI untuk pendaftaran.
                  </div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed fw-bold" style="color: var(--accent-color);" type="button"
                    data-bs-toggle="collapse" data-bs-target="#faq-3">
                    Siapa yang dapat menggunakan sistem SIPPA?
                  </button>
                </h2>
                <div id="faq-3" class="accordion-collapse collapse" data-bs-parent="#faq-accordion">
                  <div class="accordion-body">
                    Sistem ini ditujukan bagi seluruh karyawan atau perwakilan unit kerja di PT Pupuk Kujang yang
                    memerlukan
                    dukungan pengembangan perangkat lunak dalam mendukung operasional bisnis.
                  </div>
                </div>
              </div>

              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed fw-bold" style="color: var(--accent-color);" type="button"
                    data-bs-toggle="collapse" data-bs-target="#faq-4">
                    Bagaimana cara mengajukan permintaan pengembangan aplikasi?
                  </button>
                </h2>
                <div id="faq-4" class="accordion-collapse collapse" data-bs-parent="#faq-accordion">
                  <div class="accordion-body">
                    Pengguna login ke sistem, mengisi formulir pengajuan, melengkapi data yang diperlukan, kemudian
                    mengirimkan pengajuan
                    untuk diproses.
                  </div>
                </div>
              </div>



              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed fw-bold" style="color: var(--accent-color);" type="button"
                    data-bs-toggle="collapse" data-bs-target="#faq-5">
                    Bagaimana cara memantau status pengajuan?
                  </button>
                </h2>
                <div id="faq-5" class="accordion-collapse collapse" data-bs-parent="#faq-accordion">
                  <div class="accordion-body">
                    Status pengajuan dapat dilihat pada halaman daftar pengajuan atau dashboard
                  </div>
                </div>
              </div>

              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed fw-bold" style="color: var(--accent-color);" type="button"
                    data-bs-toggle="collapse" data-bs-target="#faq-6">
                    Apa yang harus dilakukan jika pengajuan saya direvisi?
                  </button>
                </h2>
                <div id="faq-6" class="accordion-collapse collapse" data-bs-parent="#faq-accordion">
                  <div class="accordion-body">
                    Pemohon dapat membuka detail pengajuan, membaca catatan revisi, memperbaiki data, lalu mengirimkan
                    kembali pengajuan.
                  </div>
                </div>
              </div>

              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed fw-bold" style="color: var(--accent-color);" type="button"
                    data-bs-toggle="collapse" data-bs-target="#faq-7">
                    Apa yang harus dilakukan jika pengajuan saya ditolak?
                  </button>
                </h2>
                <div id="faq-7" class="accordion-collapse collapse" data-bs-parent="#faq-accordion">
                  <div class="accordion-body">
                    Jika pengajuan ditolak, sistem akan menampilkan alasan penolakan. Sehingga pemohon dapat memahami
                    penyebabnya dan mengajukan kembali apabila diperlukan.
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>

    </section>
    <!-- /FAQ Section -->
  </main>

  <footer id="footer" class="footer light-background">

    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-5 col-md-12 footer-about">
          <a href="index.html" class="logo d-flex align-items-center">
            <span class="sitename">SIPPA</span>
          </a>
          <p>Sistem Informasi Pengajuan Pengembangan Aplikasi (SIPPA) merupakan solusi digital terintegrasi untuk
            mengoptimalkan tata kelola pengajuan pengembangan aplikasi secara digital di lingkungan PT Pupuk Kujang.</p>
        </div>

        <div class="col-lg-2 col-6 footer-links">
          <h4>Tautan Penting</h4>
          <ul>
            <li><a href="#hero">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#services">Services</a></li>
            <li><a href="#faq">FAQ</a></li>
            <li><a href="/login">Login</a></li>
          </ul>
        </div>

        <div class="col-lg-2 col-6 footer-links">
          <h4>Layanan Kami</h4>
          <ul>
            <li><a href="#">Pengajuan Digital</a></li>
            <li><a href="#">Approval</a></li>
            <li><a href="#">Real-time Tracking</a></li>
          </ul>
        </div>
      </div>
    </div>

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">PT Pupuk Kujang</strong> <span>All Rights
          Reserved</span>
      </p>
      <div class="credits">
        Dikembangkan untuk <strong>Departemen Teknologi Informasi PT Pupuk Kujang Cikampek</strong>
      </div>
    </div>
  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="{{ asset('landing/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('landing/assets/vendor/php-email-form/validate.js') }}"></script>
  <script src="{{ asset('landing/assets/vendor/aos/aos.js') }}"></script>
  <script src="{{ asset('landing/assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
  <script src="{{ asset('landing/assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ asset('landing/assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
  <script src="{{ asset('landing/assets/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
  <script src="{{ asset('landing/assets/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>

  <!-- Main JS File -->
  <script src="{{ asset('landing/assets/js/main.js') }}"></script>


</body>

</html>