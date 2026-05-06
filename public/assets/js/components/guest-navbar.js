/**
 * Guest Navbar Component JavaScript
 * Handles mobile menu toggle and user profile dropdown
 */

document.addEventListener('DOMContentLoaded', function() {
    const navbarToggleBtn = document.getElementById('navbarToggleBtn');
    const navbarMenu = document.querySelector('.navbar-menu');
    const userProfileBtn = document.getElementById('userProfileBtn');
    const userProfileDropdown = document.querySelector('.user-profile-dropdown');
    const userProfileMenu = document.getElementById('userProfileMenu');

    // Mobile Menu Toggle - Disabled karena menu selalu horizontal
    // Menu navigasi sekarang selalu horizontal, tidak ada dropdown

    // User Profile Dropdown Toggle
    if (userProfileBtn && userProfileDropdown) {
        userProfileBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            userProfileDropdown.classList.toggle('active');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!userProfileDropdown.contains(e.target)) {
                userProfileDropdown.classList.remove('active');
            }
        });

        // Close dropdown when clicking on menu item
        if (userProfileMenu) {
            const menuItems = userProfileMenu.querySelectorAll('.profile-menu-item');
            menuItems.forEach(item => {
                item.addEventListener('click', function() {
                    userProfileDropdown.classList.remove('active');
                });
            });
        }
    }

    // Navbar scroll effect (optional - bisa ditambahkan jika ingin navbar berubah saat scroll)
    let lastScroll = 0;
    const navbar = document.querySelector('.guest-navbar');
    
    if (navbar) {
        window.addEventListener('scroll', function() {
            const currentScroll = window.pageYOffset;
            
            // Tambahkan shadow lebih tebal saat scroll
            if (currentScroll > 50) {
                navbar.querySelector('.navbar-content').style.boxShadow = '0 4px 24px rgba(0, 0, 0, 0.12)';
            } else {
                navbar.querySelector('.navbar-content').style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.08)';
            }
            
            lastScroll = currentScroll;
        });
    }
});

