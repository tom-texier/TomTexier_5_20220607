export default function initModal()
{
    document.querySelectorAll('[data-confirm="true"]').forEach((element, index) => {
        element.addEventListener('click', openModal);
    })
}

function createModal()
{
    const HTML = `
        <div class="overlay"></div>
        <div class="modal-content">
            <p class="text"></p>
            <div class="actions">
                <div class="close actions--error" role="button">Annuler</div>
                <div class="actions--success"><a href="" class="url"></a></div>
            </div>
        </div>
    `;

    let modal = document.createElement('div');
    modal.id = 'confirm-modal';
    modal.innerHTML = HTML.trim();

    modal.querySelector('.close').addEventListener('click', closeModal);

    document.body.append(modal);
}

function modalExist()
{
    return !!document.getElementById('confirm-modal');
}

function openModal(e)
{
    e.preventDefault();
    let url_action = this.getAttribute('href');
    let title = this.getAttribute('title');

    if(!modalExist()) {
        createModal();
    }

    document.getElementById('confirm-modal').querySelector('p.text').textContent = "Êtes-vous sûr de vouloir " + title.toLowerCase() + ' ?';
    document.getElementById('confirm-modal').querySelector('.actions--success .url').textContent = title;
    document.getElementById('confirm-modal').querySelector('.actions--success .url').setAttribute('href', url_action);

    document.getElementById('confirm-modal').classList.add('show');
}

function closeModal()
{
    document.getElementById('confirm-modal').classList.remove('show');
}