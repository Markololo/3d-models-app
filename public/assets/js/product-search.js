document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInput");
    const categoryFilter = document.getElementById("categoryFilter");
    const loadingSpinner = document.getElementById("loadingSpinner");

    async function performSearch() {
        const searchTerm = searchInput.value.trim();
        const categoryId = categoryFilter.value;

        loadingSpinner.style.display = "block";
        const products = await fetchProducts(searchTerm, categoryId);
        loadingSpinner.style.display = "none";

        renderProducts(products);
    }

    const debouncedSearch = debounce(performSearch, 300);

    searchInput.addEventListener("input", debouncedSearch);
    categoryFilter.addEventListener("change", performSearch);

    searchInput.addEventListener("keydown", function (e) {
        if (e.key === "Escape") {
            searchInput.value = "";
            categoryFilter.value = "";
            performSearch();
        }
    });
});

function debounce(func, delay) {
    let timeoutId;
    return function (...args) {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => func.apply(this, args), delay);
    };
}

async function fetchProducts(searchTerm, categoryId) {
    try {
        const params = new URLSearchParams();
        if (searchTerm) params.append("q", searchTerm);
        if (categoryId) params.append("category", categoryId);

        const response = await fetch(
            `${window.APP_BASE_URL}/api/products/search?${params.toString()}`
        );

        if (!response.ok) {
            throw new Error(`HTTP Error: ${response.status}`);
        }

        const data = await response.json();
        return data.products || [];
    } catch (error) {
        console.error("Fetch error:", error);
        showError("Failed to load products. Please try again.");
        return [];
    }
}

function showError(message) {
    document.getElementById("searchResults").innerHTML = `
        <div class="col-12">
            <div class="alert alert-danger" role="alert">
                <i class="bi bi-exclamation-triangle"></i> ${message}
            </div>
        </div>
    `;
}
function renderProducts(products) {
    const tbody = document.getElementById("productsTbody");

    // clear table rows
    tbody.innerHTML = "";

    if (!products.length) {
        tbody.innerHTML = `<tr><td colspan="7">No products found</td></tr>`;
        return;
    }

    products.forEach((p) => {
        //  correct image path
        let imgSrc = "";
        if (p.file_path && p.file_path.trim() !== "") {
            imgSrc = `${window.APP_BASE_URL}/uploads/images/${p.file_path}`;
        } else if (p.image_path && p.image_path.trim() !== "") {
            imgSrc = `${window.APP_BASE_URL}/uploads/images/${p.image_path}`;
        } else {
            imgSrc = `${window.APP_BASE_URL}/assets/imageAssets/imagePlaceholder.jpg`;
        }

        tbody.innerHTML += `
            <tr>
                <td><img src="${escapeHtml(
                    imgSrc
                )}" class="img-fluid" style="max-width: 10vw; object-fit: cover;"></td>
                <td>${p.id}</td>
                <td>${escapeHtml(p.name || "")}</td>
                <td>${escapeHtml(p.description || "")}</td>
                <td>${escapeHtml(String(p.price ?? ""))}</td>
                <td>${escapeHtml(String(p.stock_quantity ?? ""))}</td>
                <td>
                    <a href="products/edit/${
                        p.id
                    }" class="btn btn-success">Edit</a>
                    <a href="products/delete/${p.id}" class="btn btn-danger"
                       onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                    <a href="products/show/${
                        p.id
                    }" class="btn btn-info">View</a>
                </td>
            </tr>
        `;
    });
}

function createProductCard(product) {
    const col = document.createElement("div");
    col.className = "col-md-4 mb-4";

    const description =
        product.description.length > 100
            ? product.description.substring(0, 100) + "..."
            : product.description;

    col.innerHTML = `
        <div class="card h-100">
            <img
                src="${escapeHtml(
                    product.image_path || "/images/placeholder.jpg"
                )}"
                class="card-img-top"
                alt="${escapeHtml(product.name)}"
                style="height: 200px; object-fit: cover;"
            >
            <div class="card-body">
                <h5 class="card-title">${escapeHtml(product.name)}</h5>
                <p class="card-text">${escapeHtml(description)}</p>
                <p class="fw-bold text-success">$${parseFloat(
                    product.price
                ).toFixed(2)}</p>
                <span class="badge bg-secondary">${escapeHtml(
                    product.category_name || "Uncategorized"
                )}</span>
            </div>
            <div class="card-footer">
                <a href="/products/${
                    product.id
                }" class="btn btn-primary btn-sm">View Details</a>
            </div>
        </div>
    `;

    return col;
}

function escapeHtml(text) {
    const div = document.createElement("div");
    div.textContent = text;
    return div.innerHTML;
}
