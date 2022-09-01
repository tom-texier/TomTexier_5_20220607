export default function initMenu() {
    const toggleMenu = document.getElementById('toggleMenu')
    const sideMenu = document.getElementById('sideMenu')
    const closeButton = sideMenu.querySelector('.close')

    toggleMenu.addEventListener('click', function() {
        sideMenu.classList.toggle('open')
    })

    closeButton.addEventListener('click', function() {
        sideMenu.classList.remove('open')
    })
}