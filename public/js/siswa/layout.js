// Mobile Menu Elements
const mobileMenuToggle = document.getElementById('mobileMenuToggle');
const sidebar = document.getElementById('sidebar');
const sidebarClose = document.getElementById('sidebarClose');
const sidebarOverlay = document.getElementById('sidebarOverlay');

// Function to open sidebar
function openSidebar() {
    sidebar.classList.add('active');
    sidebarOverlay.classList.add('active');
    mobileMenuToggle.classList.add('hide'); // Hide hamburger
}

// Function to close sidebar
function closeSidebar() {
    sidebar.classList.remove('active');
    sidebarOverlay.classList.remove('active');
    mobileMenuToggle.classList.remove('hide'); // Show hamburger
}

// Open sidebar with hamburger button
if (mobileMenuToggle) {
    mobileMenuToggle.addEventListener('click', function() {
        openSidebar();
    });
}

// Close sidebar with close button
if (sidebarClose) {
    sidebarClose.addEventListener('click', function() {
        closeSidebar();
    });
}

// Close sidebar when clicking overlay
if (sidebarOverlay) {
    sidebarOverlay.addEventListener('click', function() {
        closeSidebar();
    });
}

// Close sidebar when clicking outside
document.addEventListener('click', function(event) {
    const isClickInside = sidebar.contains(event.target) || 
                          mobileMenuToggle.contains(event.target);
    
    if (!isClickInside && window.innerWidth <= 768 && sidebar.classList.contains('active')) {
        closeSidebar();
    }
});

// Handle window resize
window.addEventListener('resize', function() {
    if (window.innerWidth > 768) {
        closeSidebar();
    }
});
