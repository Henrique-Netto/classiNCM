const MINI_BREAKPOINT = 1200;
const MOBILE_BREAKPOINT = 768;

function toggleMenu() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const content = document.querySelector('.main-content');

    if (window.innerWidth <= MOBILE_BREAKPOINT) {
        // Mobile: abre/fecha
        sidebar.classList.toggle('open');
        overlay.classList.toggle('show');
    } else {
        // Desktop: toggle manual do mini
        sidebar.classList.toggle('mini');
        content?.classList.toggle('mini');
    }
}

/* ===== CONTROLE AUTOMÁTICO ===== */
function handleResize() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const content = document.querySelector('.main-content');
    const width = window.innerWidth;

    // Mobile
    if (width <= MOBILE_BREAKPOINT) {
        sidebar.classList.remove('mini');
        content?.classList.remove('mini');
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
        return;
    }

    // Desktop médio → mini automático
    if (width <= MINI_BREAKPOINT) {
        sidebar.classList.add('mini');
        content?.classList.add('mini');
    } else {
        // Desktop grande → normal
        sidebar.classList.remove('mini');
        content?.classList.remove('mini');
    }
}

/* Executa ao carregar */
window.addEventListener('load', handleResize);

/* Executa ao redimensionar */
window.addEventListener('resize', handleResize);
