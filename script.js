// INTERAKTIVITAS WEBSITE TOKO BAJU

document.addEventListener('DOMContentLoaded', function() {
    // 1. Toggle Mobile Menu
    setupMobileMenu();
    
    // 2. Form Validation
    setupFormValidation();
    
    // 3. Konfirmasi Hapus
    setupDeleteConfirmation();
    
    // 4. Quantity Input Handler
    setupQuantityHandler();
    
    // 5. Smooth Scroll
    setupSmoothScroll();
    
    // 6. Price Formatter
    formatPrices();
});

// ===== 1. TOGGLE MOBILE MENU =====
function setupMobileMenu() {
    const navToggle = document.querySelector('.nav-toggle');
    const navMenu = document.querySelector('.nav-menu');
    
    if(navToggle) {
        navToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
        });
        
        // Close menu saat klik link
        document.querySelectorAll('.nav-menu a').forEach(link => {
            link.addEventListener('click', function() {
                navMenu.classList.remove('active');
            });
        });
    }
}

// ===== 2. FORM VALIDATION =====
function setupFormValidation() {
    // Validasi form tambah produk
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const inputs = form.querySelectorAll('input[required], textarea[required]');
            let isValid = true;
            
            inputs.forEach(input => {
                if(!input.value.trim()) {
                    isValid = false;
                    input.style.borderColor = '#e74c3c';
                    input.focus();
                } else {
                    input.style.borderColor = '#bdc3c7';
                }
            });
            
            if(!isValid) {
                e.preventDefault();
                alert('Mohon lengkapi semua field yang diperlukan!');
            }
        });
    });
}

// ===== 3. KONFIRMASI HAPUS =====
function setupDeleteConfirmation() {
    const deleteButtons = document.querySelectorAll('.btn-delete, [onclick*="confirm"]');
    
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            if(!confirm('Apakah Anda yakin ingin menghapus?')) {
                e.preventDefault();
            }
        });
    });
}

// ===== 4. QUANTITY INPUT HANDLER =====
function setupQuantityHandler() {
    const quantityInputs = document.querySelectorAll('input[name="jumlah"]');
    
    quantityInputs.forEach(input => {
        // Validasi input angka
        input.addEventListener('change', function() {
            let value = parseInt(this.value);
            const max = this.getAttribute('max');
            const min = this.getAttribute('min') || 1;
            
            if(isNaN(value) || value < min) {
                this.value = min;
            }
            if(max && value > max) {
                this.value = max;
                alert('Stok produk hanya ' + max + ' item');
            }
        });
        
        // Prevent negatif value
        input.addEventListener('keydown', function(e) {
            if(e.key === '-') {
                e.preventDefault();
            }
        });
    });
}

// ===== 5. SMOOTH SCROLL =====
function setupSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if(target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// ===== 6. PRICE FORMATTER =====
function formatPrices() {
    // Format harga agar mudah dibaca
    const priceElements = document.querySelectorAll('.price');
    
    priceElements.forEach(el => {
        const text = el.textContent;
        if(text.includes('Rp')) {
            // Harga sudah terformat, tidak perlu diubah
            return;
        }
    });
}

// ===== UTILITY FUNCTIONS =====

// Format currency ke Rupiah
function formatCurrency(value) {
    return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

// Validasi email
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Hapus dari keranjang dengan AJAX (opsional)
function removeFromCart(productId) {
    if(confirm('Hapus dari keranjang?')) {
        const formData = new FormData();
        formData.append('action', 'hapus');
        formData.append('produk_id', productId);
        
        fetch('keranjang.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            location.reload();
        })
        .catch(error => console.error('Error:', error));
    }
}

// Show notification message
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 25px;
        background-color: ${type === 'success' ? '#27ae60' : '#e74c3c'};
        color: white;
        border-radius: 4px;
        z-index: 9999;
        animation: slideIn 0.3s ease-in-out;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-in-out';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Add animation CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);