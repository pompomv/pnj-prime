import './bootstrap';

import Alpine from 'alpinejs';
import intersect from '@alpinejs/intersect';
import collapse from '@alpinejs/collapse';

// Register Alpine plugins
Alpine.plugin(intersect);
Alpine.plugin(collapse);

// ===================== GLOBAL STORES =====================

Alpine.store('cart', {
    count: 0,
    items: [],
    init() {
        const saved = localStorage.getItem('pnjprime_cart');
        if (saved) {
            const data = JSON.parse(saved);
            this.items = data.items || [];
            this.count = this.items.length;
        }
    },
    add(course) {
        if (!this.items.find(i => i.id === course.id)) {
            this.items.push(course);
            this.count = this.items.length;
            this.save();
        }
    },
    remove(courseId) {
        this.items = this.items.filter(i => i.id !== courseId);
        this.count = this.items.length;
        this.save();
    },
    save() {
        localStorage.setItem('pnjprime_cart', JSON.stringify({ items: this.items }));
    }
});

// ===================== GLOBAL COMPONENTS =====================

Alpine.data('navbar', () => ({
    mobileOpen: false,
    scrolled: false,
    init() {
        window.addEventListener('scroll', () => {
            this.scrolled = window.scrollY > 20;
        });
    },
    toggle() {
        this.mobileOpen = !this.mobileOpen;
    }
}));

Alpine.data('courseCard', () => ({
    hovered: false,
    wishlist: false,
    toggleWishlist() {
        this.wishlist = !this.wishlist;
    }
}));

Alpine.data('videoPlayer', (lessonId) => ({
    playing: false,
    progress: 0,
    lessonId,
    toggle() {
        this.playing = !this.playing;
    }
}));

Alpine.data('filterPanel', () => ({
    open: false,
    selectedCategories: [],
    selectedLevels: [],
    priceRange: 'all',
    toggle() {
        this.open = !this.open;
    },
    reset() {
        this.selectedCategories = [];
        this.selectedLevels = [];
        this.priceRange = 'all';
    }
}));

Alpine.data('searchBar', () => ({
    query: '',
    open: false,
    results: [],
    loading: false,
    async search() {
        if (this.query.length < 2) {
            this.results = [];
            return;
        }
        this.loading = true;
        try {
            const res = await fetch(`/api/search?q=${encodeURIComponent(this.query)}`);
            this.results = await res.json();
        } catch (e) {
            this.results = [];
        }
        this.loading = false;
    }
}));

Alpine.data('toast', () => ({
    show: false,
    message: '',
    type: 'success',
    display(msg, type = 'success') {
        this.message = msg;
        this.type = type;
        this.show = true;
        setTimeout(() => { this.show = false; }, 3500);
    }
}));

// ===================== START ALPINE =====================
window.Alpine = Alpine;
Alpine.start();
