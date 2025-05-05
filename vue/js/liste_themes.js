document.addEventListener('DOMContentLoaded', function () {
    const addThemeButton = document.getElementById('add-theme-button');
    const themeInput = document.getElementById('new-theme');
    const themeList = document.getElementById('theme-list');
    const themesHidden = document.getElementById('themes-hidden');

    addThemeButton.addEventListener('click', () => {
        const theme = themeInput.value.trim();
        if (theme) {
            const li = document.createElement('li');
            li.textContent = theme;
            li.classList.add('theme-item');
            const removeButton = document.createElement('button');
            removeButton.textContent = '✖';
            removeButton.classList.add('remove-theme');
            removeButton.addEventListener('click', () => li.remove());
            li.appendChild(removeButton);
            themeList.appendChild(li);
            updateThemesHiddenInput();
            themeInput.value = '';
        }
    });

    function updateThemesHiddenInput() {
        const themes = Array.from(themeList.children).map(li => li.textContent.replace('✖', '').trim());
        themesHidden.value = JSON.stringify(themes);
    }
});
