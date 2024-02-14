import { MessageManager } from '../utils/MessageManager.js'

export class SliderManager {
  constructor(sliderToEditId) {
    this.sliderToEditId = sliderToEditId
    this.messageManager = new MessageManager()

    this.sliderNameInput = document.getElementById('sliderName')
    this.sliderTitleInput = document.getElementById('sliderTitle')
    this.sliderDescriptionInput = document.getElementById('sliderDescription')
    this.sliderRankInput = document.getElementById('sliderRank')
    this.sliderImageInput = document.getElementById('inputImageSlider')
    this.tokenInput = document.getElementById('token')
    this.jwt = localStorage.getItem('jwtToken')
  }

  addImage() {
    const button = document.getElementById('inputImageSlider')
    if (button) {
      button.removeEventListener('change', this.addImageListener)
      button.addEventListener('change', this.addImageListener)
    }
  }

  addImageListener = async () => {
    const sliderImagePreview = document.getElementById('sliderImagePreview')
    const input = document.getElementById('inputImageSlider')

    const file = await input.files[0]
    const reader = new FileReader()

    reader.addEventListener('load', function () {
      sliderImagePreview.setAttribute('src', reader.result)
    })

    reader.readAsDataURL(file)
  }

  addSlider() {
    const addSliderButtons = document.getElementById('addSlider')
    if (addSliderButtons) {
      addSliderButtons.addEventListener('click', async () => {
        try {
          const sliderName = this.sliderNameInput.value
          const sliderTitle = this.sliderTitleInput.value
          const sliderDescription = this.sliderDescriptionInput.value
          const sliderRank = this.sliderRankInput.value
          const token = this.tokenInput.value
          const jwt = this.jwt

          if (!sliderName || !sliderTitle || !sliderDescription) {
            const message = 'Veuillez remplir tous les champs.'
            throw new Error(message)
          }

          if (!token || !jwt) {
            const message = "Problème d'authentification"
            throw new Error(message)
          }

          const addData = new FormData()
          addData.append('sliderName', sliderName)
          addData.append('sliderTitle', sliderTitle)
          addData.append('sliderDescription', sliderDescription)
          addData.append('sliderRank', sliderRank)
          addData.append('token', token)

          if (this.sliderImageInput.files.length < 0) {
            const message = 'Veuillez ajouter une image'
            throw new Error(message)
          }

          const inputImageSlider = this.sliderImageInput.files[0]
          addData.append('inputImageSlider', inputImageSlider)

          const response = await fetch('index.php?controller=slider&action=add', {
            method: 'POST',
            body: addData,
            headers: {
              Authorization: `Bearer ${jwt}`,
            },
          })

          const data = await response.json()

          if (response.status === 200) {
            let sliderRankValue = parseInt(data.sliderRank) + 1
            this.sliderRankInput.value = sliderRankValue

            this.sliderNameInput.value = ''
            this.sliderTitleInput.value = ''
            this.sliderDescriptionInput.value = ''
            this.sliderImageInput.value = ''
            document.getElementById('sliderImagePreview').setAttribute('src', '')

            const sliderBlock = data.sliderBlock
            const sliderList = data.sliderList

            const displaySliderElement = document.getElementById('displaySlider')
            const displayBulletPointSliderElement = document.getElementById(
              'displayBulletPointSlider'
            )

            displaySliderElement.insertAdjacentHTML('beforeend', sliderBlock)
            displayBulletPointSliderElement.insertAdjacentHTML('beforeend', sliderList)

            this.deleteSlider()
            this.editSlider()
            this.updateSlider()
            this.upRankSlider()
            this.downRankSlider()

            this.messageManager.successMessage(data, 'messageContainer')
            localStorage.setItem('successMessage', JSON.stringify(data))
            location.reload()
          } else {
            if (response.status === 401) {
              this.messageManager.refreshMessage(data, 'messageContainer')
            } else if (response.status === 403) {
              window.location.href = 'index.php?controller=auth'
            } else if (response.status === 400 || response.status === 500) {
              this.messageManager.failedMessage(data, 'messageContainer')
            }
          }
        } catch (error) {
          console.error(error.message)
          this.messageManager.errorMessage(error, 'messageContainer')
        }
      })
    }
  }

  deleteSlider() {
    const buttons = document.querySelectorAll('.deleteSlider')
    for (const btn of buttons) {
      btn.removeEventListener('click', this.deleteSliderListener)
      btn.addEventListener('click', this.deleteSliderListener)
    }
  }

