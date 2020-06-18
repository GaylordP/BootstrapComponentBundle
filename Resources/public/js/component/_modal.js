import $ from 'jquery'

$(document).ready(() => {
    global.BootstrapModal = (title, body) => {
        let modal = document.querySelector('#bootstrapModal')

        if (null === modal) {
            modal = document.createElement('div')
            modal.setAttribute('class', 'modal fade')
            modal.setAttribute('id', 'bootstrapModal')
            modal.setAttribute('tabindex', '-1')
            modal.setAttribute('role', 'dialog')
            modal.setAttribute('aria-labelledby', 'bootstrapModalTitle')
            modal.setAttribute('aria-hidden', 'true')

            modal.innerHTML = '<div class="modal-dialog modal-dialog-centered modal-xl" role="document">\n' +
            '    <div class="modal-content">\n' +
            '        <div class="modal-header">\n' +
            '            <h5 class="modal-title"></h5>\n' +
            '            <button type="button" class="close" data-dismiss="modal" aria-label="Close">\n' +
            '                <i class="fas fa-times"></i>\n' +
            '            </button>\n' +
            '        </div>\n' +
            '        <div class="modal-body"></div>\n' +
            '    </div>\n' +
            '</div>\n'

            document.body.appendChild(modal)

            $(modal).on('hidden.bs.modal', () => {
                modal.querySelector('.modal-title').innerHTML = ''
                modal.querySelector('.modal-body').innerHTML = ''
            })
        }

        modal.querySelector('.modal-title').innerHTML = title
        modal.querySelector('.modal-body').innerHTML = body
        $(modal).modal('show')
        $(modal).find('[data-toggle="tooltip"]').tooltip()
    }
})
