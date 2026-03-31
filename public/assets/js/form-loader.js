document.addEventListener("DOMContentLoaded", function () {

    // ================= AUTO FORM LOADER =================
    document.querySelectorAll("form").forEach(function (form) {
        form.addEventListener("submit", function (event) {
            const button = form.querySelector(
                'button[type="submit"].btn-loader, input[type="submit"].btn-loader, button[type="submit"]:not(.btn-link-loader), input[type="submit"]:not(.btn-link-loader)',
            );

            if (!button) return;

            // jika sudah loading hentikan
            if (button.classList.contains("loading")) {
                event.preventDefault();
                return;
            }

            // cek validasi HTML5
            if (!form.checkValidity()) {
                form.reportValidity();
                event.preventDefault();
                return;
            }

            // confirm jika ada data-confirm
            const message = button.dataset.confirm;
            if (message) {
                if (!confirm(message)) {
                    event.preventDefault();
                    return;
                }
            }

            // aktifkan loader
            activateButtonLoader(button);
        });
    });

    // ================= LINK LOADER WITH CONFIRMATION =================
    document.querySelectorAll("a.btn-link-loader, a.btn").forEach(function (link) {
        link.addEventListener("click", function (event) {
            // Skip jika sudah loading
            if (this.classList.contains("loading")) {
                event.preventDefault();
                return;
            }

            // Check confirmation jika ada data-confirm
            const message = this.dataset.confirm;
            if (message) {
                if (!confirm(message)) {
                    event.preventDefault();
                    return;
                }
            }

            // Aktifkan loader jika bukan modal trigger
            if (!this.hasAttribute('data-bs-toggle') && !this.hasAttribute('data-toggle')) {
                activateLinkLoader(this);
            }
        });
    });

    // ================= BUTTON LOADER (NON-FORM) =================
    document.querySelectorAll("button.btn-loader:not([type='submit']), button.btn:not([type='submit'])").forEach(function (button) {
        button.addEventListener("click", function (event) {
            // Skip jika sudah loading
            if (this.classList.contains("loading")) {
                event.preventDefault();
                return;
            }

            // Skip jika punya onclick handler (function based)
            if (this.getAttribute('onclick')) {
                // Izinkan onclick handler berjalan
                return;
            }

            // Check confirmation jika ada data-confirm
            const message = this.dataset.confirm;
            if (message) {
                if (!confirm(message)) {
                    event.preventDefault();
                    return;
                }
            }

            // Aktifkan loader
            activateButtonLoader(this);
        });
    });

    // ================= HELPER FUNCTION: ACTIVATE BUTTON LOADER =================
    function activateButtonLoader(button, timeout = 1000) {
        if (button.classList.contains("loading")) return;

        button.classList.add("loading");
        button.disabled = true;

        const text = button.innerHTML.trim();
        const loadingText = button.dataset.loadingText || button.textContent.trim();

        button.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2"></span>
            ${loadingText}
        `;

        // Simpan original HTML untuk restore
        button.dataset.originalHtml = text;

        setTimeout(() => {
            if (button.classList.contains("loading")) {
                button.classList.remove("loading");
                button.disabled = false;
                button.innerHTML = button.dataset.originalHtml || text;
            }
        }, timeout);
    }

    // ================= HELPER FUNCTION: ACTIVATE LINK LOADER =================
    function activateLinkLoader(link, timeout = 1000) {
        if (link.classList.contains("loading")) return;

        link.classList.add("loading");
        link.style.pointerEvents = "none";

        const text = link.innerHTML.trim();
        const loadingText = link.dataset.loadingText || link.textContent.trim();

        link.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2"></span>
            ${loadingText}
        `;

        // Simpan original HTML untuk restore
        link.dataset.originalHtml = text;

        setTimeout(() => {
            if (link.classList.contains("loading")) {
                link.classList.remove("loading");
                link.style.pointerEvents = "auto";
                link.innerHTML = link.dataset.originalHtml || text;
            }
        }, timeout);
    }

    // Export functions globally untuk keperluan custom scripts
    window.activateButtonLoader = activateButtonLoader;
    window.activateLinkLoader = activateLinkLoader;
});