  deleteSliderListener = async (event) => {
    try {
      const btn = event.currentTarget
      const sliderId = btn.dataset.id
      const sliderName = document.getElementById('title' + sliderId).textContent
      const sliderRank = document.getElementById('inputRank' + sliderId).value
      const token = this.tokenInput.value
      const jwt = this.jwt

      if (!token || !jwt) {
        const message = "Problème d'authentification"
        throw new Error(message)
      }

      const deleteData = new FormData()
      deleteData.append('sliderId', sliderId)
      deleteData.append('sliderName', sliderName)
      deleteData.append('sliderRank', sliderRank)
      deleteData.append('token', token)

      const response = await fetch('index.php?controller=slider&action=delete', {
        method: 'POST',
        body: deleteData,
        headers: {
          Authorization: `Bearer ${jwt}`,
        },
      })
      const data = await response.json()

      if (response.status === 200) {
        let sliderRankValue = parseInt(this.sliderRankInput.value)
        sliderRankValue = sliderRankValue - 1
        this.sliderRankInput.value = sliderRankValue

        const selectRank = document.getElementById('inputRank' + data.sliderId).value
        if (selectRank) {
          const allRank = document.querySelectorAll('.inputRankSlider')
          for (let i = 0; i < allRank.length; i++) {
            if (allRank[i].value > selectRank) {
              allRank[i].value = allRank[i].value - 1
            }
          }
        }

        document.getElementById('pushSlider' + data.sliderId).remove()
        document.getElementById('bulletedSliderList' + data.sliderId).remove()

        this.sliderNameInput.value = ''
        this.sliderTitleInput.value = ''
        this.sliderDescriptionInput.value = ''

        document.getElementById('sliderImagePreview').setAttribute('src', '')
        const addSliderButton = document.getElementById('addSlider')
        const updateSliderButton = document.getElementById('updateSlider')

        addSliderButton.style.display = 'block'
        updateSliderButton.style.display = 'none'
        this.messageManager.successMessage(data, 'messageContainer')
      } else {
        if (response.status === 401) {
          this.messageManager.refreshMessage(data, 'messageContainer')
        } else if (response.status === 403) {
          window.location.href = 'index.php?controller=auth'
        } else if (response.status === 500) {
          this.messageManager.failedMessage(data, 'messageContainer')
        }
      }
    } catch (error) {
      console.log(error)
      this.messageManager.errorMessage(error, 'messageContainer')
    }
  }

  editSlider() {
    const buttons = document.querySelectorAll('.editSlider')
    for (const btn of buttons) {
      btn.addEventListener('click', async () => {
        try {
          window.scrollTo(0, 0)

          this.sliderToEditId = btn.dataset.id

          const sliderName = document.getElementById('title' + this.sliderToEditId).textContent
          const sliderTitle = document.getElementById('subtitle' + this.sliderToEditId).textContent
          const sliderDescription = document.getElementById(
            'desc' + this.sliderToEditId
          ).textContent

          this.sliderNameInput.value = sliderName
          this.sliderTitleInput.value = sliderTitle
          this.sliderDescriptionInput.value = sliderDescription

          const imgToEdit = sliderName + '.jpg'

          const sliderImagePreview = document.getElementById('sliderImagePreview')

          const response = await fetch('assets/news/' + imgToEdit, { method: 'HEAD' })

          if (response.status === 200) {
            const imageName = response.url.split('/').pop()
            sliderImagePreview.src = 'assets/news/' + imageName

            document.getElementById('actionSlider').textContent = 'Modifier'
            document.getElementById('addSlider').style.display = 'none'
            document.getElementById('updateSlider').style.display = 'block'
          } else {
            const message = "Erreur lors de la récupération de l'image"
            throw new Error(message)
          }
        } catch (error) {
          this.messageManager.errorMessage(error, 'messageContainer')
        }
      })
    }
  }

  updateSlider() {
    const updateSlider = document.getElementById('updateSlider')
    if (updateSlider) {
      updateSlider.removeEventListener('click', this.updateSliderListener)
      updateSlider.addEventListener('click', this.updateSliderListener)
    }
  }

