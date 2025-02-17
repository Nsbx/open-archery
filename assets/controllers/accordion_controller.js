import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['button', 'content']

    connect() {
        this.contentTargets.forEach((content) => {
            if (this.getButton(content).getAttribute('aria-expanded') === 'true') {
                content.classList.remove('hidden');
                content.style.height = content.scrollHeight + 'px';
            } else {
                content.classList.add('hidden');
                content.style.height = '0';
            }
        });
    }

    toggle(event) {
        const button = event.currentTarget;
        const content = this.getContent(button);
        const isExpanded = button.getAttribute('aria-expanded') === 'true';

        if (isExpanded) {
            this.closeItem(button, content);
        } else {
            this.closeAllItems();
            this.openItem(button, content);
        }
    }

    getButton(content) {
        const itemId = content.dataset.itemId;
        return this.buttonTargets.find(button => button.dataset.itemId === itemId);
    }

    getContent(button) {
        const itemId = button.dataset.itemId;
        return this.contentTargets.find(content => content.dataset.itemId === itemId);
    }

    openItem(button, content) {
        button.setAttribute('aria-expanded', 'true');
        content.classList.remove('hidden');

        content.style.height = content.scrollHeight + 'px';
    }

    closeItem(button, content) {
        button.setAttribute('aria-expanded', 'false');

        content.style.height = content.scrollHeight + 'px';
        content.style.height = '0';
        content.classList.add('hidden');
    }

    closeAllItems() {
        this.buttonTargets.forEach((button) => {
            if (button.getAttribute('aria-expanded') === 'true') {
                const content = this.getContent(button);
                this.closeItem(button, content);
            }
        });
    }
}
