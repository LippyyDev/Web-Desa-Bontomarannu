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

    // Navbar scroll effect
    const navbar = document.querySelector('.guest-navbar');
    
    if (navbar) {
        window.addEventListener('scroll', function() {
            const currentScroll = window.pageYOffset;
            
            if (currentScroll > 50) {
                navbar.classList.add('navbar-scrolled');
            } else {
                navbar.classList.remove('navbar-scrolled');
            }
        });
        
        // Trigger once on load to set correct state
        if (window.pageYOffset > 50) {
            navbar.classList.add('navbar-scrolled');
        }
    }
});

