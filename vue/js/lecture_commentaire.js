function toggleComments(propositionId) {
    const section = document.getElementById(`comments-${propositionId}`);
    section.classList.toggle('visible');
}

function toggleEmojiPicker(button) {
    const emojiPicker = button.closest('form').querySelector('.emoji-picker');
    emojiPicker.classList.toggle('hidden');
}

function addEmoji(emoji, emojiElement) {
    const form = emojiElement.closest('form');
    const input = form.querySelector('input[name="commentaire"]');
    if (input) {
        input.value += emoji;
    }
}


