<?php
$currentUser = session('user');
if (!$currentUser) {
    return;
}

$userProfileModel = new \App\Models\UserProfileModel();
$notificationModel = new \App\Models\NotificationModel();

$userId = $currentUser['id'] ?? null;
$profile = $userId ? $userProfileModel->find($userId) : null;
$unreadCount = $userId ? $notificationModel->where('user_id', $userId)
    ->where('is_read', 0)
    ->countAllResults() : 0;

$userName = ($profile && !empty($profile['nama_lengkap'])) ? $profile['nama_lengkap'] : ($currentUser['username'] ?? 'User');
$notificationUrl = match($currentUser['role'] ?? '') {
    'admin' => '#', // Admin belum punya route notifikasi
    'staf' => base_url('/staff/notifikasi'),
    'user' => base_url('/user/notifikasi'),
    default => '#'
};

$profileUrl = match($currentUser['role'] ?? '') {
    'admin' => base_url('/admin/profil'),
    'staf' => base_url('/staff/profil'),
    'user' => base_url('/user/profil'),
    default => '#'
};
?>
<div class="topbar">
    <div class="topbar-content">
        <button class="sidebar-toggle-open" id="sidebarOpenBtn">
            <i class="bi bi-list"></i>
        </button>
        <div class="topbar-left">
            <div class="topbar-weather" id="weather-widget">
                <span class="weather-location"><i class="bi bi-geo-alt-fill" style="color: #ef4444;"></i> Bontomarannu</span>
                <span class="weather-divider" style="margin: 0 8px; color: #ced4da;">|</span>
                <div class="weather-item fade" id="weather-display">Memuat cuaca...</div>
            </div>
        </div>
        <div class="topbar-center">
            <div class="topbar-date">
                <span id="current-date"></span>
                <span id="current-time"></span>
            </div>
        </div>
        <div class="topbar-right">
            <a href="<?= base_url('/') ?>" class="notification-icon" title="Halaman Utama" target="_blank">
                <i class="bi bi-house"></i>
            </a>
            <a href="<?= $notificationUrl ?>" class="notification-icon" title="Notifikasi">
                <i class="bi bi-bell"></i>
                <?php if ($unreadCount > 0): ?>
                    <span class="notification-badge"><?= $unreadCount > 99 ? '99+' : $unreadCount ?></span>
                <?php endif; ?>
            </a>
            <a href="<?= $profileUrl ?>" class="notification-icon" title="Profil Saya">
                <i class="bi bi-person"></i>
            </a>
            <a href="<?= base_url('/logout') ?>" class="notification-icon" title="Keluar" style="color: #ef4444; background-color: #fee2e2;">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    
    function updateDateTime() {
        const now = new Date();
        const day = days[now.getDay()];
        const date = now.getDate();
        const month = months[now.getMonth()];
        const year = now.getFullYear();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        
        const dateElement = document.getElementById('current-date');
        const timeElement = document.getElementById('current-time');
        
        if (dateElement) {
            dateElement.textContent = `${day}, ${date} ${month} ${year}`;
        }
        if (timeElement) {
            timeElement.textContent = `${hours}.${minutes}.${seconds}`;
        }
    }
    
    updateDateTime();
    setInterval(updateDateTime, 1000);
    
    // Weather Widget Logic
    const weatherDisplay = document.getElementById('weather-display');
    let weatherData = [];
    let currentIndex = 0;

    async function fetchWeather() {
        try {
            // Coordinates for Bontomarannu, Bantaeng
            const lat = -5.4388;
            const lon = 119.9325;
            const response = await fetch(`https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current=temperature_2m,relative_humidity_2m,weather_code,wind_speed_10m&timezone=Asia%2FMakassar`);
            const data = await response.json();
            
            if (data.current) {
                const current = data.current;
                
                // Decode WMO weather code (simplified)
                const code = current.weather_code;
                let condition = 'Cerah';
                let icon = 'bi-sun-fill';
                let iconColor = '#f59e0b';
                
                if (code >= 1 && code <= 3) { condition = 'Berawan'; icon = 'bi-cloud-fill'; iconColor = '#64748b'; }
                else if (code >= 45 && code <= 48) { condition = 'Berkabut'; icon = 'bi-cloud-fog-fill'; iconColor = '#94a3b8'; }
                else if (code >= 51 && code <= 67) { condition = 'Hujan'; icon = 'bi-cloud-rain-fill'; iconColor = '#3b82f6'; }
                else if (code >= 71 && code <= 77) { condition = 'Salju'; icon = 'bi-snow'; iconColor = '#93c5fd'; }
                else if (code >= 80 && code <= 82) { condition = 'Hujan Deras'; icon = 'bi-cloud-showers-heavy'; iconColor = '#1d4ed8'; }
                else if (code >= 95 && code <= 99) { condition = 'Badai Petir'; icon = 'bi-cloud-lightning-fill'; iconColor = '#eab308'; }

                weatherData = [
                    `<i class="bi ${icon}" style="color: ${iconColor}; font-size: 15px;"></i> <span>${condition}</span>`,
                    `<i class="bi bi-thermometer-half" style="color: #ef4444; font-size: 15px;"></i> <span>${current.temperature_2m}°C</span>`,
                    `<i class="bi bi-droplet-fill" style="color: #0ea5e9; font-size: 15px;"></i> <span>${current.relative_humidity_2m}%</span>`,
                    `<i class="bi bi-wind" style="color: #64748b; font-size: 15px;"></i> <span>${current.wind_speed_10m} km/j</span>`
                ];
                
                startWeatherRotation();
            }
        } catch (error) {
            console.error('Error fetching weather:', error);
            weatherDisplay.innerHTML = '<i class="bi bi-exclamation-circle"></i> Gagal memuat cuaca';
        }
    }

    function startWeatherRotation() {
        if (weatherData.length === 0) return;
        
        weatherDisplay.innerHTML = weatherData[0];
        weatherDisplay.classList.add('show');
        
        setInterval(() => {
            weatherDisplay.classList.remove('show');
            
            setTimeout(() => {
                currentIndex = (currentIndex + 1) % weatherData.length;
                weatherDisplay.innerHTML = weatherData[currentIndex];
                weatherDisplay.classList.add('show');
            }, 500); 
        }, 3000); 
    }

    fetchWeather();
});
</script>
