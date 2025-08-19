window.multiSelect = function(config) {
    return {
        options: config.options,
        model: config.model,
        searchable: config.searchable,
        compact: config.compact,
        placeholder: config.placeholder || 'Select items...',
        open: false,
        search: '',
        selected: [],

        init() {
            // Initialize selected items based on model value
            if (this.model && this.model.length > 0) {
                this.selected = this.options.filter(option => 
                    this.model.includes(option.id)
                );
            }
        },

        get filteredOptions() {
            if (!this.search) return this.options;
            return this.options.filter(option =>
                option.name.toLowerCase().includes(this.search.toLowerCase())
            );
        },

        isSelected(option) {
            return this.selected.some(item => item.id === option.id);
        },

        toggleOption(option) {
            if (this.isSelected(option)) {
                this.removeItem(option);
            } else {
                this.addItem(option);
            }
        },

        addItem(option) {
            this.selected.push(option);
            this.model = this.selected.map(item => item.id);
        },

        removeItem(option) {
            this.selected = this.selected.filter(item => item.id !== option.id);
            this.model = this.selected.map(item => item.id);
        }
    }
} 