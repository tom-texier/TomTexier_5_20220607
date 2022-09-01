export default function initPreload()
{
    window.addEventListener('load', function() {
        document.querySelectorAll('.preload').forEach((element, index) => {
            element.classList.remove('preload');
        })
    })
}