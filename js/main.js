// ========================
// Global Helper Functions
// ========================

function escapeHtml(text) {
    if (!text) return '';
    return text.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type}`;
    notification.style.position = 'fixed';
    notification.style.bottom = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.style.padding = '12px 20px';
    notification.style.borderRadius = '8px';
    notification.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
    notification.style.maxWidth = '300px';
    notification.innerText = message;
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 3000);
}

// ========================
// Cart AJAX Functions
// ========================

function addToCart(medicineId) {
    const qtyInput = document.getElementById(`qty-${medicineId}`);
    const quantity = qtyInput ? parseInt(qtyInput.value) : 1;
    
    fetch('index.php?controller=cart&action=addAjax', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `medicine_id=${medicineId}&quantity=${quantity}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Added to cart!', 'success');
            updateCartCount(data.cart_count);
        } else {
            showNotification(data.error || 'Failed to add', 'error');
        }
    })
    .catch(err => showNotification('Error adding to cart', 'error'));
}

function updateCartCount(count) {
    const cartSpans = document.querySelectorAll('#cartCount');
    cartSpans.forEach(span => {
        span.innerText = count || 0;
        if (count > 0) span.style.display = 'inline-block';
        else span.style.display = 'none';
    });
}

function updateCartQuantity(cartId, quantity) {
    if (quantity < 1) {
        removeCartItem(cartId);
        return;
    }
    fetch('index.php?controller=cart&action=updateAjax', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `cart_id=${cartId}&quantity=${quantity}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            showNotification('Update failed', 'error');
        }
    });
}

function removeCartItem(cartId) {
    if (!confirm('Remove this item from cart?')) return;
    fetch('index.php?controller=cart&action=removeAjax', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `cart_id=${cartId}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            updateCartCount(data.cart_count);
            location.reload();
        }
    });
}

// ========================
// Admin: Update Order Status (AJAX)
// ========================

function updateOrderStatus(orderId, status) {
    if (!confirm(`Set order #${orderId} to ${status}?`)) return;
    fetch('index.php?controller=admin&action=updateOrderStatusAjax', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `order_id=${orderId}&status=${status}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showNotification(`Order ${status}`, 'success');
            location.reload();
        } else {
            showNotification('Failed to update order', 'error');
        }
    });
}

// ========================
// Form Validations (Client-side)
// ========================

function validateRegister() {
    const pwd = document.getElementById('password');
    const confirm = document.getElementById('confirm_password');
    if (!pwd || !confirm) return true;
    if (pwd.value.length < 8) {
        alert('Password must be at least 8 characters');
        return false;
    }
    if (pwd.value !== confirm.value) {
        alert('Passwords do not match');
        return false;
    }
    return true;
}

function validateCheckoutAddress() {
    const addr = document.getElementById('address');
    if (!addr || addr.value.trim() === '') {
        alert('Please enter shipping address');
        return false;
    }
    return true;
}

function validatePaymentMethod() {
    const selected = document.querySelector('input[name="payment_method"]:checked');
    if (!selected) {
        alert('Please select a payment method');
        return false;
    }
    return true;
}

// ========================
// Search & Filter (Home Page)
// ========================

function loadMedicines() {
    const searchInput = document.getElementById('searchInput');
    const vendorFilter = document.getElementById('vendorFilter');
    const typeFilter = document.getElementById('typeFilter');
    const categoryFilter = document.getElementById('categoryFilter'); // hidden if exists
    
    let params = new URLSearchParams();
    if (searchInput && searchInput.value) params.append('q', searchInput.value);
    if (vendorFilter && vendorFilter.value) params.append('vendor', vendorFilter.value);
    if (typeFilter && typeFilter.value) params.append('category_type', typeFilter.value);
    if (categoryFilter && categoryFilter.value) params.append('category_id', categoryFilter.value);
    
    fetch(`index.php?controller=home&action=searchAjax&${params.toString()}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Pass isLoggedIn from the JSON response
                renderMedicineGrid(data.medicines, data.isLoggedIn);
            }
        })
        .catch(err => console.error('Search error:', err));
}

function renderMedicineGrid(medicines, isLoggedIn) {
    const grid = document.getElementById('medicinesGrid');
    if (!grid) return;
    
    if (!medicines.length) {
        grid.innerHTML = '<p class="alert alert-error">No medicines found.</p>';
        return;
    }
    
    let html = '';
    medicines.forEach(med => {
        const stockClass = med.availability > 0 ? 'in-stock' : 'out-of-stock';
        const stockText = med.availability > 0 ? `In Stock (${med.availability})` : 'Out of Stock';
        let addButton = '';
        
        // Use the isLoggedIn parameter passed from AJAX
        if (med.availability > 0 && isLoggedIn) {
            addButton = `<div class="add-cart">
                            <input type="number" id="qty-${med.id}" value="1" min="1" max="${med.availability}" class="qty-input">
                            <button onclick="addToCart(${med.id})" class="btn-add">Add to Cart</button>
                         </div>`;
        } else if (med.availability > 0) {
            addButton = `<a href="index.php?controller=auth&action=login" class="btn-add login-to-buy">Login to Buy</a>`;
        }
        
        html += `
            <div class="medicine-card">
                <img src="uploads/medicines/${med.image_path || 'default.png'}" alt="${escapeHtml(med.name)}" class="medicine-image">
                <div class="medicine-info">
                    <div class="medicine-name">${escapeHtml(med.name)}</div>
                    <div class="medicine-vendor">${escapeHtml(med.vendor_name)}</div>
                    <div class="medicine-category">${escapeHtml(med.category_name)} (${med.category_type})</div>
                    <div class="medicine-price">৳${parseFloat(med.price).toFixed(2)}</div>
                    <div class="medicine-stock ${stockClass}">${stockText}</div>
                    ${addButton}
                </div>
            </div>
        `;
    });
    grid.innerHTML = html;
}

// ========================
// Debounce utility
// ========================
function debounce(func, delay) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), delay);
    };
}

// ========================
// DOMContentLoaded – attach events
// ========================
document.addEventListener('DOMContentLoaded', function() {
    // Set logged-in flag for JS (optional, kept for compatibility)
    const isLoggedIn = document.body.getAttribute('data-user') === 'true';
    window.isLoggedIn = isLoggedIn;
    
    // Search/filter events (home page)
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(loadMedicines, 300));
    }
    const vendorFilter = document.getElementById('vendorFilter');
    if (vendorFilter) vendorFilter.addEventListener('input', debounce(loadMedicines, 300));
    const typeFilter = document.getElementById('typeFilter');
    if (typeFilter) typeFilter.addEventListener('change', loadMedicines);
    
    // Global cart count update on every page
    fetch('index.php?controller=cart&action=getCountAjax')
        .then(res => res.json())
        .then(data => {
            if (data.cart_count !== undefined) updateCartCount(data.cart_count);
        })
        .catch(() => {});
    
    // Attach form validations if forms exist
    const regForm = document.querySelector('form[action*="action=register"]');
    if (regForm) {
        regForm.onsubmit = validateRegister;
    }
    const addressForm = document.querySelector('form input[name="step"][value="address"]')?.closest('form');
    if (addressForm) {
        addressForm.onsubmit = validateCheckoutAddress;
    }
    const paymentForm = document.querySelector('form input[name="step"][value="payment"]')?.closest('form');
    if (paymentForm) {
        paymentForm.onsubmit = validatePaymentMethod;
    }
});