<div class="hero">
    <h1>Welcome to MediShop</h1>
    <p>Safe & Fast Medicine Delivery</p>
</div>

<div class="main-layout">
    <!-- Sidebar Filters -->
    <aside class="filters-sidebar">
        <h3>Categories</h3>
        <ul id="categoryList" class="category-list"></ul>

        <h3>Search</h3>
        <input type="text" id="searchInput" placeholder="Medicine name...">

        <h3>Vendor</h3>
        <input type="text" id="vendorFilter" placeholder="Vendor name...">

        <h3>Type</h3>
        <select id="typeFilter">
            <option value="">All</option>
            <option value="liquid">Liquid</option>
            <option value="solid">Solid</option>
        </select>
    </aside>

    <!-- Medicine Grid -->
    <div id="medicinesGrid" class="medicines-grid">
        <p>Loading medicines...</p>
    </div>
</div>

<script>
// Data passed from controller
const categories = <?= json_encode($categories ?? []) ?>;
const allMedicines = <?= json_encode($medicines ?? []) ?>;

function escapeHtml(str) {
    return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

function renderCategories() {
    let html = '<li><a href="#" onclick="searchMedicines({})">All</a></li>';
    categories.forEach(cat => {
        html += `<li><a href="#" onclick="searchMedicines({category_id:${cat.id}})">${escapeHtml(cat.name)} (${cat.category_type})</a></li>`;
    });
    document.getElementById('categoryList').innerHTML = html;
}

function searchMedicines(extraFilters = {}) {
    let params = new URLSearchParams({
        q: document.getElementById('searchInput').value,
        vendor: document.getElementById('vendorFilter').value,
        category_type: document.getElementById('typeFilter').value,
        ...extraFilters
    });
    fetch(`index.php?controller=home&action=searchAjax&${params}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                renderMedicines(data.medicines, data.isLoggedIn);
            }
        });
}

function renderMedicines(meds, isLoggedIn) {
    const grid = document.getElementById('medicinesGrid');
    if (!meds.length) {
        grid.innerHTML = '<p class="no-results">No medicines found.</p>';
        return;
    }
    let html = '';
    meds.forEach(m => {
        let stockClass = m.availability > 0 ? 'in-stock' : 'out-of-stock';
        let stockText = m.availability > 0 ? `In Stock (${m.availability})` : 'Out of Stock';
        let addButton = '';
        if (m.availability > 0 && isLoggedIn) {
            addButton = `<div class="add-cart">
                            <input type="number" id="qty-${m.id}" value="1" min="1" max="${m.availability}" class="qty-input">
                            <button onclick="addToCart(${m.id})" class="btn-add">Add to Cart</button>
                         </div>`;
        } else if (m.availability > 0) {
            addButton = `<a href="index.php?controller=auth&action=login" class="btn-add login-to-buy">Login to Buy</a>`;
        }
        html += `
            <div class="medicine-card">
                <img src="uploads/medicines/${m.image_path || 'default.png'}" alt="${escapeHtml(m.name)}" class="medicine-image">
                <div class="medicine-info">
                    <div class="medicine-name">${escapeHtml(m.name)}</div>
                    <div class="medicine-vendor">${escapeHtml(m.vendor_name)}</div>
                    <div class="medicine-category">${escapeHtml(m.category_name)} (${m.category_type})</div>
                    <div class="medicine-price">৳${parseFloat(m.price).toFixed(2)}</div>
                    <div class="medicine-stock ${stockClass}">${stockText}</div>
                    ${addButton}
                </div>
            </div>
        `;
    });
    grid.innerHTML = html;
}

function addToCart(medicineId) {
    let qty = document.getElementById(`qty-${medicineId}`).value;
    fetch('index.php?controller=cart&action=addAjax', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `medicine_id=${medicineId}&quantity=${qty}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Added to cart!');
            document.getElementById('cartCount').innerText = data.cart_count;
        } else {
            alert(data.error || 'Failed to add');
        }
    });
}

// Event listeners
document.getElementById('searchInput').addEventListener('input', () => searchMedicines());
document.getElementById('vendorFilter').addEventListener('input', () => searchMedicines());
document.getElementById('typeFilter').addEventListener('change', () => searchMedicines());

renderCategories();
searchMedicines();
</script>