  updateSliderListener = async () => {
    try {
      const sliderId = this.sliderToEditId
      const sliderName = this.sliderNameInput.value
      const sliderTitle = this.sliderTitleInput.value
      const sliderDescription = this.sliderDescriptionInput.value
      const token = this.tokenInput.value
      const sliderRank = document.getElementById('inputRank' + sliderId).value
      const jwt = this.jwt

      if (!sliderName || !sliderTitle || !sliderDescription) {
        const message = 'Veuillez remplir tous les champs.'
        throw new Error(message)
      }

      if (!token || !jwt) {
        const message = "Problème d'authentification"
        throw new Error(message)
      }

      const sliderImagePath = 'assets/news/' + sliderName + '.jpg'

      const updateData = new FormData()
      updateData.append('sliderId', sliderId)
      updateData.append('sliderName', sliderName)
      updateData.append('sliderTitle', sliderTitle)
      updateData.append('sliderDescription', sliderDescription)
      updateData.append('sliderImagePath', sliderImagePath)
      updateData.append('sliderRank', sliderRank)
      updateData.append('token', token)

      if (this.sliderImageInput.files.length > 0) {
        const inputImage = this.sliderImageInput.files[0]
        updateData.append('inputImage', inputImage)
      }

      const response = await fetch('index.php?controller=slider&action=edit', {
        method: 'POST',
        body: updateData,
        headers: {
          Authorization: `Bearer ${jwt}`,
        },
      })

      const data = await response.json()
      if (response.status === 200) {
        document.getElementById('actionSlider').textContent = 'Ajouter'

        document.getElementById('sliderName').value = ''
        document.getElementById('sliderTitle').value = ''
        document.getElementById('sliderDescription').value = ''
        document.getElementById('sliderImagePreview').src = ''
        document.getElementById('inputImageSlider').value = ''

        document.getElementById('addSlider').style.display = 'block'
        document.getElementById('updateSlider').style.display = 'none'

        document.getElementById('title' + data.sliderId).textContent = data.sliderName
        document.getElementById('subtitle' + data.sliderId).textContent = data.sliderTitle
        document.getElementById('desc' + data.sliderId).textContent = data.sliderDescription

        const sliderImagePath = 'assets/news/' + data.sliderName + '.jpg?' + new Date().getTime()

        document
          .getElementById('sliderImgPreview' + data.sliderId)
          .setAttribute('src', sliderImagePath)

        const sliderBulletElement = document.getElementById('sliderBullet' + data.sliderId)

        sliderBulletElement.querySelector('h5').textContent = data.sliderName

        this.messageManager.successMessage(data, 'messageContainer')
      } else {
        if (response.status === 401) {
          this.messageManager.refreshMessage(data, 'messageContainer')
        } else if (response.status === 403) {
          window.location.href = 'index.php?controller=auth'
        } else if (response.status === 400 || response.status === 500) {
          this.messageManager.failedMessage(data, 'messageContainer')
        }
      }
    } catch (error) {
      this.messageManager.errorMessage(error, 'messageContainer')
    }
  }

  upRankSlider() {
    const buttons = document.querySelectorAll('.downRankSlider')
    for (const btn of buttons) {
      btn.removeEventListener('click', this.upRankListener)
      btn.addEventListener('click', () => this.upRankListener(btn))
    }
  }

  downRankSlider() {
    const buttons = document.querySelectorAll('.upRankSlider')
    for (const btn of buttons) {
      btn.removeEventListener('click', this.downRankListener)
      btn.addEventListener('click', () => this.downRankListener(btn))
    }
  }

