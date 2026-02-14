import './bootstrap';
import 'preline';  // most important for solve navigate livewire problem with window.HSStaticMethods.autoInit();
import Swal from 'sweetalert2' // most important for SweetAlert2 to integrate to the page ;

window.Swal = Swal

function initPrelineIfReady() {
    if (window.HSStaticMethods && typeof window.HSStaticMethods.autoInit === 'function') {
        window.HSStaticMethods.autoInit();
    } else {
        console.warn('HSStaticMethods not ready');
    }
}

// عند تحميل الصفحة أول مرة
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(initPrelineIfReady, 100);
});

// عند التنقل باستخدام Inertia أو Livewire
document.addEventListener('livewire:navigated', () => {
    const darkToggleBtn = document.getElementById('darkToggle');

    // Apply dark mode based on saved preference or system preference
    if (localStorage.getItem('theme') === 'dark' ||
        (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }

    // Toggle dark mode (if button exists on page)
    if (darkToggleBtn) {
        darkToggleBtn.addEventListener('click', () => {
            document.documentElement.classList.toggle('dark');
            const isDark = document.documentElement.classList.contains('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        });
    }
    setTimeout(initPrelineIfReady, 100);
});

// دعم لأي تغييرات ديناميكية
const observer = new MutationObserver(() => {
    initPrelineIfReady();
});
observer.observe(document.body, { childList: true, subtree: true });
