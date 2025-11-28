# Tubes2

LINK TRELLO (KANBAN)
https://trello.com/invite/b/69251411024d2f1d9becd695/ATTI15638a2a3aabfa590de802e205cb146623907378/tubes2

LINK UI
https://figma.com

TAHAP INSTALASI IDE DAN PERSIAPAN LINGKUNGAN PENGEMBANGAN

1. Persiapan Bahasa Pemrograman
   - Bahasa utama yang digunakan adalah Java.
   - Menginstal JDK versi 17 sebagai standar proyek.
   - Melakukan verifikasi instalasi lewat perintah "java -version".
   - Mengecek variabel lingkungan PATH dan JAVA_HOME.
   - Menetapkan penggunaan JDK 17 untuk seluruh anggota tim.

2. Instalasi Integrated Development Environment (IDE)
   - IDE yang digunakan: IntelliJ IDEA Community Edition.
   - Mengunduh installer dari situs resmi dan menjalankan instalasi.
   - Mengaktifkan plugin yang dibutuhkan: Maven, Spring Boot, Lombok.
   - Mengonfigurasi workspace awal sesuai standar proyek.

3. Pembuatan dan Setup Proyek Spring Boot
   - Membuat proyek melalui Spring Initializr.
   - Menggunakan Spring Boot versi 3.x.
   - Menambahkan dependensi dasar: Spring Web, DevTools, Spring Data JPA,
     driver database, dan Validation (opsional).
   - Mengimpor proyek ke IDE dan melakukan sinkronisasi Maven.

4. Konfigurasi Dependency Management
   - Memastikan pom.xml memuat seluruh dependency yang dibutuhkan.
   - Melakukan update repository Maven lokal agar dependency terunduh.
   - Menambahkan konfigurasi build seperti encoding UTF-8 dan target Java 17.

5. Pengaturan Spring Bean dan Struktur Paket
   - Menetapkan struktur paket: controller, service, repository, config, model.
   - Mengaktifkan component scanning melalui anotasi @SpringBootApplication.
   - Mengimplementasikan bean menggunakan anotasi: @Component, @Service,
     @Repository, @Configuration.
   - Menyiapkan contoh bean konfigurasi untuk memastikan dependency injection berjalan.

6. Verifikasi Fungsional
   - Melakukan build awal menggunakan perintah "mvn clean install".
   - Menjalankan aplikasi dan memastikan konteks Spring termuat tanpa error.
   - Melakukan testing sederhana terhadap autowiring Spring Bean.
   - Mendokumentasikan hasil verifikasi sebagai baseline lingkungan pengembangan.
