const selectTags = document.querySelectorAll('select');
if (selectTags) {
    selectTags.forEach(tag => {
        tag.addEventListener('change', () => {
            const untouchedOption = tag.querySelector('option[name=untouched]');
            if (untouchedOption) {
                untouchedOption.remove();
            }
        })

    })
}
