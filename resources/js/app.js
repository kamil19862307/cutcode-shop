import './bootstrap';
import './main';

// Подскажем, что статические файлы находятся по адресу: и снова запустим npm run build
import.meta.glob([
    '../images/**',
    '../fonts/**',
])
