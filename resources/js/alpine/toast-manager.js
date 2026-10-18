export default function () {
    return {
        toasts: [],
        addToast(event) {
            const { message, variant = 'info', duration = 4000 } = event.detail;
            const id = Date.now() + Math.random();
            this.toasts.push({ id, message, variant });
            if (duration > 0) {
                setTimeout(() => this.removeToast(id), duration);
            }
        },
        removeToast(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        },
    };
}