  upRankListener = async (btn) => {
    try {
      const thisSliderId = btn.dataset.id
      const sliderRankInput = document.getElementById('inputRank' + thisSliderId)
      const thisSliderBloc = document.getElementById('pushSlider' + thisSliderId)
      const token = this.tokenInput.value
      const jwt = this.jwt

      if (!token || !jwt) {
        const message = "Problème d'authentification"
        throw new Error(message)
      }

      let thisSliderRankValue = parseInt(sliderRankInput.value)

      let prevBloc
      let prevSliderBlocId
      let previousSliderRankValue

      prevBloc = thisSliderBloc.previousElementSibling
      if (!prevBloc) {
        const message = 'Opération impossible : Aucun élément précédent trouvé.'
        throw new Error(message)
      }

      prevSliderBlocId = prevBloc.dataset.id
      previousSliderRankValue = parseInt(
        thisSliderBloc.previousElementSibling.querySelector('.inputRankSlider').value
      )
      previousSliderRankValue++
      thisSliderRankValue--

      const upRankData = new FormData()
      upRankData.append('thisSliderRankValue', thisSliderRankValue)
      upRankData.append('thisSliderId', thisSliderId)
      upRankData.append('previousSliderRankValue', previousSliderRankValue)
      upRankData.append('prevSliderBlocId', prevSliderBlocId)
      upRankData.append('token', token)

      const response = await fetch('index.php?controller=slider&action=upRank', {
        method: 'POST',
        body: upRankData,
        headers: {
          Authorization: `Bearer ${jwt}`,
        },
      })

      const data = await response.json()

      if (response.status === 200) {
        document.getElementById('inputRank' + data.thisSliderId).value = data.thisSliderRankValue
        const thisSliderBloc = document.getElementById('pushSlider' + data.thisSliderId)
        const thisSliderBullet = document.getElementById('bulletedSliderList' + data.thisSliderId)

        const prevSliderBloc = thisSliderBloc.previousElementSibling
        const previousSliderRankInput = prevSliderBloc.querySelector('.inputRankSlider')
        previousSliderRankInput.value = parseInt(previousSliderRankInput.value) + 1
        thisSliderBloc.parentNode.insertBefore(prevSliderBloc, thisSliderBloc.nextSibling)

        const prevSliderBullet = thisSliderBullet.previousElementSibling
        thisSliderBullet.parentNode.insertBefore(prevSliderBullet, thisSliderBullet.nextSibling)
      } else {
        if (response.status === 401) {
          this.messageManager.refreshMessage(data, 'messageContainer')
        } else if (response.status === 403) {
          window.location.href = 'index.php?controller=auth'
        } else if (response.status === 500) {
          this.messageManager.failedMessage(data, 'messageContainer')
        }
      }
    } catch (error) {
      this.messageManager.errorMessage(error, 'messageContainer')
    }
  }

  downRankListener = async (btn) => {
    try {
      const thisSliderId = btn.dataset.id
      const sliderRankInput = document.getElementById('inputRank' + thisSliderId)
      const thisSliderBloc = document.getElementById('pushSlider' + thisSliderId)
      const token = this.tokenInput.value
      const jwt = this.jwt

      if (!token || !jwt) {
        const message = "Problème d'authentification"
        throw new Error(message)
      }

      let thisSliderRankValue = parseInt(sliderRankInput.value)

      let nextBloc
      let nextSliderBlocId
      let nextSliderRankValue

      nextBloc = thisSliderBloc.nextElementSibling

      if (!nextBloc) {
        const message = 'Opération impossible : Aucun élément suivant trouvé.'
        throw new Error(message)
      }

      nextSliderBlocId = nextBloc.dataset.id
      nextSliderRankValue = parseInt(
        thisSliderBloc.nextElementSibling.querySelector('.inputRankSlider').value
      )
      nextSliderRankValue--
      thisSliderRankValue++

      const downRankData = new FormData()
      downRankData.append('thisSliderRankValue', thisSliderRankValue)
      downRankData.append('thisSliderId', thisSliderId)
      downRankData.append('nextSliderRankValue', nextSliderRankValue)
      downRankData.append('nextSliderBlocId', nextSliderBlocId)
      downRankData.append('token', token)

      const response = await fetch('index.php?controller=slider&action=downRank', {
        method: 'POST',
        body: downRankData,
        headers: {
          Authorization: `Bearer ${jwt}`,
        },
      })

      const data = await response.json()

      if (response.status === 200) {
        document.getElementById('inputRank' + data.thisSliderId).value = data.thisSliderRankValue
        const thisSliderBloc = document.getElementById('pushSlider' + data.thisSliderId)
        const thisSliderBullet = document.getElementById('bulletedSliderList' + data.thisSliderId)
        const parentSlider = document.getElementById('displaySlider')
        const parentBulletSlider = document.getElementById('displayBulletPointSlider')

        const nextSliderBloc = thisSliderBloc.nextElementSibling
        const nextSliderRankInput = nextSliderBloc.querySelector('.inputRankSlider')
        nextSliderRankInput.value = parseInt(nextSliderRankInput.value) - 1
        parentSlider.insertBefore(nextSliderBloc, thisSliderBloc)

        const nextSliderBullet = thisSliderBullet.nextElementSibling
        parentBulletSlider.insertBefore(nextSliderBullet, thisSliderBullet)
      } else {
        if (response.status === 401) {
          this.messageManager.refreshMessage(data, 'messageContainer')
        } else if (response.status === 403) {
          window.location.href = 'index.php?controller=auth'
        } else if (response.status === 500) {
          this.messageManager.failedMessage(data, 'messageContainer')
        }
      }
    } catch (error) {
      this.messageManager.errorMessage(error, 'messageContainer')
    }
  }
}
