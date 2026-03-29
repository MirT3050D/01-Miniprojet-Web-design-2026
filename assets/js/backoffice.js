// Form elements
const titleInput = document.querySelector("#title");
const slugInput = document.querySelector("#slug");
const metaInput = document.querySelector("#meta_description");
const metaCount = document.querySelector("#meta_count");

/**
 * Convert text to URL slug format
 */
const slugify = (value) => {
    if (!value) {
        return "";
    }

    return value
        .toLowerCase()
        .trim()
        .replace(/['"`]/g, "")
        .replace(/[^a-z0-9]+/g, "-")
        .replace(/^-+|-+$/g, "");
};

/**
 * Update meta description character count
 */
const updateMetaCount = () => {
    if (!metaInput || !metaCount) {
        return;
    }

    metaCount.textContent = String(metaInput.value.length);
};

/**
 * Setup slug auto-generation
 */
if (slugInput) {
    slugInput.dataset.auto = "true";
    slugInput.addEventListener("input", () => {
        slugInput.dataset.auto = "false";
    });
}

/**
 * Auto-generate slug from title
 */
if (titleInput && slugInput) {
    titleInput.addEventListener("input", () => {
        if (slugInput.dataset.auto === "false") {
            return;
        }

        slugInput.value = slugify(titleInput.value);
    });
}

/**
 * Update meta count on input
 */
if (metaInput) {
    metaInput.addEventListener("input", updateMetaCount);
    updateMetaCount();
}

/**
 * Initialize TinyMCE editor
 */
if (typeof tinymce !== "undefined") {
    tinymce.init({
        selector: "#content",
        height: 420,
        menubar: false,
        branding: false,
        license_key: "gpl",
        plugins: "lists link image table code",
        toolbar:
            "undo redo | styles | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image table | code",
        content_style:
            "body { font-family: 'Space Grotesk', sans-serif; font-size: 16px; color: #111315; } h2, h3 { font-family: 'Cinzel', serif; }",
    });
}
