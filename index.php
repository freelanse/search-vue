<div id="search-container" class="search-container">
    <input
        id="ajax-search"
        class="search__input"
        type="text"
        v-model="searchQuery"
        placeholder="Я ищу фейерверк 100 залпов"
        autocomplete="off">
    <div 
        id="search-results" 
        class="search-results" 
        :class="{ active: searchResults.length || isLoading || error }">
        <div v-if="isLoading" class="loading">Загрузка...</div>
        <div v-if="error">{{ error }}</div>
        <ul v-if="searchResults.length">
            <li v-for="result in searchResults" :key="result.link">
                <a :href="result.link">{{ result.title }}</a>
            </li>
        </ul>
        <div v-if="!isLoading && !error && searchResults.length === 0 && searchQuery">
            Ничего не найдено
        </div>
    </div>
</div>
