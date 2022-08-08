export default function initUpload()
{
    document.querySelectorAll('.form-action .form-group__image input').forEach((input, index) => {
        input.addEventListener('change', function(e) {
            let parentNode = input.parentNode;
            parentNode.querySelector('.image-upload-preview').style.display = 'flex';
            let imageNode = parentNode.querySelector('.image-upload-preview img');
            imageNode.src = URL.createObjectURL(e.target.files[0]);
            parentNode.querySelector('.label .name-preview').textContent = e.target.files[0].name;
        })
    })
}