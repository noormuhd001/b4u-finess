const editToggle = document.getElementById('editToggle');
const cancelEdit = document.getElementById('cancelEdit');
const viewMode = document.getElementById('viewMode');
const editMode = document.getElementById('editMode');

if (editToggle && cancelEdit && viewMode && editMode) {
    editToggle.addEventListener('click', function () {
        viewMode.classList.add('d-none');
        editMode.classList.remove('d-none');
    });

    cancelEdit.addEventListener('click', function () {
        editMode.classList.add('d-none');
        viewMode.classList.remove('d-none');
    });
}

const profilePictureInput = document.getElementById('profile-picture');
const profilePictureError = document.getElementById('profile-picture-error');
const profilePreview = document.getElementById('profilePreview');

if (profilePictureInput && profilePictureError && profilePreview) {
    const originalPreview = profilePreview.getAttribute('src');
    const allowedExtensions = new Set(['jpg', 'jpeg', 'png', 'gif']);
    const allowedMimeTypes = new Set(['image/jpeg', 'image/png', 'image/gif']);
    let previewUrl = null;

    function clearPreviewUrl() {
        if (previewUrl) {
            URL.revokeObjectURL(previewUrl);
            previewUrl = null;
        }
    }

    function showProfilePictureError(message) {
        profilePictureInput.classList.add('is-invalid');
        profilePictureInput.setAttribute('aria-invalid', 'true');
        profilePictureError.textContent = message;
        profilePictureError.classList.add('d-block');
    }

    function clearProfilePictureError() {
        profilePictureInput.classList.remove('is-invalid');
        profilePictureInput.removeAttribute('aria-invalid');
        profilePictureError.textContent = '';
        profilePictureError.classList.remove('d-block');
    }

    profilePictureInput.addEventListener('change', function () {
        const file = profilePictureInput.files[0];
        if (!file) {
            return;
        }

        const extension = file.name.split('.').pop().toLowerCase();
        const isAllowedType = allowedExtensions.has(extension)
            && (!file.type || allowedMimeTypes.has(file.type));
        if (!isAllowedType) {
            profilePictureInput.value = '';
            clearPreviewUrl();
            profilePreview.src = originalPreview;
            showProfilePictureError('Choose a JPG, PNG, or GIF image.');
            return;
        }

        if (file.size > Number(profilePictureInput.dataset.maxBytes)) {
            profilePictureInput.value = '';
            clearPreviewUrl();
            profilePreview.src = originalPreview;
            showProfilePictureError('The profile picture must be no larger than 2 MB.');
            return;
        }

        clearProfilePictureError();
        clearPreviewUrl();
        previewUrl = URL.createObjectURL(file);
        profilePreview.src = previewUrl;
    });

    window.addEventListener('beforeunload', clearPreviewUrl);
}
