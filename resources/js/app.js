import Alpine from "alpinejs";
import collapse from "@alpinejs/collapse";
import "./notificationDropdown";
import { createIcons, icons } from "lucide";

createIcons({ icons });

// Theme toggle: read preference, apply class, persist on changes
(function () {
    function applyTheme(theme) {
        if (theme === "dark") {
            document.documentElement.classList.add("dark");
        } else {
            document.documentElement.classList.remove("dark");
        }
    }

    // initialize from localStorage or OS preference
    var stored = null;
    try {
        stored = localStorage.getItem("theme");
    } catch (e) {
        /* noop */
    }
    if (stored) {
        applyTheme(stored);
    } else if (
        window.matchMedia &&
        window.matchMedia("(prefers-color-scheme: dark)").matches
    ) {
        applyTheme("dark");
    }

    // delegate click on #theme-toggle
    document.addEventListener("click", function (e) {
        var btn = e.target.closest && e.target.closest("#theme-toggle");
        if (!btn) return;
        var isDark = document.documentElement.classList.toggle("dark");
        try {
            localStorage.setItem("theme", isDark ? "dark" : "light");
        } catch (e) {
            /* noop */
        }
    });
})();

Alpine.plugin(collapse);
window.Alpine = Alpine;

Alpine.start();
