"use strict"

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('form')

    form.addEventListener('submit', formSend)

    async function formSend(e) {
        e.preventDefault()

        const error = formValidate(form)
        const formData = new FormData(form)

        formData.append('image', formImage.files[0])

        if (error === 0) {
            form.classList.add('_sending')

            const response = await fetch('../../sendmail.php', {
                method: 'POST',
                body: formData
            })

            if (response.ok) {
                const result = await response.json()

                alert(result.message)
                formPreview.innerHTML = ''
                form.reset()
                form.classList.remove('_sending')
            } else {
                alert('Ошибка')
                form.classList.remove('_sending')
            }
        } else {
            alert('Заполните обязательные поля')
        }
    }

    function formValidate() {
        let error = 0
        const formReq = document.querySelectorAll('.__req')

        formReq.forEach(input => {
            formRemoveError(input)

            if (input.classList.contains('_email')) {
                if (!emailTest(input)) {
                    formAddError(input)
                    error++
                }
            } else if (input.getAttribute('type') === 'checkbox' && input.checked === false) {
                formAddError(input)
                error++
            } else {
                if (input.value === '') {
                    formAddError(input)
                    error++
                }
            }
        })

        return error
    }

    function formAddError(input) {
        input.parentElement.classList.add('_error')
        input.classList.add('_error')
    }

    function formRemoveError(input) {
        input.parentElement.classList.remove('_error')
        input.classList.remove('_error')
    }

    function emailTest(input) {
        return /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(input.value)
    }

    const formImage = document.getElementById('formImage')
    const formPreview = document.getElementById('formPreview')

    formImage.addEventListener('change', () => {
        uploadFile(formImage.files[0])
    })

    function uploadFile(file) {
        if (!['image/jpeg', 'image/png', 'image/gif'].includes(file.type)) {
            alert('Разрешены только изображения.')

            formImage.value = ''
        }

        if (file.size > 2 * 1024 * 1024) {
            alert('Файл должен быть менее 2мб')
        }

        const reader = new FileReader()

        reader.addEventListener('load', e => {
            formPreview.innerHTML = `<img src="${e.target.result}" alt="Фото">`
        })

        reader.addEventListener('error', () => {
            alert('Ошибка')
        })

        reader.readAsDataURL(file)
    }
})