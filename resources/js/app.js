import './bootstrap';
import Swal from 'sweetalert2';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
window.Swal = Swal;
Alpine.start();

// Kode Vue 2 yang berkonflik telah dihapus.
// Kita akan mengandalkan Vue 3 yang dimuat dari CDN di template/app.blade.php
// dan inisialisasi spesifik per halaman seperti di tasks/index.blade.php.