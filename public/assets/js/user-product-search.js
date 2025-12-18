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
    const resultsContainer = document.getElementById("searchResults");
    const defaultContainer = document.getElementById("defaultProducts");

    if (defaultContainer) defaultContainer.style.display = "none";
    resultsContainer.innerHTML = "";

    if (products.length === 0) {
        resultsContainer.innerHTML = `
            <div class="col-12">
                <div class="alert alert-info" role="alert">
                    <i class="bi bi-info-circle"></i> No products found matching your search.
                </div>
            </div>
        `;
        return;
    }

    products.forEach((product) => {
        resultsContainer.appendChild(createProductCard(product));
    });
}

function createProductCard(product) {
    const col = document.createElement("div");
    col.className = "col-md-4 mb-4";

    const description =
        product.description && product.description.length > 100
            ? product.description.substring(0, 100) + "..."
            : product.description || "";

    // Use image_path if exists, otherwise placeholder
    let imagePath = "";
    if (product.file_path && product.file_path.trim() !== "") {
        imagePath = `${window.APP_BASE_URL}/uploads/images/${product.file_path}`;
    } else if (product.image_path && product.image_path.trim() !== "") {
        imagePath = `${window.APP_BASE_URL}/uploads/images/${product.image_path}`;
    } else {
        imagePath = `${window.APP_BASE_URL}/assets/imageAssets/imagePlaceholder.jpg`;
    }

    col.innerHTML = `
        <div class="card h-100">
            <img
                src="${escapeHtml(imagePath)}"
                class="card-img-top"
                alt="${escapeHtml(product.name)}"
                style="style="max-width: 15vw object-fit: cover;"
            >
            <div class="card-body">
                <h5 class="card-title">${escapeHtml(product.name)}</h5>
                <p class="card-text">${escapeHtml(description)}</p>
                <p class="fw-bold text-success">$${parseFloat(
                    product.price || 0
                ).toFixed(2)}</p>
                <span class="badge bg-secondary">${escapeHtml(
                    product.category_name || "Uncategorized"
                )}</span>
            </div>
            <div class="card-footer">
                <a href="${window.APP_BASE_URL}/user/products/${
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
