const titleInput = document.querySelector("#title");
const slugInput = document.querySelector("#slug");
const metaInput = document.querySelector("#meta_description");
const metaCount = document.querySelector("#meta_count");

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

const updateMetaCount = () => {
    if (!metaInput || !metaCount) {
        return;
    }

    metaCount.textContent = String(metaInput.value.length);
};

if (slugInput) {
    slugInput.dataset.auto = "true";
    slugInput.addEventListener("input", () => {
        slugInput.dataset.auto = "false";
    });
}

if (titleInput && slugInput) {
    titleInput.addEventListener("input", () => {
        if (slugInput.dataset.auto === "false") {
            return;
        }

        slugInput.value = slugify(titleInput.value);
    });
}

if (metaInput) {
    metaInput.addEventListener("input", updateMetaCount);
    updateMetaCount();
}

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
