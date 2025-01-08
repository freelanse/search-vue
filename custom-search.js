const { createApp } = Vue;

createApp({
    data() {
        return {
            searchQuery: '',
            searchResults: [],
            isLoading: false,
            error: null,
        };
    },
    methods: {
        async performSearch() {
            if (!this.searchQuery.trim()) {
                this.searchResults = [];
                return;
            }
            
            this.isLoading = true;
            this.error = null;
            
            try {
                const response = await fetch(`${wpSearchData.ajax_url}?action=vue_search&nonce=${wpSearchData.nonce}&query=${encodeURIComponent(this.searchQuery)}`);
                
                if (!response.ok) {
                    throw new Error('Ошибка сервера');
                }
                
                const data = await response.json();
                if (data.success) {
                    this.searchResults = data.data;
                } else {
                    this.error = data.data || 'Ошибка при выполнении поиска';
                }
            } catch (err) {
                this.error = err.message;
            } finally {
                this.isLoading = false;
            }
        },
    },
    watch: {
        searchQuery: {
            handler: 'performSearch',
            immediate: false,
        },
    },
}).mount('#search-container');
